<?php

declare(strict_types=1);

namespace SamihSoylu\CrunchyrollSyncer\Service\Contract;

interface CrunchyrollToNotionSyncServiceInterface
{
    public function sync(): void;
}
