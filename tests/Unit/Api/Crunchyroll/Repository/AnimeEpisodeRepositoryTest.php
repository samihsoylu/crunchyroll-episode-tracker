<?php

declare(strict_types=1);

use SamihSoylu\CrunchyrollSyncer\Api\Crunchyroll\Repository\AnimeEpisodeRepository;
use SamihSoylu\CrunchyrollSyncer\Tests\Framework\TestDouble\Fake\FakeCrunchyrollRssParser;

it('should retrieve latest episodes', function () {
    $fakeParser = new FakeCrunchyrollRssParser(testKit()->crunchyroll()->createRssFeedXml());
    $animeEpisodeRepository = new AnimeEpisodeRepository($fakeParser);

    $latestEpisodes = $animeEpisodeRepository->getLatestEpisodes();

    expect($latestEpisodes)->toBeArray()
        ->and($latestEpisodes)->toHaveCount(1);
});

it('throws exception for feed structure change', function () {
    $fakeParser = new FakeCrunchyrollRssParser(testKit()->crunchyroll()->createRssFeedXml(channelTitle: 'new feed name'));
    $animeEpisodeRepository = new AnimeEpisodeRepository($fakeParser);

    $animeEpisodeRepository->getLatestEpisodes();
})->throws(LogicException::class, 'Feed structure has changed');

it('filters out dubbed episodes', function () {
    $fakeParser = new FakeCrunchyrollRssParser(testKit()->crunchyroll()->createRssFeedXml(isDubbed: true));
    $animeEpisodeRepository = new AnimeEpisodeRepository($fakeParser);

    $latestEpisodes = $animeEpisodeRepository->getLatestEpisodes();

    expect($latestEpisodes)->toBeArray()
        ->and($latestEpisodes)->toHaveCount(0);
});
