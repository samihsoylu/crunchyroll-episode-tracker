<?php

namespace Samihsoylu\Crunchyroll;

use SimpleXMLElement;

final class RssFeedParser
{
    private SimpleXMLElement $xmlObject;

    public function __construct(string $rssFeed)
    {
        $this->xmlObject = simplexml_load_string($rssFeed);
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