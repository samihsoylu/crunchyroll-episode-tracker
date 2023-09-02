<?php

declare(strict_types=1);

namespace SamihSoylu\CrunchyrollSyncer\Api\Crunchyroll\Utility\Feed;

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

        $xml = simplexml_load_string($response->getBody()->getContents());
        if ($xml === false) {
            throw new \RuntimeException('Could not parse feed data, feed data is corrupt');
        }

        return $xml;
    }
}
