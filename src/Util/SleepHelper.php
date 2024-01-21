<?php

declare(strict_types=1);

namespace SamihSoylu\CrunchyrollSyncer\Util;

use SamihSoylu\CrunchyrollSyncer\Util\Contract\SleepHelperInterface;

final class SleepHelper implements SleepHelperInterface
{
    public function sleep(int $seconds): void
    {
        sleep($seconds);
    }
}
