
<?php

use Notion\Pages\Page;
use Notion\Pages\Properties\PropertyCollection;
use Notion\Pages\Properties\RichTextProperty;
use Notion\Pages\Properties\Url;
use Notion\Pages\Properties\Select;
use Notion\Databases\Properties\SelectOption;
use Notion\Common\RichText;
use SamihSoylu\Crunchyroll\Api\Notion\Entity\Option\EpisodeStatus;
use SamihSoylu\Crunchyroll\Api\Notion\Entity\Serie;
use SamihSoylu\Crunchyroll\Api\Notion\Entity\Field\Episode;

function getMockPage(): Page
{
    $json = file_get_contents(__DIR__ . '/MockPage.json');
    $array = json_decode($json, true);


    return Page::fromArray($array);
}

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
