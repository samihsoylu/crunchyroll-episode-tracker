<?php

declare(strict_types=1);

use SamihSoylu\Crunchyroll\Api\Crunchyroll\Utility\CrunchyrollParser\CrunchyrollRssFeedRssParser;
use SamihSoylu\Crunchyroll\Tests\Framework\TestDouble\Fake\FakeRssFeedProvider;

it('should successfully parse rss feed', function () {
    $fakeFeed = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rss>
    <channel>
        <title>Latest Crunchyroll Anime Videos</title>
        <item>
            <title>Naruto Episode 1</title>
        </item>
        <item>
            <title>Naruto Episode 2</title>
        </item>
    </channel>
</rss>
XML;

    $parser = new CrunchyrollRssFeedRssParser(new FakeRssFeedProvider($fakeFeed));

    $channels = $parser->getChannels();
    expect((string) $channels->title)->toBe('Latest Crunchyroll Anime Videos');

    /** @var SimpleXMLElement $items */
    $items = $parser->getItems($channels);
    expect($items->count())->toBe(2)
        ->and((string)$items[0]->title)->toBe('Naruto Episode 1')
        ->and((string)$items[1]->title)->toBe('Naruto Episode 2');
});
