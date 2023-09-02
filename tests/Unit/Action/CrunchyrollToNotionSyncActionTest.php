<?php

declare(strict_types=1);

use SamihSoylu\CrunchyrollSyncer\Action\CrunchyrollToNotionSyncAction;
use SamihSoylu\CrunchyrollSyncer\Api\Crunchyroll\CrunchyrollApiClient;
use SamihSoylu\CrunchyrollSyncer\Api\Notion\Entity\Option\EpisodeStatus;
use SamihSoylu\CrunchyrollSyncer\Api\Notion\NotionApiClient;
use SamihSoylu\CrunchyrollSyncer\Tests\Framework\TestDouble\Dummy\DummyLogger;
use SamihSoylu\CrunchyrollSyncer\Tests\Framework\TestDouble\Spy\SpyLogger;

it('should update the series if a new crunchyroll episode is found', function () {
    [$notionSpy, $crunchyrollSpy] = testKit()->action()->createCrunchyrollToNotionSyncActionSpies(
        crunchyrollLatestSeason: 1,
        crunchyrollLatestEpisodeNumber: 2,
        currentEpisodeStatus: EpisodeStatus::watched()
    );

    $sync = new CrunchyrollToNotionSyncAction(
        new CrunchyrollApiClient($crunchyrollSpy),
        new NotionApiClient($notionSpy),
        new DummyLogger()
    );
    $sync('fake-notion-database-id');

    expect($notionSpy->getUpdatedSeries())->toHaveCount(1);
});

it('should not update the series if no new crunchyroll episode is found', function () {
    [$notionSpy, $crunchyrollSpy] = testKit()->action()->createCrunchyrollToNotionSyncActionSpies(
        crunchyrollLatestSeason: 1,
        crunchyrollLatestEpisodeNumber: 1
    );

    $sync = new CrunchyrollToNotionSyncAction(
        new CrunchyrollApiClient($crunchyrollSpy),
        new NotionApiClient($notionSpy),
        new DummyLogger()
    );
    $sync('fake-notion-database-id');

    expect($notionSpy->getUpdatedSeries())->toHaveCount(0);
});

it('should not update the series if crunchyroll has no information about the series', function () {
    [$notionSpy, $crunchyrollSpy] = testKit()->action()->createCrunchyrollToNotionSyncActionSpies();

    $sync = new CrunchyrollToNotionSyncAction(
        new CrunchyrollApiClient($crunchyrollSpy),
        new NotionApiClient($notionSpy),
        new DummyLogger()
    );
    $sync('fake-notion-database-id');

    expect($notionSpy->getUpdatedSeries())->toHaveCount(0);
});

it('should not update any series if there are no series in notion', function () {
    [$notionSpy, $crunchyrollSpy] = testKit()->action()->createCrunchyrollToNotionSyncActionSpies(
        crunchyrollLatestSeason: 1,
        crunchyrollLatestEpisodeNumber: 2,
        isEmptyNotion: true
    );

    $sync = new CrunchyrollToNotionSyncAction(
        new CrunchyrollApiClient($crunchyrollSpy),
        new NotionApiClient($notionSpy),
        new DummyLogger()
    );
    $sync('fake-notion-database-id');

    expect($notionSpy->getUpdatedSeries())->toHaveCount(0);
});

it('should skip series marked as new episode', function () {
    [$notionSpy, $crunchyrollSpy] = testKit()->action()->createCrunchyrollToNotionSyncActionSpies(
        currentEpisodeStatus: EpisodeStatus::newEpisode()
    );

    $sync = new CrunchyrollToNotionSyncAction(
        new CrunchyrollApiClient($crunchyrollSpy),
        new NotionApiClient($notionSpy),
        new DummyLogger()
    );
    $sync('fake-notion-database-id');

    expect($notionSpy->getUpdatedSeries())->toHaveCount(0);
});

it('should throw a logic exception if the notion database id is not provided', function () {
    [$notionSpy, $crunchyrollSpy] = testKit()->action()->createCrunchyrollToNotionSyncActionSpies();

    $sync = new CrunchyrollToNotionSyncAction(
        new CrunchyrollApiClient($crunchyrollSpy),
        new NotionApiClient($notionSpy),
        new DummyLogger()
    );

    $sync();
})->throws(LogicException::class)->expectExceptionMessage('Notion database id must be provided');

it('should log an info message if no new crunchyroll episodes are found for the series', function () {
    $spyLogger = new SpyLogger();
    [$notionSpy, $crunchyrollSpy] = testKit()->action()->createCrunchyrollToNotionSyncActionSpies(currentEpisodeStatus: EpisodeStatus::watched());

    $sync = new CrunchyrollToNotionSyncAction(
        new CrunchyrollApiClient($crunchyrollSpy),
        new NotionApiClient($notionSpy),
        $spyLogger
    );
    $sync('fake-notion-database-id');

    expect($spyLogger->getLogs())->toContain("Skipped Series[name=Naruto], found no new Episodes");
});

it('should log an info message if matched series but it is recent', function () {
    $spyLogger = new SpyLogger();
    [$notionSpy, $crunchyrollSpy] = testKit()->action()->createCrunchyrollToNotionSyncActionSpies(
        crunchyrollLatestSeason: 1,
        crunchyrollLatestEpisodeNumber: 1,
        currentEpisodeStatus: EpisodeStatus::watched(),
    );

    $sync = new CrunchyrollToNotionSyncAction(
        new CrunchyrollApiClient($crunchyrollSpy),
        new NotionApiClient($notionSpy),
        $spyLogger
    );
    $sync('fake-notion-database-id');

    expect($spyLogger->getLogs())->toContain('Matched Series[name=Naruto, season=1, episode=1] but has no new episodes.');
});

it('should log an info message when behind several episodes', function () {
    $spyLogger = new SpyLogger();
    [$notionSpy, $crunchyrollSpy] = testKit()->action()->createCrunchyrollToNotionSyncActionSpies(
        crunchyrollLatestSeason: 2,
        crunchyrollLatestEpisodeNumber: 1,
        currentEpisodeStatus: EpisodeStatus::watched(),
    );

    $sync = new CrunchyrollToNotionSyncAction(
        new CrunchyrollApiClient($crunchyrollSpy),
        new NotionApiClient($notionSpy),
        $spyLogger
    );
    $sync('fake-notion-database-id');

    expect($spyLogger->getLogs())->toContain('Matched Series[name=Naruto], but too many episodes behind, only updated the badge, and current link to Unknown');
});

it('should log an info message when new episode', function () {
    $spyLogger = new SpyLogger();
    [$notionSpy, $crunchyrollSpy] = testKit()->action()->createCrunchyrollToNotionSyncActionSpies(
        crunchyrollLatestSeason: 1,
        crunchyrollLatestEpisodeNumber: 2,
        currentEpisodeStatus: EpisodeStatus::watched(),
    );

    $sync = new CrunchyrollToNotionSyncAction(
        new CrunchyrollApiClient($crunchyrollSpy),
        new NotionApiClient($notionSpy),
        $spyLogger
    );
    $sync('fake-notion-database-id');

    expect($spyLogger->getLogs())->toContain('Matched Series[name=Naruto, season=1, episode=2] synced to Notion');
});
