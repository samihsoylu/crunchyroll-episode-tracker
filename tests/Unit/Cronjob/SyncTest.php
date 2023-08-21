<?php

// behavior do we want to test?
// data retrieved from crunchyroll is processed properly
// data send is invoked to notion.

use Samihsoylu\Crunchyroll\Api\Crunchyroll\CrunchyrollApiClient;
use Samihsoylu\Crunchyroll\Api\Notion\NotionApiClient;
use Samihsoylu\Crunchyroll\Cronjob\Sync;
use Samihsoylu\Crunchyroll\Tests\TestDouble\Fake\FakeAnimeEpisodeRepository;
use Samihsoylu\Crunchyroll\Tests\TestDouble\Spy\SpyDatabaseRepository;

test('sync', function () {
    $spyRepository = new SpyDatabaseRepository();

    $sync = new Sync(
        new CrunchyrollApiClient(new FakeAnimeEpisodeRepository()),
        new NotionApiClient($spyRepository)
    );

    $sync('0987654321');

    $spyRepository->assertUpdateInvoked();
});
