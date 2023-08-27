<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use SamihSoylu\Crunchyroll\Core\Framework\AppEnv;
use SamihSoylu\Crunchyroll\Core\Framework\Core\ContainerFactory;
use SamihSoylu\Crunchyroll\Tests\Framework\TestDouble\Dummy\DummyObjectInterface;
use SamihSoylu\Crunchyroll\Tests\Framework\TestDouble\Dummy\DummyProdObject;
use SamihSoylu\Crunchyroll\Tests\Framework\TestDouble\Dummy\DummyTestObject;

it('should create a container', function() {
    $configDir = $this->getProjectRootDir() . '/tests/Framework/TestDouble/Fake/Core/Framework/Container/config';

    $containerFactory = new ContainerFactory($configDir, AppEnv::TEST);
    $container = $containerFactory->create();

    expect($container)->toBeInstanceOf(ContainerInterface::class);
});

it('should load container configurations based on the app environment', function($environment, $expectedInstance) {
    $configDir = $this->getProjectRootDir() . '/tests/Framework/TestDouble/Fake/Core/Framework/Container/config';

    $containerFactory = new ContainerFactory($configDir, $environment);
    $container = $containerFactory->create();

    $dummyObject = $container->get(DummyObjectInterface::class);
    expect($dummyObject)->toBeInstanceOf($expectedInstance);
})->with([
    [AppEnv::TEST, DummyTestObject::class],
    [AppEnv::PROD, DummyProdObject::class]
]);

it('should throw exception when config dir does not exist', function () {
    $containerFactory = new ContainerFactory($this->getProjectRootDir(), AppEnv::TEST);
    $container = $containerFactory->create();
})->throws(UnexpectedValueException::class);

it('should throw exception when configurator is not callable', function () {
    $configDir = $this->getProjectRootDir() . '/tests/Framework/TestDouble/Fake/Core/Framework/Container/invalidConfig';

    $containerFactory = new ContainerFactory($configDir, AppEnv::TEST);
    $containerFactory->create();
})->throws(LogicException::class);
