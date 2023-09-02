<?php

declare(strict_types=1);

use SamihSoylu\CrunchyrollSyncer\Console\Action\CrunchyrollToNotion\SyncCommand;
use SamihSoylu\CrunchyrollSyncer\Tests\Framework\TestDouble\Spy\SpyAction;

it('should invoke crunchyroll to notion sync', function () {
    $expectedToken = 'some-id';
    $spy = new SpyAction();

    testKit()->executeConsoleCommand(
        new SyncCommand($spy, $expectedToken)
    );

    $spy->assertInvokedWith($expectedToken);
})->expectNotToPerformAssertions();
