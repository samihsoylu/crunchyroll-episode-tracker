<?php

declare(strict_types=1);

namespace SamihSoylu\Crunchyroll\Cronjob;

use Notion\Databases\Properties\Status;
use SamihSoylu\Crunchyroll\Api\Crunchyroll\CrunchyrollApiClient;
use SamihSoylu\Crunchyroll\Api\Crunchyroll\Entity\AnimeEpisode;
use SamihSoylu\Crunchyroll\Api\Notion\Entity\Field\Episode;
use SamihSoylu\Crunchyroll\Api\Notion\Entity\Option\EpisodeStatus;
use SamihSoylu\Crunchyroll\Api\Notion\Entity\Serie;
use SamihSoylu\Crunchyroll\Api\Notion\NotionApiClient;

final class CrunchyrollToNotionSync
{
    public function __construct(
        private readonly CrunchyrollApiClient $crunchyrollApiClient,
        private readonly NotionApiClient $notionApiClient,
    ) {}

    public function __invoke(string $notionDatabaseId): void
    {
        $episodes = $this->crunchyrollApiClient->getAnimeEpisodeRepository()->getLatestEpisodes();
        $series = $this->notionApiClient->getSeriesRepository()->getAllSeriesByDatabaseId($notionDatabaseId);

        foreach ($series as $serie) {
            $this->updateSerieIfNeeded($serie, $episodes);
        }
    }

    /**
     * @param AnimeEpisode[] $episodes
     */
    private function updateSerieIfNeeded(Serie $serie, array $episodes): void
    {
        $crunchyrollEpisode = null;
        foreach ($episodes as $episode) {
            if (str_contains($serie->getName(), $episode->getSeriesTitle())) {
                $crunchyrollEpisode = $episode;
            }
        }

        if ($crunchyrollEpisode === null) {
            return;
        }

        $isOldEpisode = $serie->getCurrentEpisode()->isOldEpisode(
            $crunchyrollEpisode->getSeasonNumber(),
            $crunchyrollEpisode->getEpisodeNumber()
        );

        if (!$isOldEpisode) {
            return;
        }

        $this->updateSerieDetails($serie, $crunchyrollEpisode);
    }

    private function updateSerieDetails(Serie $serie, AnimeEpisode $crunchyrollEpisode): void
    {
        $serie->setPreviousEpisode($serie->getCurrentEpisode());
        $serie->setCurrentEpisode(new Episode(
            $crunchyrollEpisode->getSeasonNumber(),
            $crunchyrollEpisode->getEpisodeNumber(),
        ));
        $serie->setCurrentEpisodeUrl($crunchyrollEpisode->getLink());
        $serie->setCurrentEpisodeStatus(EpisodeStatus::newEpisode());

        $this->notionApiClient->getSeriesRepository()->updateSerie($serie);
    }
}