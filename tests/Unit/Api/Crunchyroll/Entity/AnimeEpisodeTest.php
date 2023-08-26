<?php

declare(strict_types=1);

use SamihSoylu\Crunchyroll\Api\Crunchyroll\Entity\AnimeEpisode;


it('should create AnimeEpisode from SimpleXMLElement', function () {
    $xml = new SimpleXMLElement('
        <item xmlns:crunchyroll="http://www.crunchyroll.com/rss">
            <title>Episode 1</title>
            <link>http://example.com/episode-1</link>
            <description>Some description</description>
            <crunchyroll:seriesTitle>Naruto</crunchyroll:seriesTitle>
            <crunchyroll:episodeTitle>Enter: Naruto</crunchyroll:episodeTitle>
            <crunchyroll:episodeNumber>1</crunchyroll:episodeNumber>
            <crunchyroll:season>1</crunchyroll:season>
            <crunchyroll:duration>24m</crunchyroll:duration>
            <crunchyroll:publisher>Crunchyroll</crunchyroll:publisher>
            <crunchyroll:premiumPubDate>2002-10-03</crunchyroll:premiumPubDate>
        </item>'
    );

    $episode = AnimeEpisode::fromSimpleXmlElement($xml);

    assertAnimeEpisodePropertiesMatch($episode);
});

it('should create AnimeEpisode from array', function () {
    $data = [
        'title' => 'Episode 1',
        'link' => 'http://example.com/episode-1',
        'description' => 'Some description',
        'seriesTitle' => 'Naruto',
        'episodeTitle' => 'Enter: Naruto',
        'episodeNumber' => 1,
        'seasonNumber' => 1,
        'duration' => '24m',
        'publisher' => 'Crunchyroll',
        'publishedDate' => '2002-10-03',
    ];

    $episode = AnimeEpisode::fromArray($data);

    assertAnimeEpisodePropertiesMatch($episode);
});

function assertAnimeEpisodePropertiesMatch(AnimeEpisode $episode): void
{
    expect($episode->getTitle())->toBe('Episode 1')
        ->and($episode->getLink())->toBe('http://example.com/episode-1')
        ->and($episode->getDescription())->toBe('Some description')
        ->and($episode->getSeriesTitle())->toBe('Naruto')
        ->and($episode->getEpisodeTitle())->toBe('Enter: Naruto')
        ->and($episode->getEpisodeNumber())->toBe(1)
        ->and($episode->getSeasonNumber())->toBe(1)
        ->and($episode->getDuration())->toBe('24m')
        ->and($episode->getPublisher())->toBe('Crunchyroll')
        ->and($episode->getPublishedDate()->format('Y-m-d'))->toBe('2002-10-03');
}