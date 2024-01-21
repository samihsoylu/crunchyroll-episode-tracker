<?php

declare(strict_types=1);

use SamihSoylu\CrunchyrollSyncer\Console\Action\CrunchyrollToNotion\SyncCommand;
use SamihSoylu\CrunchyrollSyncer\Tests\Framework\TestDouble\Spy\SpyCrunchyrollToNotionSyncService;

it('should invoke crunchyroll to notion sync', function () {
    $spy = new SpyCrunchyrollToNotionSyncService();

    testKit()->executeConsoleCommand(
        new SyncCommand($spy)
    );

    $spy->assertSyncInvoked();
});
