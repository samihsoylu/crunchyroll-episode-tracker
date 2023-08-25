<?php

declare(strict_types=1);

use SamihSoylu\Crunchyroll\Core\Framework\AppEnv;

it('should be prod', function () {
    $env = AppEnv::PROD;

    expect($env->isProd())->toBeTrue()
        ->and($env->isTest())->toBeFalse()
        ->and($env->isDev())->toBeFalse();
});

it('should be test', function () {
    $env = AppEnv::TEST;

    expect($env->isProd())->toBeFalse()
        ->and($env->isTest())->toBeTrue()
        ->and($env->isDev())->toBeFalse();
});

it('should be dev', function () {
    $env = AppEnv::DEV;

    expect($env->isProd())->toBeFalse()
        ->and($env->isTest())->toBeFalse()
        ->and($env->isDev())->toBeTrue();
});