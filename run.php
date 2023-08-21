<?php

declare(strict_types=1);

use Samihsoylu\Crunchyroll\AnimeEpisode;
use Samihsoylu\Crunchyroll\RssFeedParser;

require_once(__DIR__ . '/vendor/autoload.php');

$rssFeed = file_get_contents('https://www.crunchyroll.com/rss/anime');
if (!$rssFeed) {
    throw new \RuntimeException('Could not retrieve RSS feed');
}

$feed = new RssFeedParser($rssFeed);
$episodes = [];
foreach ($feed->getChannels() as $channel) {
    /** @var SimpleXMLElement $channel */
    if ((string) $channel->title !== 'Latest Crunchyroll Anime Videos') {
        throw new LogicException('More than one channel detected');
    }

    foreach ($feed->getItems($channel) as $item) {
        /** @var SimpleXMLElement $item */
        $episode = AnimeEpisode::fromFeed($item);

        if (str_contains($episode->getTitle(), 'Dub)')) {
            // Ignore new dubbed episodes
            continue;
        }

        $episodes[] = $episode;
    }
}
