<?php

declare(strict_types=1);

namespace SamihSoylu\Crunchyroll\Tests\Framework\TestDouble\Fake;

use SamihSoylu\Crunchyroll\Api\Crunchyroll\Utility\Feed\FeedProviderInterface;
use SimpleXMLElement;

final class FakeRssFeedProvider implements FeedProviderInterface
{
    public function __construct(
        private string $xmlFeed,
    ) {}

    public function getFeed(): SimpleXMLElement
    {
        return simplexml_load_string($this->xmlFeed);
    }
}