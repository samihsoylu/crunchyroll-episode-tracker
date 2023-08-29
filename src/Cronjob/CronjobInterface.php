<?php

namespace SamihSoylu\Crunchyroll\Cronjob;

interface CronjobInterface
{
    public function __invoke(mixed ...$args): void;
}