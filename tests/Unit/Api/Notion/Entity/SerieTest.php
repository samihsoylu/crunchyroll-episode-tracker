
<?php

use SamihSoylu\CrunchyrollSyncer\Api\Notion\Entity\Field\Episode;
use SamihSoylu\CrunchyrollSyncer\Api\Notion\Entity\Option\EpisodeStatus;
use SamihSoylu\CrunchyrollSyncer\Api\Notion\Entity\Serie;

it('should get the name of the serie', function () {
    $serie = Serie::fromApiPage(testKit()->notion()->loadFakePage());

    expect($serie->getName())->toBe('Am I Actually the Strongest?');
});


it('should get the current episode of the serie', function () {
    $serie = Serie::fromApiPage(testKit()->notion()->loadFakePage());

    expect($serie->getCurrentEpisode()->getSeasonNumber())->toBe(1)
        ->and($serie->getCurrentEpisode()->getEpisodeNumber())->toBe(6);
});

it('should set the current episode of the serie', function () {
    $serie = Serie::fromApiPage(testKit()->notion()->loadFakePage());
    $newEpisode = Episode::fromString('S01E07');

    $serie->setCurrentEpisode($newEpisode);

    expect($serie->getCurrentEpisode()->getEpisodeNumber())->toBe(7)
        ->and($serie->getCurrentEpisode()->getSeasonNumber())->toBe(1);
});

it('should set the previous episode of the serie', function () {
    $serie = Serie::fromApiPage(testKit()->notion()->loadFakePage());
    $previousEpisode = Episode::fromString('S01E05');

    $serie->setPreviousEpisode($previousEpisode);

    $actualPreviousEpisode = $serie->toApiPage()
        ->properties()
        ->get('Previous episode')
        ->toArray()['rich_text'][0]['plain_text'];

    expect($actualPreviousEpisode)->toBe('S01E05');
});

it('should set the current episode url of the serie', function () {
    $serie = Serie::fromApiPage(testKit()->notion()->loadFakePage());
    $newUrl = 'https://new-episode-url.com';

    $serie->setCurrentEpisodeUrl($newUrl);

    $actualCurrentEpisodeUrl = $serie->toApiPage()
        ->properties()
        ->get('Current episode url')
        ->toArray()['url'];

    expect($actualCurrentEpisodeUrl)->toBe($newUrl);
});

it('should set the current episode status of the serie', function () {
    $serie = Serie::fromApiPage(testKit()->notion()->loadFakePage());

    $serie->setCurrentEpisodeStatus(EpisodeStatus::newEpisode());

    $actualCurrentEpisodeStatus = $serie->toApiPage()
        ->properties()
        ->get('Current episode status')
        ->toArray()['select']['name'];

    expect($actualCurrentEpisodeStatus)->toBe(EpisodeStatus::NEW_EPISODE);
});

it('should get the current episode status of the serie', function () {
    $serie = Serie::fromApiPage(testKit()->notion()->loadFakePage());

    expect($serie->getCurrentEpisodeStatus())->toBe(EpisodeStatus::WATCHED);
});


it('should get the marked as new episode false', function () {
    $serie = Serie::fromApiPage(testKit()->notion()->loadFakePage());

    expect($serie->isMarkedAsNewEpisode())->toBeFalse();
});
