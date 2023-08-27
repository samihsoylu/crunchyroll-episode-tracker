<?php

declare(strict_types=1);

namespace SamihSoylu\Crunchyroll\Api\Crunchyroll\Utility\Feed;

use GuzzleHttp\Client as GuzzleHttpClient;
use SimpleXMLElement;

final readonly class RssFeedProvider implements FeedProviderInterface
{
    public function __construct(
        private string $rssFeedUrl,
        private GuzzleHttpClient $httpClient,
    ) {
    }

    public function getFeed(): SimpleXMLElement
    {
        $response = $this->httpClient->get($this->rssFeedUrl);

        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException('Could not retrieve RSS feed');
        }

        return simplexml_load_string($response->getBody()->getContents());
    }
}
