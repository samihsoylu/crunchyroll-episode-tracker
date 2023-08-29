<?php

declare(strict_types=1);

namespace SamihSoylu\Crunchyroll\Tests\Framework\TestDouble\Spy;

use PHPUnit\Framework\TestCase;
use SamihSoylu\Crunchyroll\Cronjob\CronjobInterface;

final class SpyCronjob implements CronjobInterface
{
    /** @var array */
    private array $actualArguments;

    public function __invoke(mixed ...$args): void
    {
        $this->actualArguments = $args;
    }

    public function getActualArguments(): array
    {
        return $this->actualArguments;
    }
}