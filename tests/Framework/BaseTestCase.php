<?php

declare(strict_types=1);

namespace SamihSoylu\Crunchyroll\Tests\Framework;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;
use SamihSoylu\Crunchyroll\Core\Framework\AppEnv;
use SamihSoylu\Crunchyroll\Core\Framework\Core\Kernel;

abstract class BaseTestCase extends TestCase
{
    private Kernel $kernel;

    protected function setUp(): void
    {
        $this->kernel = Kernel::boot();
    }

    /**
     * @template T
     *
     * @param class-string<T> $serviceId
     * @return T
     */
    public function getService(string $serviceId): object
    {
        return $this->kernel->container->get($serviceId);
    }

    public function getProjectRootDir(): string
    {
        return $this->kernel->rootDir;
    }
}