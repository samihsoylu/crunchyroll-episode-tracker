<?php

declare(strict_types=1);

use DI\Container;
use SamihSoylu\CrunchyrollSyncer\Action\CrunchyrollToNotionSyncAction;
use SamihSoylu\CrunchyrollSyncer\Console\Action\CrunchyrollToNotion\SyncCommand;

return function (Container $container) {
    $container->set(SyncCommand::class, function (Container $container) {
        return new SyncCommand(
            $container->get(CrunchyrollToNotionSyncAction::class),
            $_ENV['NOTION_DATABASE_ID'],
        );
    });
};
