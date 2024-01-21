<?php

declare(strict_types=1);

use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use SamihSoylu\CrunchyrollSyncer\Api\Crunchyroll\Utility\Feed\RssFeedProvider;
use SamihSoylu\CrunchyrollSyncer\Tests\Framework\TestDouble\Dummy\DummySleepHelper;
use SamihSoylu\CrunchyrollSyncer\Tests\Framework\TestDouble\Spy\SpySleepHelper;

it('should return simple xml element when http status code is 200', function () {
    $feedUrl = 'feed.com';
    $xmlContent = '<?xml version="1.0" encoding="UTF-8"?><rss></rss>';
    $mockHttp = testKit()->crunchyroll()->createMockHttpClient(
        method: 'get',
        with: $feedUrl,
        willReturn: new Response(200, [], $xmlContent)
    );

    $feedProvider = new RssFeedProvider($feedUrl, $mockHttp, new DummySleepHelper());
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

    $feedProvider = new RssFeedProvider($feedUrl, $mockHttp, new DummySleepHelper());
    $feedProvider->getFeed();
})->throws(RuntimeException::class, 'Could not retrieve RSS feed');

it('should throw runtime exception when retrieved xml is corrupt', function () {
    $feedUrl = 'feed.com';
    $mockHttp = testKit()->crunchyroll()->createMockHttpClient(
        method: 'get',
        with: $feedUrl,
        willReturn: new Response(200, [], null)
    );

    $feedProvider = new RssFeedProvider($feedUrl, $mockHttp, new DummySleepHelper());
    $feedProvider->getFeed();
})->throws(RuntimeException::class, 'Could not parse feed data, feed data is corrupt');

it('retries on server exception and eventually succeeds', function () {
    $spySleeper = new SpySleepHelper();

    $feedUrl = 'feed.com';
    $xmlContent = '<?xml version="1.0" encoding="UTF-8"?><rss></rss>';

    // First two attempts will throw a ServerException (simulating a 502 Bad Gateway)
    // The third attempt will return a successful response
    $mockHttp = testKit()->crunchyroll()->createMockHttpClient(
        method: 'get',
        with: $feedUrl,
        willReturn: new Response(200, [], $xmlContent),
        willThrowOnConsecutiveCalls: [
            new ServerException('Server error', $this->createMock(RequestInterface::class), new Response(502)),
            new ServerException('Server error', $this->createMock(RequestInterface::class), new Response(502)),
        ],
    );

    $feedProvider = new RssFeedProvider($feedUrl, $mockHttp, $spySleeper);
    $result = $feedProvider->getFeed();

    $spySleeper->assertInvoked();
    expect($result)->toBeInstanceOf(SimpleXMLElement::class);
});

it('throws exception after maximum retries exceeded', function () {
    $feedUrl = 'feed.com';

    // Simulate 502 Bad Gateway for all three attempts
    $mockHttp = testKit()->crunchyroll()->createMockHttpClient(
        method: 'get',
        with: $feedUrl,
        willThrowOnConsecutiveCalls: [
            new ServerException('Server error', $this->createMock(RequestInterface::class), new Response(502)),
            new ServerException('Server error', $this->createMock(RequestInterface::class), new Response(502)),
            new ServerException('Server error', $this->createMock(RequestInterface::class), new Response(502)),
        ],
    );

    $feedProvider = new RssFeedProvider($feedUrl, $mockHttp, new DummySleepHelper());

    $feedProvider->getFeed();
})->throws(ServerException::class);
