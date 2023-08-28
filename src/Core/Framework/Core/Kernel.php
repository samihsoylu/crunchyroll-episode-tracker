<?php

declare(strict_types=1);

namespace SamihSoylu\Crunchyroll\Core\Framework\Core;

use Psr\Container\ContainerInterface;
use SamihSoylu\Crunchyroll\Core\Framework\AppEnv;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final readonly class Kernel
{
    public ContainerInterface $container;
    public AppEnv $environment;
    public string $rootDir;

    private function __construct()
    {
        $this->assertEnvironmentVariablesAreSet();

        $this->environment = AppEnv::from($_ENV['APP_ENV']);
        $this->rootDir = $_ENV['ROOT_DIR'];
        $this->container = $this->getContainerFactory()->create();
    }

    public static function boot(): self
    {
        return new self();
    }

    private function getContainerFactory(): ContainerFactory
    {
        return new ContainerFactory(
            $_ENV['APP_CONFIG_DIR'],
            $this->environment,
        );
    }

    private function assertEnvironmentVariablesAreSet(): void
    {
        $requiredFields = ['APP_ENV', 'APP_CONFIG_DIR', 'ROOT_DIR'];

        foreach ($requiredFields as $field) {
            if (!array_key_exists($field, $_ENV)) {
                throw new \RuntimeException("Environment variable \$_ENV['{$field}'] not found");
            }
        }
    }
}
