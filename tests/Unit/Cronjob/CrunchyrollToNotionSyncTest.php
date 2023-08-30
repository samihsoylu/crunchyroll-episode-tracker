<?php

declare(strict_types=1);

use Notion\Databases\Properties\SelectOption;
use SamihSoylu\Crunchyroll\Api\Crunchyroll\CrunchyrollApiClient;
use SamihSoylu\Crunchyroll\Api\Notion\Entity\Field\Episode;
use SamihSoylu\Crunchyroll\Api\Notion\Entity\Option\EpisodeStatus;
use SamihSoylu\Crunchyroll\Api\Notion\NotionApiClient;
use SamihSoylu\Crunchyroll\Cronjob\CrunchyrollToNotionSync;
use SamihSoylu\Crunchyroll\Tests\Framework\Builder\AnimeEpisodeBuilder;
use SamihSoylu\Crunchyroll\Tests\Framework\Builder\FakeSerieBuilder;
use SamihSoylu\Crunchyroll\Tests\Framework\TestDouble\Dummy\DummyLogger;
use SamihSoylu\Crunchyroll\Tests\Framework\TestDouble\Spy\SpyAnimeEpisodeRepository;
use SamihSoylu\Crunchyroll\Tests\Framework\TestDouble\Spy\SpyLogger;
use SamihSoylu\Crunchyroll\Tests\Framework\TestDouble\Spy\SpySeriesRepository;

it('should update the series if a new Crunchyroll episode is found', function () {
    [$notionSpy, $crunchyrollSpy] = createSpies(
        crunchyrollLatestSeason: 1,
        crunchyrollLatestEpisodeNumber: 2,
        currentEpisodeStatus: EpisodeStatus::watched()
    );

    $sync = new CrunchyrollToNotionSync(
        new CrunchyrollApiClient($crunchyrollSpy),
        new NotionApiClient($notionSpy),
        new DummyLogger()
    );
    $sync('fake-notion-database-id');

    expect($notionSpy->getUpdatedSeries())->toHaveCount(1);
});

it('should not update the series if no new Crunchyroll episode is found', function () {
    [$notionSpy, $crunchyrollSpy] = createSpies(crunchyrollLatestSeason: 1, crunchyrollLatestEpisodeNumber: 1);

    $sync = new CrunchyrollToNotionSync(
        new CrunchyrollApiClient($crunchyrollSpy),
        new NotionApiClient($notionSpy),
        new DummyLogger()
    );
    $sync('fake-notion-database-id');

    expect($notionSpy->getUpdatedSeries())->toHaveCount(0);
});

it('should not update the series if crunchyroll has no information about the series', function () {
    [$notionSpy, $crunchyrollSpy] = createSpies();

    $sync = new CrunchyrollToNotionSync(
        new CrunchyrollApiClient($crunchyrollSpy),
        new NotionApiClient($notionSpy),
        new DummyLogger()
    );
    $sync('fake-notion-database-id');

    expect($notionSpy->getUpdatedSeries())->toHaveCount(0);
});

it('should not update any series if there are no series in notion', function () {
    [$notionSpy, $crunchyrollSpy] = createSpies(
        crunchyrollLatestSeason: 1,
        crunchyrollLatestEpisodeNumber: 2,
        isEmptyNotion: true
    );

    $sync = new CrunchyrollToNotionSync(
        new CrunchyrollApiClient($crunchyrollSpy),
        new NotionApiClient($notionSpy),
        new DummyLogger()
    );
    $sync('fake-notion-database-id');

    expect($notionSpy->getUpdatedSeries())->toHaveCount(0);
});

it('should skip series marked as new episode', function () {
    [$notionSpy, $crunchyrollSpy] = createSpies(
        currentEpisodeStatus: EpisodeStatus::newEpisode()
    );

    $sync = new CrunchyrollToNotionSync(
        new CrunchyrollApiClient($crunchyrollSpy),
        new NotionApiClient($notionSpy),
        new DummyLogger()
    );
    $sync('fake-notion-database-id');

    expect($notionSpy->getUpdatedSeries())->toHaveCount(0);
});

