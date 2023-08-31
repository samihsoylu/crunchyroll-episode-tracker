<?php

declare(strict_types=1);

namespace SamihSoylu\Crunchyroll\Action;

interface ActionInterface
{
    public function __invoke(mixed ...$args): void;
}
