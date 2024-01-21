<?php

declare(strict_types=1);

namespace SamihSoylu\CrunchyrollSyncer\Tests\Framework\TestDouble\Spy;

use PHPUnit\Framework\Assert;
use SamihSoylu\CrunchyrollSyncer\Util\Contract\SleepHelperInterface;

final class SpySleepHelper extends Assert implements SleepHelperInterface
{
    private bool $invoked = false;

    public function sleep(int $seconds): void
    {
        $this->invoked = true;
    }

    public function assertInvoked(): void
    {
        self::assertTrue($this->invoked, 'Sleep was not invoked');
    }
}
