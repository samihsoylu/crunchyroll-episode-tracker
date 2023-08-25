<?php

declare(strict_types=1);

use DI\Container;
use SamihSoylu\Crunchyroll\Api\Crunchyroll\Repository\AnimeEpisodeRepository;
use SamihSoylu\Crunchyroll\Api\Crunchyroll\Repository\AnimeEpisodeRepositoryInterface;

return function (Container $container) {
    $container->set(AnimeEpisodeRepositoryInterface::class, function (Container $container) {
        return $container->get(AnimeEpisodeRepository::class);
    });
};