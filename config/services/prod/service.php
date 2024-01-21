<?php

declare(strict_types=1);

use DI\Container;
use Psr\Log\LoggerInterface;
use SamihSoylu\CrunchyrollSyncer\Api\Crunchyroll\CrunchyrollApiClient;
use SamihSoylu\CrunchyrollSyncer\Api\Notion\NotionApiClient;
use SamihSoylu\CrunchyrollSyncer\Service\Contract\CrunchyrollToNotionSyncServiceInterface;
use SamihSoylu\CrunchyrollSyncer\Service\CrunchyrollToNotionSyncService;

return function (Container $container) {
    $container->set(CrunchyrollToNotionSyncServiceInterface::class, function (Container $container) {
        return new CrunchyrollToNotionSyncService(
            $container->get(CrunchyrollApiClient::class),
            $container->get(NotionApiClient::class),
            $container->get(LoggerInterface::class),
            $_ENV['NOTION_DATABASE_ID'],
        );
    });
};
