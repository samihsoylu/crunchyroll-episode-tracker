<?php

declare(strict_types=1);

use SamihSoylu\CrunchyrollSyncer\Api\Crunchyroll\CrunchyrollApiClient;
use SamihSoylu\CrunchyrollSyncer\Console\Crunchyroll\Anime\LatestCommand;

it('should display latest episodes correctly', function () {
    $expectedEpisodes = testKit()->crunchyroll()->createExpectedEpisodes();

    $output = testKit()->executeConsoleCommand(
        new LatestCommand(
            new CrunchyrollApiClient(
                testKit()
                    ->crunchyroll()
                    ->createFakeAnimeRepository(...$expectedEpisodes)
            )
        )
    )->getDisplay();

    foreach ($expectedEpisodes as $expectedEpisode) {
        expect($output)->toContain(
            $expectedEpisode->getSeriesTitle(),
            $expectedEpisode->getSeasonNumber(),
            $expectedEpisode->getEpisodeNumber(),
            $expectedEpisode->getPublishedDate()->format('d-m-Y h:i'),
        );
    }
});
