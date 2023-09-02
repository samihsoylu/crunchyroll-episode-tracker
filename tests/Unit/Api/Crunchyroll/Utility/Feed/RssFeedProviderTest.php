<?php

declare(strict_types=1);

use GuzzleHttp\Psr7\Response;
use SamihSoylu\CrunchyrollSyncer\Api\Crunchyroll\Utility\Feed\RssFeedProvider;

it('should return simple xml element when http status code is 200', function () {
    $feedUrl = 'feed.com';
    $xmlContent = '<?xml version="1.0" encoding="UTF-8"?><rss></rss>';
    $mockHttp = testKit()->crunchyroll()->createMockHttpClient(
        method: 'get',
        with: $feedUrl,
        willReturn: new Response(200, [], $xmlContent)
    );

    $feedProvider = new RssFeedProvider($feedUrl, $mockHttp);
    $result = $feedProvider->getFeed();

    expect($result)->toBeInstanceOf(SimpleXMLElement::class);
});

it('should throw runtime exception when http status code is not 200', function () {
    $feedUrl = 'feed.com';
    $mockHttp = testKit()->crunchyroll()->createMockHttpClient(
        method: 'get',
        with: $feedUrl,
        willReturn: new Response(404)
    );

    $feedProvider = new RssFeedProvider($feedUrl, $mockHttp);
    $feedProvider->getFeed();
})->throws(RuntimeException::class, 'Could not retrieve RSS feed');

it('should throw runtime exception when retrieved xml is corrupt', function () {
    $feedUrl = 'feed.com';
    $mockHttp = testKit()->crunchyroll()->createMockHttpClient(
        method: 'get',
        with: $feedUrl,
        willReturn: new Response(200, [], null)
    );

    $feedProvider = new RssFeedProvider($feedUrl, $mockHttp);
    $feedProvider->getFeed();
})->throws(RuntimeException::class, 'Could not parse feed data, feed data is corrupt');
