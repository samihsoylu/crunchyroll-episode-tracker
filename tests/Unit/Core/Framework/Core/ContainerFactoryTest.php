<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use SamihSoylu\Crunchyroll\Core\Framework\AppEnv;
use SamihSoylu\Crunchyroll\Core\Framework\Core\ContainerFactory;
use SamihSoylu\Crunchyroll\Tests\TestDouble\Dummy\DummyObjectInterface;
use SamihSoylu\Crunchyroll\Tests\TestDouble\Dummy\DummyProdObject;
use SamihSoylu\Crunchyroll\Tests\TestDouble\Dummy\DummyTestObject;

it('should create a container', function() {
    $configDir = $this->kernel->rootDir . '/tests/TestDouble/Fake/Core/Framework/Container/config';

    $containerFactory = new ContainerFactory($configDir, AppEnv::TEST);
    $container = $containerFactory->create();

    expect($container)->toBeInstanceOf(ContainerInterface::class);
});

it('should load container configurations based on the app environment', function($environment, $expectedInstance) {
    $configDir = $this->kernel->rootDir . '/tests/TestDouble/Fake/Core/Framework/Container/config';

    $containerFactory = new ContainerFactory($configDir, $environment);
    $container = $containerFactory->create();

    $dummyObject = $container->get(DummyObjectInterface::class);
    expect($dummyObject)->toBeInstanceOf($expectedInstance);
})->with([
    [AppEnv::TEST, DummyTestObject::class],
    [AppEnv::PROD, DummyProdObject::class]
]);

it('should throw exception when config dir does not exist', function () {

    $containerFactory = new ContainerFactory($this->kernel->rootDir, AppEnv::TEST);
    $container = $containerFactory->create();
})->throws(UnexpectedValueException::class);