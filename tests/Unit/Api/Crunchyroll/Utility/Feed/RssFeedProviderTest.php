<?php

declare(strict_types=1);

use SamihSoylu\Crunchyroll\Api\Crunchyroll\Utility\Feed\RssFeedProvider;

it('should return SimpleXMLElement on successful getFeed', function () {
    $this->mockHttpStream('<rss><channel><title>Sample Feed</title></channel></rss>');

    $rssFeedProvider = new RssFeedProvider('http://example.com/rss');
    $result = $rssFeedProvider->getFeed();

    expect($result)->toBeInstanceOf(SimpleXMLElement::class)
        ->and((string)$result->channel->title)->toBe('Sample Feed');
});

it('should throw RuntimeException when getFeed fails', function () {
    $this->mockHttpStream(false);

    $rssFeedProvider = new RssFeedProvider('http://example.com/rss');

    $rssFeedProvider->getFeed();
})->throws(RuntimeException::class, 'Could not retrieve RSS feed');

afterEach(function () {
   $this->restoreHttpStream();
});