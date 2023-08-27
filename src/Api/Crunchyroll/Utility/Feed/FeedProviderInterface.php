<?php

declare(strict_types=1);

namespace SamihSoylu\Crunchyroll\Api\Crunchyroll\Utility\Feed;

use SimpleXMLElement;

interface FeedProviderInterface
{
    public function getFeed(): SimpleXMLElement;
}
