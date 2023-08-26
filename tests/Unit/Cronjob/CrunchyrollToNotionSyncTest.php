<?php

declare(strict_types=1);

use SamihSoylu\Crunchyroll\Api\Crunchyroll\CrunchyrollApiClient;
use SamihSoylu\Crunchyroll\Api\Notion\Entity\Field\Episode;
use SamihSoylu\Crunchyroll\Api\Notion\NotionApiClient;
use SamihSoylu\Crunchyroll\Cronjob\CrunchyrollToNotionSync;
use SamihSoylu\Crunchyroll\Tests\Framework\Builder\AnimeEpisodeBuilder;
use SamihSoylu\Crunchyroll\Tests\Framework\Builder\FakeSerieBuilder;
use SamihSoylu\Crunchyroll\Tests\Framework\TestDouble\Spy\SpyAnimeEpisodeRepository;
use SamihSoylu\Crunchyroll\Tests\Framework\TestDouble\Spy\SpySeriesRepository;

it('should update the series if a new Crunchyroll episode is found', function () {
    [$notionSpy, $crunchyrollSpy] = createSpies(latestSeason: 1, latestEpisodeNumber: 2);

    $sync = new CrunchyrollToNotionSync(
        new CrunchyrollApiClient($crunchyrollSpy),
        new NotionApiClient($notionSpy),
    );
    $sync('fake-notion-database-id');

    expect($notionSpy->getUpdatedSeries())->toHaveCount(1);
});

it('should not update the series if no new Crunchyroll episode is found', function () {
    [$notionSpy, $crunchyrollSpy] = createSpies(latestSeason: 1, latestEpisodeNumber: 1);

    $sync = new CrunchyrollToNotionSync(
        new CrunchyrollApiClient($crunchyrollSpy),
        new NotionApiClient($notionSpy),
    );
    $sync('fake-notion-database-id');

    expect($notionSpy->getUpdatedSeries())->toHaveCount(0);
});

it('should not update the series if Crunchyroll has no information about the series', function () {
    [$notionSpy, $crunchyrollSpy] = createSpies();

    $sync = new CrunchyrollToNotionSync(
        new CrunchyrollApiClient($crunchyrollSpy),
        new NotionApiClient($notionSpy),
    );
    $sync('fake-notion-database-id');

    expect($notionSpy->getUpdatedSeries())->toHaveCount(0);
});

it('should not update any series if there are no series in Notion', function () {
    [$notionSpy, $crunchyrollSpy] = createSpies(latestSeason: 1, latestEpisodeNumber: 2, isEmptyNotion: true);

    $sync = new CrunchyrollToNotionSync(
        new CrunchyrollApiClient($crunchyrollSpy),
        new NotionApiClient($notionSpy),
    );
    $sync('fake-notion-database-id');

    expect($notionSpy->getUpdatedSeries())->toHaveCount(0);
});

function createSpies(
    string $serieName = 'Naruto',
    int $currentSeason = 1,
    int $currentEpisodeNumber = 1,
    ?int $latestSeason = null,
    ?int $latestEpisodeNumber = null,
    ?bool $isEmptyNotion = false
): array {
    $notionSpy = new SpySeriesRepository();

    if (!$isEmptyNotion) {
        $notionSpy->setAllSeries(
            (new FakeSerieBuilder())
                ->withName($serieName)
                ->withCurrentEpisode(new Episode($currentSeason, $currentEpisodeNumber))
                ->build()
        );
    }

    $crunchyrollSpy = new SpyAnimeEpisodeRepository();

    if ($latestSeason !== null && $latestEpisodeNumber !== null) {
        $crunchyrollSpy->setLatestEpisodes(
            (new AnimeEpisodeBuilder())
                ->withSeriesTitle($serieName)
                ->withSeasonNumber($latestSeason)
                ->withEpisodeNumber($latestEpisodeNumber)
                ->build()
        );
    }

    return [$notionSpy, $crunchyrollSpy];
}