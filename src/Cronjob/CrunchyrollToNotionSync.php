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

final class CrunchyrollToNotionSync
{
    public function __construct(
        private readonly CrunchyrollApiClient $crunchyroll,
        private readonly NotionApiClient $notion,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(string $notionDatabaseId): void
    {
        $latestEpisodesOnCrunchyroll = $this->crunchyroll->animeEpisode()->getLatestEpisodes();
        $seriesOnNotion = $this->notion->series()->getAll($notionDatabaseId);

        $this->syncSeries($seriesOnNotion, $latestEpisodesOnCrunchyroll);
    }

    private function syncSeries(array $seriesOnNotion, array $latestEpisodesOnCrunchyroll): void
    {
        foreach ($seriesOnNotion as $serie) {
            if ($this->isMarkedAsNewEpisode($serie)) {
                continue;
            }

            $this->updateSerieIfNeeded($serie, $latestEpisodesOnCrunchyroll);
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

    /**
     * @param AnimeEpisode[] $crunchyrollEpisodes
     */
    private function updateSerieIfNeeded(SerieInterface $serie, array $crunchyrollEpisodes): void
    {
        $matchedEpisode = $this->findMatchedEpisode($serie, $crunchyrollEpisodes);
        if ($matchedEpisode === null) {
            return;
        }

        $this->updateSerieBasedOnEpisode($serie, $matchedEpisode);
    }

    private function findMatchedEpisode(SerieInterface $serie, array $crunchyrollEpisodes): ?AnimeEpisode
    {
        foreach ($crunchyrollEpisodes as $episode) {
            if (str_contains($serie->getName(), $episode->getSeriesTitle())) {
                return $episode;
            }
        }

        return null;
    }

    private function updateSerieBasedOnEpisode(SerieInterface $serie, AnimeEpisode $crunchyrollEpisode): void
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
            $serie->setCurrentEpisodeUrl('Unknown');

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
