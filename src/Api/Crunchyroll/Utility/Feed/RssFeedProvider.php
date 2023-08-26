<?php

declare(strict_types=1);

namespace SamihSoylu\Crunchyroll\Api\Crunchyroll\Utility\Feed;

use GuzzleHttp\Client;
use SimpleXMLElement;

final readonly class RssFeedProvider implements FeedProviderInterface
{
    public function __construct(
        private string $rssFeedUrl,
    ) {}

    public function getFeed(): SimpleXMLElement
    {
        $rssFeed = file_get_contents($this->rssFeedUrl);

        if ($rssFeed === false) {
            throw new \RuntimeException('Could not retrieve RSS feed');
        }

        return simplexml_load_string($rssFeed);
    }
}