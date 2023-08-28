
<?php

use Notion\Pages\Page;
use SamihSoylu\Crunchyroll\Api\Notion\Entity\Field\Episode;
use SamihSoylu\Crunchyroll\Api\Notion\Entity\Option\EpisodeStatus;
use SamihSoylu\Crunchyroll\Api\Notion\Entity\Serie;

it('should get the name of the serie', function () {
    $serie = Serie::fromApiPage(getMockPage());

    expect($serie->getName())->toBe('Am I Actually the Strongest?');
});


it('should get the current episode of the serie', function () {
    $serie = Serie::fromApiPage(getMockPage());

    expect($serie->getCurrentEpisode()->getSeasonNumber())->toBe(1)
        ->and($serie->getCurrentEpisode()->getEpisodeNumber())->toBe(6);
});

it('should set the current episode of the serie', function () {
    $serie = Serie::fromApiPage(getMockPage());
    $newEpisode = Episode::fromString('S01E07');

    $serie->setCurrentEpisode($newEpisode);

    expect($serie->getCurrentEpisode()->getEpisodeNumber())->toBe(7)
        ->and($serie->getCurrentEpisode()->getSeasonNumber())->toBe(1);
});

it('should set the previous episode of the serie', function () {
    $serie = Serie::fromApiPage(getMockPage());
    $previousEpisode = Episode::fromString('S01E05');

    $serie->setPreviousEpisode($previousEpisode);

    $actualPreviousEpisode = $serie->toApiPage()
        ->properties()
        ->get('Previous episode')
        ->toArray()['rich_text'][0]['plain_text'];

    expect($actualPreviousEpisode)->toBe('S01E05');
});

it('should set the current episode URL of the serie', function () {
    $serie = Serie::fromApiPage(getMockPage());
    $newUrl = 'https://new-episode-url.com';

    $serie->setCurrentEpisodeUrl($newUrl);

    $actualCurrentEpisodeUrl = $serie->toApiPage()
        ->properties()
        ->get('Current episode url')
        ->toArray()['url'];

    expect($actualCurrentEpisodeUrl)->toBe($newUrl);
});

it('should set the current episode status of the serie', function () {
    $serie = Serie::fromApiPage(getMockPage());

    $serie->setCurrentEpisodeStatus(EpisodeStatus::newEpisode());

    $actualCurrentEpisodeStatus = $serie->toApiPage()
        ->properties()
        ->get('Current episode status')
        ->toArray()['select']['name'];

    expect($actualCurrentEpisodeStatus)->toBe('New Episode');
});
