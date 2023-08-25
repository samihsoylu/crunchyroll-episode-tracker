<?php

declare(strict_types=1);

namespace SamihSoylu\Crunchyroll\Tests\Framework;

use PHPUnit\Framework\TestCase;
use SamihSoylu\Crunchyroll\Core\Framework\Core\Kernel;

abstract class BaseTestCase extends TestCase
{
    protected Kernel $kernel;

    protected function setUp(): void
    {
        $this->kernel = Kernel::boot();
    }
}