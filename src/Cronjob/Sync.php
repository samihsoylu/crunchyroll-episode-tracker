<?php

declare(strict_types=1);

namespace SamihSoylu\Crunchyroll\Cronjob;

use Notion\Databases\Properties\Status;
use SamihSoylu\Crunchyroll\Api\Crunchyroll\CrunchyrollApiClient;
use SamihSoylu\Crunchyroll\Api\Notion\Entity\CustomField\Watched;
use SamihSoylu\Crunchyroll\Api\Notion\NotionApiClient;

final class Sync
{
    public function __construct(
        private readonly CrunchyrollApiClient $crunchyrollApiClient,
        private readonly NotionApiClient $notionApiClient,
    ) {}

    public function __invoke(string $notionDatabaseId): void
    {
        // grab from notion all "watched" series
        // grab what's new from crunchyroll (feed)
        // compare if watched series is in crunchyroll feed?
        // yes: check episode number (comparison)
        // yes: In Notion set status field to "new episode"
        // insert link to "new_episode_url" column/fiel
        //$episodes = $this->crunchyrollApiClient->getAnimeEpisodeRepository()->getLatestEpisodes();

        $series = $this->notionApiClient->getSeriesRepository()->getAllSeriesByDatabaseId($notionDatabaseId);
        foreach ($series as $serie) {
            $serie->setStatus(Watched::newEpisodes());
            $serie->setNewEpisodeUrl('https://google.com');
            //$this->notionApiClient->getSeriesRepository()->updateSerie($serie->toApiPage());
            echo "Updated {$serie->getName()}\n";
        }
    }
}