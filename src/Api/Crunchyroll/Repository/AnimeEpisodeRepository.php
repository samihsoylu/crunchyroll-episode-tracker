<?php

declare(strict_types=1);

namespace SamihSoylu\Crunchyroll\Api\Crunchyroll\Repository;

use LogicException;
use SamihSoylu\Crunchyroll\Api\Crunchyroll\Entity\AnimeEpisode;
use SamihSoylu\Crunchyroll\Api\Crunchyroll\Utility\CrunchyrollRssFeedParser;
use SimpleXMLElement;

final class AnimeEpisodeRepository implements AnimeEpisodeRepositoryInterface
{
    private const RSS_FEED_URL = 'https://www.crunchyroll.com/rss/anime';

    /**
     * @return AnimeEpisode[]
     */
    public function getLatestEpisodes(): array
    {
        $feed = new CrunchyrollRssFeedParser($this->getRssFeed());

        $episodes = [];
        foreach ($feed->getChannels() as $channel) {
            /** @var SimpleXMLElement $channel */
            if ((string) $channel->title !== 'Latest Crunchyroll Anime Videos') {
                throw new LogicException('Feed structure has changed');
            }

            foreach ($feed->getItems($channel) as $item) {
                /** @var SimpleXMLElement $item */
                $episode = AnimeEpisode::fromSimpleXmlElement($item);

                if (str_contains($episode->getTitle(), 'Dub)')) {
                    // Ignore new dubbed episodes
                    continue;
                }

                $episodes[$episode->getSeriesTitle()] = $episode;
            }
        }

        return $episodes;
    }

    private function getRssFeed(): string
    {
        $rssFeed = file_get_contents(self::RSS_FEED_URL);
        if ($rssFeed === false) {
            throw new \RuntimeException('Could not retrieve RSS feed');
        }

        return $rssFeed;
    }
}