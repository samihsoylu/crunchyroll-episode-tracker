<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Samihsoylu\Crunchyroll\Core\Framework\AppEnv;
use Samihsoylu\Crunchyroll\Core\Framework\Container\ContainerFactory;
use Samihsoylu\Crunchyroll\Tests\TestDouble\Dummy\DummyObjectInterface;
use Samihsoylu\Crunchyroll\Tests\TestDouble\Dummy\DummyProdObject;
use Samihsoylu\Crunchyroll\Tests\TestDouble\Dummy\DummyTestObject;

it('should initialize without error for valid environment', function() {
    $configDir = __DIR__ . '/config';

    $containerFactory = new ContainerFactory($configDir, AppEnv::DEV);

    expect($containerFactory)->toBeInstanceOf(ContainerFactory::class);
});


it('it should create a container that implements container interface', function() {
    $configDir = __DIR__ . '/config';

    $containerFactory = new ContainerFactory($configDir, AppEnv::DEV);
    $container = $containerFactory->create();

    expect($container)->toBeInstanceOf(ContainerInterface::class);
});

it('should load configurations based on the environment', function($environment, $expectedInstance) {
    $configDir = $_ENV['ROOT_DIR'] . '/tests/TestDouble/Fake/Core/Framework/Container/config';

    $containerFactory = new ContainerFactory($configDir, $environment);
    $container = $containerFactory->create();

    $dummyObject = $container->get(DummyObjectInterface::class);
    expect($dummyObject)->toBeInstanceOf($expectedInstance);
})->with([
    [AppEnv::TEST, DummyTestObject::class],
    [AppEnv::PROD, DummyProdObject::class]
]);
