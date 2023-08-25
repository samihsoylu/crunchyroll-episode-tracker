<?php

declare(strict_types=1);

namespace SamihSoylu\Crunchyroll\Cronjob;

use SamihSoylu\Crunchyroll\Api\Crunchyroll\CrunchyrollApiClient;
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
        $episodes = $this->crunchyrollApiClient->getAnimeEpisodeRepository()->getLatestEpisodes();


        $this->notionApiClient->getDatabaseRepository()->update(new \StdClass);
        //$this->notionApiClient->getDatabaseRepository()->getById($notionDatabaseId);
    }
}