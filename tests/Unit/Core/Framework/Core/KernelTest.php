<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use SamihSoylu\CrunchyrollSyncer\Core\Framework\AppEnv;
use SamihSoylu\CrunchyrollSyncer\Core\Framework\Core\Kernel;

it('should boot up kernel', function () {
    $kernel = Kernel::boot();

    expect($kernel->environment)->toBe(AppEnv::from($_ENV['APP_ENV']));
});

it('should initialize container', function () {
    $_ENV['CONFIG_DIR'] = $_ENV['ROOT_DIR'] . '/tests/Framework/TestDouble/Fake/Infrastructure/Framework/Core';

    $kernel = Kernel::boot();

    expect($kernel->container)->toBeInstanceOf(ContainerInterface::class);
});

it('should throw when environment variable is not set', function () {
    $env = $_ENV['APP_ENV'];
    unset($_ENV['APP_ENV']);

    afterEach(function () use ($env) {
        $_ENV['APP_ENV'] = $env;
    });

    Kernel::boot();
})->throws(RuntimeException::class);
