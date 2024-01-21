<?php

declare(strict_types=1);

namespace SamihSoylu\CrunchyrollSyncer\Tests\Framework\TestDouble\Dummy;

use SamihSoylu\CrunchyrollSyncer\Util\Contract\SleepHelperInterface;

final class DummySleepHelper implements SleepHelperInterface
{
    public function sleep(int $seconds): void
    {
    }
}
