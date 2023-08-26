<?php

declare(strict_types=1);

namespace SamihSoylu\Crunchyroll\Api\Crunchyroll\Repository;

use LogicException;
use SamihSoylu\Crunchyroll\Api\Crunchyroll\Entity\AnimeEpisode;
use SamihSoylu\Crunchyroll\Api\Crunchyroll\Utility\CrunchyrollParser\CrunchyrollRssFeedRssParser;
use SamihSoylu\Crunchyroll\Api\Crunchyroll\Utility\CrunchyrollParser\CrunchyrollRssParserInterface;
use SimpleXMLElement;

final class AnimeEpisodeRepository implements AnimeEpisodeRepositoryInterface
{
    public function __construct(
        private readonly CrunchyrollRssParserInterface $feedParser,
    ) {}

    /**
     * @return AnimeEpisode[]
     */
    public function getLatestEpisodes(): array
    {
        $episodes = [];
        foreach ($this->feedParser->getChannels() as $channel) {
            /** @var SimpleXMLElement $channel */
            if ((string) $channel->title !== 'Latest Crunchyroll Anime Videos') {
                throw new LogicException('Feed structure has changed');
            }

            foreach ($this->feedParser->getItems($channel) as $item) {
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
}