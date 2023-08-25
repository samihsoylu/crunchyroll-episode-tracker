<?php

// behavior do we want to test?
// data retrieved from crunchyroll is processed properly
// data send is invoked to notion.

use SamihSoylu\Crunchyroll\Api\Crunchyroll\CrunchyrollApiClient;
use SamihSoylu\Crunchyroll\Api\Notion\NotionApiClient;
use SamihSoylu\Crunchyroll\Cronjob\Sync;
use SamihSoylu\Crunchyroll\Tests\TestDouble\Fake\FakeAnimeEpisodeRepository;
use SamihSoylu\Crunchyroll\Tests\TestDouble\Spy\SpyDatabaseRepository;

test('sync', function () {
    $spyRepository = new SpyDatabaseRepository();

    $sync = new Sync(
        new CrunchyrollApiClient(new FakeAnimeEpisodeRepository()),
        new NotionApiClient($spyRepository)
    );

    $sync('0987654321');

    $spyRepository->assertUpdateInvoked();
});
