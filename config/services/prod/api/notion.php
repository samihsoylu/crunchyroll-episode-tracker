<?php

declare(strict_types=1);

use DI\Container;
use Notion\Notion;
use SamihSoylu\Crunchyroll\Api\Notion\Repository\SeriesRepository;
use SamihSoylu\Crunchyroll\Api\Notion\Repository\SeriesRepositoryInterface;

return function (Container $container) {
    $container->set(Notion::class, function () {
        return Notion::create($_ENV['NOTION_TOKEN']);
    });

    $container->set(SeriesRepositoryInterface::class, function (Container $container) {
        return $container->get(SeriesRepository::class);
    });
};