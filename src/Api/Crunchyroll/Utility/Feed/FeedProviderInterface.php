<?php

declare(strict_types=1);

namespace SamihSoylu\CrunchyrollSyncer\Api\Crunchyroll\Utility\Feed;

use SimpleXMLElement;

interface FeedProviderInterface
{
    public function getFeed(): SimpleXMLElement;
}
