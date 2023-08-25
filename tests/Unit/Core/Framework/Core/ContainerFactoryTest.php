<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use SamihSoylu\Crunchyroll\Core\Framework\AppEnv;
use SamihSoylu\Crunchyroll\Core\Framework\Core\ContainerFactory;
use SamihSoylu\Crunchyroll\Tests\TestDouble\Dummy\DummyObjectInterface;
use SamihSoylu\Crunchyroll\Tests\TestDouble\Dummy\DummyProdObject;
use SamihSoylu\Crunchyroll\Tests\TestDouble\Dummy\DummyTestObject;

it('it should create a container', function() {
    $configDir = __DIR__ . '/config';

    $containerFactory = new ContainerFactory($configDir, AppEnv::DEV);
    $container = $containerFactory->create();

    expect($container)->toBeInstanceOf(ContainerInterface::class);
});

it('should load container configurations based on the app environment', function($environment, $expectedInstance) {
    $configDir = $_ENV['ROOT_DIR'] . '/tests/TestDouble/Fake/Core/Framework/Container/config';

    $containerFactory = new ContainerFactory($configDir, $environment);
    $container = $containerFactory->create();

    $dummyObject = $container->get(DummyObjectInterface::class);
    expect($dummyObject)->toBeInstanceOf($expectedInstance);
})->with([
    [AppEnv::TEST, DummyTestObject::class],
    [AppEnv::PROD, DummyProdObject::class]
]);