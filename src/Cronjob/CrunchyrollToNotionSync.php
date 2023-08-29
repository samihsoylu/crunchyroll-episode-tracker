<?php

declare(strict_types=1);

namespace SamihSoylu\Crunchyroll\Cronjob;

use Psr\Log\LoggerInterface;
use SamihSoylu\Crunchyroll\Api\Crunchyroll\CrunchyrollApiClient;
use SamihSoylu\Crunchyroll\Api\Crunchyroll\Entity\AnimeEpisode;
use SamihSoylu\Crunchyroll\Api\Notion\Entity\Field\Episode;
use SamihSoylu\Crunchyroll\Api\Notion\Entity\Option\EpisodeStatus;
use SamihSoylu\Crunchyroll\Api\Notion\Entity\SerieInterface;
use SamihSoylu\Crunchyroll\Api\Notion\NotionApiClient;

final readonly class CrunchyrollToNotionSync implements CronjobInterface
{
    public function __construct(
        private CrunchyrollApiClient $crunchyroll,
        private NotionApiClient $notion,
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(mixed ...$args): void
    {
        $latestEpisodesOnCrunchyroll = $this->crunchyroll->animeEpisode()->getLatestEpisodes();
        $seriesOnNotion = $this->notion->series()->getAll($this->getNotionDatabaseId(...$args));

        $this->syncSeries($seriesOnNotion, $latestEpisodesOnCrunchyroll);
    }

    private function getNotionDatabaseId(mixed ...$args): string
    {
        $notionDatabaseId = $args[0] ?? null;
        if ($notionDatabaseId === null) {
            throw new \LogicException('Notion database id must be provided');
        }

        /** @phpstan-ignore-next-line  */
        return (string) $notionDatabaseId;
    }

    /**
     * @param SerieInterface[] $seriesOnNotion
     * @param AnimeEpisode[] $latestEpisodesOnCrunchyroll
     * @return void
     */
    private function syncSeries(array $seriesOnNotion, array $latestEpisodesOnCrunchyroll): void
    {
        foreach ($seriesOnNotion as $serie) {
            if ($this->isMarkedAsNewEpisode($serie)) {
                continue;
            }

            $serieName = strtolower($serie->getName());

            $crunchyrollEpisode = $latestEpisodesOnCrunchyroll[$serieName] ?? null;
            if ($crunchyrollEpisode === null) {
                $this->logger->info("Skipped Series[name={$serie->getName()}], found no new Episodes");

                continue;
            }

            $this->updateSerieIfNeeded($serie, $crunchyrollEpisode);
        }
    }

    private function isMarkedAsNewEpisode(SerieInterface $serie): bool
    {
        if ($serie->getCurrentEpisodeStatus() === EpisodeStatus::NEW_EPISODE) {
            $this->logger->info("Skipped Series[name={$serie->getName()}], since it's already marked as New Episode");

            return true;
        }

        return false;
    }

    private function updateSerieIfNeeded(SerieInterface $serie, AnimeEpisode $crunchyrollEpisode): void
    {
        $isOldEpisode = $serie->getCurrentEpisode()->isOldEpisode(
            $crunchyrollEpisode->getSeasonNumber(),
            $crunchyrollEpisode->getEpisodeNumber()
        );

        $isBehindMultipleEpisodes = $serie->getCurrentEpisode()->isBehindMultipleEpisodes(
            $crunchyrollEpisode->getSeasonNumber(),
            $crunchyrollEpisode->getEpisodeNumber()
        );

        if (!$isOldEpisode) {
            $this->logger->info(
                "Matched Series[name={$serie->getName()}, season={$crunchyrollEpisode->getSeasonNumber()}, episode={$crunchyrollEpisode->getEpisodeNumber()}] but has no new episodes."
            );

            return;
        }

        if ($isBehindMultipleEpisodes) {
            $this->logger->info(
                "Matched Series[name={$serie->getName()}], but too many episodes behind, only updated the badge, and current link to Unknown"
            );

            $serie->setCurrentEpisodeStatus(EpisodeStatus::newEpisode());
            $serie->setCurrentEpisodeUrl("You're behind multiple episodes");

            $this->notion->series()->update($serie);

            return;
        }

        $this->updateSerieDetails($serie, $crunchyrollEpisode);
    }

    private function updateSerieDetails(SerieInterface $serie, AnimeEpisode $crunchyrollEpisode): void
    {
        $serie->setPreviousEpisode($serie->getCurrentEpisode());
        $serie->setCurrentEpisode(new Episode(
            $crunchyrollEpisode->getSeasonNumber(),
            $crunchyrollEpisode->getEpisodeNumber(),
        ));
        $serie->setCurrentEpisodeUrl($crunchyrollEpisode->getLink());
        $serie->setCurrentEpisodeStatus(EpisodeStatus::newEpisode());

        $this->notion->series()->update($serie);
    }
}
