<?php

declare(strict_types=1);

namespace SamihSoylu\CrunchyrollSyncer\Api\Crunchyroll\Utility\CrunchyrollParser;

use SimpleXMLElement;

interface CrunchyrollRssParserInterface
{
    public function getChannels(): SimpleXMLElement;

    public function getItems(SimpleXMLElement $channel): SimpleXMLElement;
}
