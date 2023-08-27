<?php

declare(strict_types=1);

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Psr7\Response;
use SamihSoylu\Crunchyroll\Api\Crunchyroll\Utility\Feed\RssFeedProvider;

beforeEach(function () {
    $this->httpClient = $this->createMock(GuzzleHttpClient::class);
    $this->rssFeedUrl = 'https://some-rss-feed-url.com';
    $this->rssFeedProvider = new RssFeedProvider($this->rssFeedUrl, $this->httpClient);
});

it('should return SimpleXMLElement when HTTP status code is 200', function () {
    $xmlContent = '<?xml version="1.0" encoding="UTF-8"?><rss></rss>';
    $this->httpClient->expects($this->once())
        ->method('get')
        ->with($this->rssFeedUrl)
        ->willReturn(new Response(200, [], $xmlContent));

    $result = $this->rssFeedProvider->getFeed();

    expect($result)->toBeInstanceOf(SimpleXMLElement::class);
});

it('should throw RuntimeException when HTTP status code is not 200', function () {
    $this->httpClient->expects($this->once())
        ->method('get')
        ->with($this->rssFeedUrl)
        ->willReturn(new Response(404));

    $this->rssFeedProvider->getFeed();
})->throws(RuntimeException::class, 'Could not retrieve RSS feed');
