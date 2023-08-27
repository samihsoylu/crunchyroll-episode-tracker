<?php

declare(strict_types=1);

use DI\Container;
use GuzzleHttp\Client;
use SamihSoylu\Crunchyroll\Api\Crunchyroll\Repository\AnimeEpisodeRepository;
use SamihSoylu\Crunchyroll\Api\Crunchyroll\Repository\AnimeEpisodeRepositoryInterface;
use SamihSoylu\Crunchyroll\Api\Crunchyroll\Utility\CrunchyrollParser\CrunchyrollRssFeedRssParser;
use SamihSoylu\Crunchyroll\Api\Crunchyroll\Utility\CrunchyrollParser\CrunchyrollRssParserInterface;
use SamihSoylu\Crunchyroll\Api\Crunchyroll\Utility\Feed\FeedProviderInterface;
use SamihSoylu\Crunchyroll\Api\Crunchyroll\Utility\Feed\RssFeedProvider;

return function (Container $container) {
    $container->set(AnimeEpisodeRepositoryInterface::class, function (Container $container) {
        return $container->get(AnimeEpisodeRepository::class);
    });

    $container->set(FeedProviderInterface::class, function (Container $container) {
        return new RssFeedProvider(
            $_ENV['CRUNCHYROLL_RSS_FEED_URL'],
            $container->get(Client::class),
        );
    });

    $container->set(CrunchyrollRssParserInterface::class, function (Container $container) {
        return $container->get(CrunchyrollRssFeedRssParser::class);
    });
};
