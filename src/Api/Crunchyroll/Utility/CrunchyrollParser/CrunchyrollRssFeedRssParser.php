<?php

namespace SamihSoylu\Crunchyroll\Api\Crunchyroll\Utility\CrunchyrollParser;

use SamihSoylu\Crunchyroll\Api\Crunchyroll\Utility\Feed\FeedProviderInterface;
use SimpleXMLElement;

final class CrunchyrollRssFeedRssParser implements CrunchyrollRssParserInterface
{
    private SimpleXMLElement $xmlObject;

    public function __construct(FeedProviderInterface $feedProvider)
    {
        $this->xmlObject = $feedProvider->getFeed();
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