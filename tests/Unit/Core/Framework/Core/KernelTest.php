<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use SamihSoylu\Crunchyroll\Core\Framework\AppEnv;
use SamihSoylu\Crunchyroll\Core\Framework\Core\Kernel;

it('should boot up kernel', function () {
    $kernel = Kernel::boot();

    expect($kernel->environment)->toBe(AppEnv::from($_ENV['APP_ENV']));
});

it('should initialize container', function () {
    $_ENV['CONFIG_DIR'] = $_ENV['ROOT_DIR'] . '/tests/Framework/TestDouble/Fake/Infrastructure/Framework/Core';

    $kernel = Kernel::boot();

    expect($kernel->container)->toBeInstanceOf(ContainerInterface::class);
});

it('should throw when environment variable is nto set', function () {
    $this->env = $_ENV['APP_ENV'];
    unset($_ENV['APP_ENV']);

    afterEach(function () {
        $_ENV['APP_ENV'] = $this->env;
    });

    Kernel::boot();
})->throws(RuntimeException::class);
