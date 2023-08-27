<?php

declare(strict_types=1);

use SamihSoylu\Crunchyroll\Api\Crunchyroll\Repository\AnimeEpisodeRepository;
use SamihSoylu\Crunchyroll\Tests\Framework\TestDouble\Fake\FakeCrunchyrollRssParser;

it('should retrieve latest episodes', function () {
    $fakeParser = new FakeCrunchyrollRssParser(getXml());
    $animeEpisodeRepository = new AnimeEpisodeRepository($fakeParser);

    $latestEpisodes = $animeEpisodeRepository->getLatestEpisodes();

    expect($latestEpisodes)->toBeArray()
        ->and($latestEpisodes)->toHaveCount(1);
});

it('throws exception for feed structure change', function () {
    $fakeParser = new FakeCrunchyrollRssParser(getXml('new feed name'));
    $animeEpisodeRepository = new AnimeEpisodeRepository($fakeParser);

    $animeEpisodeRepository->getLatestEpisodes();
})->throws(LogicException::class, 'Feed structure has changed');

it('filters out dubbed episodes', function () {
    $fakeParser = new FakeCrunchyrollRssParser(getXml(isDubbed: true));
    $animeEpisodeRepository = new AnimeEpisodeRepository($fakeParser);

    $latestEpisodes = $animeEpisodeRepository->getLatestEpisodes();

    expect($latestEpisodes)->toBeArray()
        ->and($latestEpisodes)->toHaveCount(0);
});

function getXml(?string $channelTitle = null, bool $isDubbed = false): SimpleXMLElement
{
    $channelTitle = $channelTitle ?? 'Latest Crunchyroll Anime Videos';
    $dub = ($isDubbed) ? ' (French Dub)' : '';

    $xml = <<<XML
<?xml version="1.0"?>
<rss version="2.0" xmlns:media="http://search.yahoo.com/mrss/" xmlns:crunchyroll="http://www.crunchyroll.com/rss">
    <channel>
        <title>{$channelTitle}</title>
        <item>
            <title>My Fake episode{$dub}</title>
            <crunchyroll:mediaId>905352</crunchyroll:mediaId>
        </item>
    </channel>
</rss>
XML;

    return simplexml_load_string($xml);
}
