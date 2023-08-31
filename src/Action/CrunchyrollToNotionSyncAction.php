<?php

declare(strict_types=1);

namespace SamihSoylu\Crunchyroll\Action;

use Psr\Log\LoggerInterface;
use SamihSoylu\Crunchyroll\Action\Dto\CurrentEpisode;
use SamihSoylu\Crunchyroll\Api\Crunchyroll\CrunchyrollApiClient;
use SamihSoylu\Crunchyroll\Api\Crunchyroll\Entity\AnimeEpisode;
use SamihSoylu\Crunchyroll\Api\Notion\Entity\Field\Episode;
use SamihSoylu\Crunchyroll\Api\Notion\Entity\Option\EpisodeStatus;
use SamihSoylu\Crunchyroll\Api\Notion\Entity\SerieInterface;
use SamihSoylu\Crunchyroll\Api\Notion\NotionApiClient;

final readonly class CrunchyrollToNotionSyncAction implements ActionInterface
{
    public function __construct(
        private CrunchyrollApiClient $crunchyroll,
        private NotionApiClient $notion,
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(mixed ...$args): void
    {
        $episodes = $this->crunchyroll->animeEpisode()->getLatestEpisodes();
        $series = $this->notion->series()->getAll($this->getNotionDatabaseId(...$args));

        $this->syncEpisodes($series, $episodes);
    }

    private function getNotionDatabaseId(mixed ...$args): string
    {
        $notionDatabaseId = $args[0] ?? throw new \LogicException('Notion database id must be provided');

        /** @phpstan-ignore-next-line  */
        return (string) $notionDatabaseId;
    }

    /**
     * @param SerieInterface[] $series
     * @param AnimeEpisode[] $episodes
     */
    private function syncEpisodes(array $series, array $episodes): void
    {
        foreach ($series as $serie) {
            $serie->isMarkedAsNewEpisode()
                ? $this->logAlreadyFlagged($serie->getName())
                : $this->processSeries($serie, $episodes);
        }
    }

    /**
     * @param AnimeEpisode[] $episodes
     */
    private function processSeries(SerieInterface $serie, array $episodes): void
    {
        $newEpisode = $episodes[strtolower($serie->getName())] ?? null;

        $newEpisode === null
            ? $this->logNoEpisodes($serie->getName())
            : $this->updateSerieIfNeeded($serie, $newEpisode);
    }

    private function updateSerieIfNeeded(SerieInterface $serie, AnimeEpisode $newEpisode): void
    {
        $current = new CurrentEpisode($serie->getCurrentEpisode());

        if (!$current->isNewEpisode($newEpisode)) {
            $this->logNothingNew(
                $serie->getName(),
                $newEpisode->getSeasonNumber(),
                $newEpisode->getEpisodeNumber(),
            );

            return;
        }

        $current->isBehind($newEpisode)
            ? $this->setSeriesBehind($serie)
            : $this->setSeriesNewEpisode($serie, $newEpisode);
    }

    private function setSeriesNewEpisode(SerieInterface $serie, AnimeEpisode $newEpisode): void
    {
        $this->logIsNew($newEpisode);

        $serie->setPreviousEpisode($serie->getCurrentEpisode());
        $serie->setCurrentEpisode(new Episode(
            $newEpisode->getSeasonNumber(),
            $newEpisode->getEpisodeNumber(),
        ));
        $serie->setCurrentEpisodeUrl($newEpisode->getLink());
        $serie->setCurrentEpisodeStatus(EpisodeStatus::newEpisode());

        $this->notion->series()->update($serie);
    }

    protected function setSeriesBehind(SerieInterface $serie): void
    {
        $this->logIsBehind($serie->getName());

        $serie->setCurrentEpisodeStatus(EpisodeStatus::newEpisode());
        $serie->setCurrentEpisodeUrl("You're behind multiple episodes");

        $this->notion->series()->update($serie);
    }

    private function logAlreadyFlagged(string $seriesName): void
    {
        $this->logger->info("Skipped Series[name={$seriesName}], since it's already marked as New Episode");
    }

    protected function logNoEpisodes(string $seriesName): void
    {
        $this->logger->info("Skipped Series[name={$seriesName}], found no new Episodes");
    }

    protected function logNothingNew(string $seriesName, int $season, int $episode): void
    {
        $this->logger->info(
            "Matched Series[name={$seriesName}, season={$season}, episode={$episode}] but has no new episodes."
        );
    }

    protected function logIsBehind(string $seriesName): void
    {
        $this->logger->info(
            "Matched Series[name={$seriesName}], but too many episodes behind, only updated the badge, and current link to Unknown"
        );
    }

    protected function logIsNew(AnimeEpisode $episode): void
    {
        $this->logger->info(
            "Matched Series[name={$episode->getSeriesTitle()}, season={$episode->getSeasonNumber()}, episode={$episode->getEpisodeNumber()}] synced to Notion"
        );
    }
}
