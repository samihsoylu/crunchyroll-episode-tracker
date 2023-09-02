<?php

declare(strict_types=1);

namespace SamihSoylu\CrunchyrollSyncer\Tests\Framework\Core;

use GuzzleHttp\Client as GuzzleHttpClient;
use SamihSoylu\CrunchyrollSyncer\Api\Crunchyroll\Entity\AnimeEpisode;
use SamihSoylu\CrunchyrollSyncer\Tests\Framework\Builder\AnimeEpisodeBuilder;
use SamihSoylu\CrunchyrollSyncer\Tests\Framework\TestDouble\Fake\FakeAnimeEpisodeRepository;
use SimpleXMLElement;

final class CrunchyrollTestHelper
{
    /**
     * @return AnimeEpisode[]
     */
    public function createExpectedEpisodes(): array
    {
        $build = new AnimeEpisodeBuilder();
        $expectedEpisodes[] = $build->withSeriesTitle('Naruto')
            ->withSeasonNumber(3)
            ->withEpisodeNumber(6)
            ->withPublishedDate('28-08-2023 03:00')
            ->build();
        $expectedEpisodes[] = $build->withSeriesTitle('Demon Slayer')
            ->withSeasonNumber(2)
            ->withEpisodeNumber(15)
            ->withPublishedDate('28-08-2023 03:05')
            ->build();

        return $expectedEpisodes;
    }

    public function createFakeAnimeRepository(AnimeEpisode ...$episodes): FakeAnimeEpisodeRepository
    {
        $repository = new FakeAnimeEpisodeRepository();
        $repository->setLatestEpisodes(...$episodes);

        return $repository;
    }

    public function createMockHttpClient(
        string $method,
        string $with,
        mixed $willReturn
    ): GuzzleHttpClient {
        $client = \Mockery::mock(GuzzleHttpClient::class);
        $client->shouldReceive($method)
            ->once()
            ->with($with)
            ->andReturn($willReturn);

        return $client;
    }

    public function createRssFeedXml(?string $channelTitle = null, bool $isDubbed = false): SimpleXMLElement
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
}
