<?php

declare(strict_types=1);

namespace SamihSoylu\Crunchyroll\Tests\Framework\TestDouble\Fake;

use SamihSoylu\Crunchyroll\Api\Crunchyroll\Utility\CrunchyrollParser\CrunchyrollRssParserInterface;
use SimpleXMLElement;

final readonly class FakeCrunchyrollRssParser implements CrunchyrollRssParserInterface
{
    public function __construct(
        private SimpleXMLElement $xmlObject,
    ) {
    }

    public function getChannels(): SimpleXMLElement
    {
        return $this->xmlObject->channel;
    }

    public function getItems(SimpleXMLElement $channel): SimpleXMLElement
    {
        return $channel->item;
    }
}
