<?php

declare(strict_types=1);

namespace SamihSoylu\Crunchyroll\Core\Framework\Core;

use DI\Container;
use LogicException;
use SamihSoylu\Crunchyroll\Core\Framework\AppEnv;

final readonly class ContainerFactory
{
    public function __construct(
        private string $configDir,
        private AppEnv $environment,
    ) {}

    public function create(): Container
    {
        $container = new Container();

        $this->configureContainer($container);

        return $container;
    }

    private function configureContainer(Container $container): void
    {
        $environments = array_unique([
            AppEnv::PROD->value,
            $this->environment->value
        ]);

        foreach ($environments as $environment) {
            $files = glob("{$this->configDir}/services/{$environment}/*.php");
            foreach ($files as $file) {
                $configurator = require $file;
                if (!is_callable($configurator)) {
                    throw new LogicException("Expected '{$file}' to return a callable configurator.");
                }

                $configurator($container);
            }
        }
    }
}