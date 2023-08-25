<?php

declare(strict_types=1);

namespace Samihsoylu\Crunchyroll\Core\Framework\Core;

use Psr\Container\ContainerInterface;
use Samihsoylu\Crunchyroll\Core\Framework\AppEnv;

final readonly class Kernel
{
    public ContainerInterface $container;
    public AppEnv $environment;

    private function __construct() {}

    public static function boot(): self
    {
        $kernel = new self();
        $kernel->assertEnvironmentVariablesAreSet();

        $kernel->environment = AppEnv::from($_ENV['APP_ENV']);

        $kernel->initializeContainer();

        return $kernel;
    }

    private function initializeContainer(): void
    {
        $factory = new ContainerFactory(
            $_ENV['APP_CONFIG_DIR'],
            $this->environment,
        );

        $this->container = $factory->create();
    }

    private function assertEnvironmentVariablesAreSet(): void
    {
        $requiredFields = ['APP_ENV', 'APP_CONFIG_DIR'];

        foreach ($requiredFields as $field) {
            if (!array_key_exists($field, $_ENV)) {
                throw new \RuntimeException("Environment variable \$_ENV['{$field}'] not found");
            }
        }
    }
}