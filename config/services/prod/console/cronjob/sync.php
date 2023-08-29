<?php

declare(strict_types=1);

use DI\Container;
use SamihSoylu\Crunchyroll\Console\Cronjob\SyncCommand;
use SamihSoylu\Crunchyroll\Cronjob\CrunchyrollToNotionSync;

return function (Container $container) {
    $container->set(SyncCommand::class, function (Container $container) {
        return new SyncCommand(
            $container->get(CrunchyrollToNotionSync::class),
            $_ENV['NOTION_DATABASE_ID'],
        );
    });
};
