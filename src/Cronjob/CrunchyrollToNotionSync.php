<?php

declare(strict_types=1);

namespace SamihSoylu\Crunchyroll\Cronjob;

use Notion\Databases\Properties\Status;
use Psr\Log\LoggerInterface;
use SamihSoylu\Crunchyroll\Api\Crunchyroll\CrunchyrollApiClient;
use SamihSoylu\Crunchyroll\Api\Crunchyroll\Entity\AnimeEpisode;
use SamihSoylu\Crunchyroll\Api\Notion\Entity\Field\Episode;
use SamihSoylu\Crunchyroll\Api\Notion\Entity\Option\EpisodeStatus;
use SamihSoylu\Crunchyroll\Api\Notion\Entity\Serie;
use SamihSoylu\Crunchyroll\Api\Notion\Entity\SerieInterface;
use SamihSoylu\Crunchyroll\Api\Notion\NotionApiClient;

final class CrunchyrollToNotionSync
{
    public function __construct(
        private readonly CrunchyrollApiClient $crunchyroll,
        private readonly NotionApiClient $notion,
        private readonly LoggerInterface $logger,
    ) {}

    public function __invoke(string $notionDatabaseId): void
    {
        $latestEpisodesOnCrunchyroll = $this->crunchyroll->animeEpisode()->getLatestEpisodes();
        $seriesOnNotion = $this->notion->series()->getAll($notionDatabaseId);

        foreach ($seriesOnNotion as $serie) {
            $this->updateSerieIfNeeded($serie, $latestEpisodesOnCrunchyroll);
        }
    }

    /**
     * @param AnimeEpisode[] $crunchyrollEpisodes
     */
    private function updateSerieIfNeeded(SerieInterface $serie, array $crunchyrollEpisodes): void
    {
        $crunchyrollEpisode = null;
        foreach ($crunchyrollEpisodes as $episode) {
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
            $this->logger->info(
                "Matched Series[name={$serie->getName()}, season={$crunchyrollEpisode->getSeasonNumber()}, episode={$crunchyrollEpisode->getEpisodeNumber()}] but has no new episodes. "
            );
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