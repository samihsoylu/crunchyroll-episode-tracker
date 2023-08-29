<?php

declare(strict_types=1);

namespace SamihSoylu\Crunchyroll\Cronjob;

interface CronjobInterface
{
    public function __invoke(mixed ...$args): void;
}