it('should throw a LogicException if the Notion database id is not provided', function () {
    [$notionSpy, $crunchyrollSpy] = createSpies();

    $sync = new CrunchyrollToNotionSync(
        new CrunchyrollApiClient($crunchyrollSpy),
        new NotionApiClient($notionSpy),
        new DummyLogger()
    );

    $sync();
})->throws(LogicException::class)->expectExceptionMessage('Notion database id must be provided');

it('should log an info message if no new crunchyroll episodes are found for the series', function () {
    $spyLogger = new SpyLogger();
    [$notionSpy, $crunchyrollSpy] = createSpies(currentEpisodeStatus: EpisodeStatus::watched());

    $sync = new CrunchyrollToNotionSync(
        new CrunchyrollApiClient($crunchyrollSpy),
        new NotionApiClient($notionSpy),
        $spyLogger
    );
    $sync('fake-notion-database-id');

    expect($spyLogger->getLogs())->toContain("Skipped Series[name=Naruto], found no new Episodes");
});

it('should log an info message if matched series but it is recent', function () {
    $spyLogger = new SpyLogger();
    [$notionSpy, $crunchyrollSpy] = createSpies(
        crunchyrollLatestSeason: 1,
        crunchyrollLatestEpisodeNumber: 1,
        currentEpisodeStatus: EpisodeStatus::watched(),
    );

    $sync = new CrunchyrollToNotionSync(
        new CrunchyrollApiClient($crunchyrollSpy),
        new NotionApiClient($notionSpy),
        $spyLogger
    );
    $sync('fake-notion-database-id');

    expect($spyLogger->getLogs())->toContain('Matched Series[name=Naruto, season=1, episode=1] but has no new episodes.');
});

it('should log an info message when behind several episodes', function () {
    $spyLogger = new SpyLogger();
    [$notionSpy, $crunchyrollSpy] = createSpies(
        crunchyrollLatestSeason: 2,
        crunchyrollLatestEpisodeNumber: 1,
        currentEpisodeStatus: EpisodeStatus::watched(),
    );

    $sync = new CrunchyrollToNotionSync(
        new CrunchyrollApiClient($crunchyrollSpy),
        new NotionApiClient($notionSpy),
        $spyLogger
    );
    $sync('fake-notion-database-id');

    expect($spyLogger->getLogs())->toContain('Matched Series[name=Naruto], but too many episodes behind, only updated the badge, and current link to Unknown');
});

it('should log an info message when new episode', function () {
    $spyLogger = new SpyLogger();
    [$notionSpy, $crunchyrollSpy] = createSpies(
        crunchyrollLatestSeason: 1,
        crunchyrollLatestEpisodeNumber: 2,
        currentEpisodeStatus: EpisodeStatus::watched(),
    );

    $sync = new CrunchyrollToNotionSync(
        new CrunchyrollApiClient($crunchyrollSpy),
        new NotionApiClient($notionSpy),
        $spyLogger
    );
    $sync('fake-notion-database-id');

    expect($spyLogger->getLogs())->toContain('Matched Series[name=Naruto, season=1, episode=2] synced to Notion');
});

function createSpies(
    string $serieName = 'Naruto',
    int $notionCurrentSeason = 1,
    int $notionCurrentEpisodeNumber = 1,
    ?int $crunchyrollLatestSeason = null,
    ?int $crunchyrollLatestEpisodeNumber = null,
    ?bool $isEmptyNotion = false,
    ?SelectOption $currentEpisodeStatus = null,
): array {
    $notionSpy = new SpySeriesRepository();

    if (!$isEmptyNotion) {
        $notionSpy->setAllSeries(
            (new FakeSerieBuilder())
                ->withName($serieName)
                ->withCurrentEpisode(new Episode($notionCurrentSeason, $notionCurrentEpisodeNumber))
                ->withCurrentEpisodeStatus($currentEpisodeStatus)
                ->build()
        );
    }

    $crunchyrollSpy = new SpyAnimeEpisodeRepository();

    if ($crunchyrollLatestSeason !== null && $crunchyrollLatestEpisodeNumber !== null) {
        $crunchyrollSpy->setLatestEpisodes(
            (new AnimeEpisodeBuilder())
                ->withSeriesTitle($serieName)
                ->withSeasonNumber($crunchyrollLatestSeason)
                ->withEpisodeNumber($crunchyrollLatestEpisodeNumber)
                ->build()
        );
    }

    return [$notionSpy, $crunchyrollSpy];
}
