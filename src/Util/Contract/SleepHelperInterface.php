<?php

declare(strict_types=1);

namespace SamihSoylu\CrunchyrollSyncer\Util\Contract;

interface SleepHelperInterface
{
    public function sleep(int $seconds): void;
}
