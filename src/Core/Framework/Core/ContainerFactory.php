<?php

declare(strict_types=1);

namespace SamihSoylu\Crunchyroll\Core\Framework\Core;

use DI\Container;
use LogicException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;
use SamihSoylu\Crunchyroll\Core\Framework\AppEnv;
use UnexpectedValueException;

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
            $files = $this->getFiles($environment);
            foreach ($files as $file) {
                $configurator = require $file;
                if (!is_callable($configurator)) {
                    throw new LogicException("Expected '{$file}' to return a callable configurator.");
                }

                $configurator($container);
            }
        }
    }

    /**
     * @return array<int, string>
     */
    private function getFiles(string $environment): array
    {
        $directoryPath = "{$this->configDir}/services/{$environment}/";
        try {
            $directory = new RecursiveDirectoryIterator($directoryPath);
        } catch (UnexpectedValueException $exception) {
            throw new UnexpectedValueException(
                "Unable to open '{$directoryPath}'",
                $exception->getCode(), $exception
            );
        }

        $iterator = new RecursiveIteratorIterator($directory);
        $files = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

        return array_values(array_map(function (array $file) {
            return $file[0];
        }, iterator_to_array($files)));
    }
}