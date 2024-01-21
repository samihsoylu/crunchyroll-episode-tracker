<?php

declare(strict_types=1);

namespace SamihSoylu\CrunchyrollSyncer\Tests\Framework\TestDouble\Spy;

use PHPUnit\Framework\Assert;
use SamihSoylu\CrunchyrollSyncer\Service\Contract\CrunchyrollToNotionSyncServiceInterface;

final class SpyCrunchyrollToNotionSyncService extends Assert implements CrunchyrollToNotionSyncServiceInterface
{
    private bool $isInvoked = false;

    public function sync(): void
    {
        $this->isInvoked = true;
    }

    public function assertSyncInvoked(): void
    {
        self::assertTrue($this->isInvoked, 'Expected sync() method was not invoked');
    }
}
