<?php

declare(strict_types=1);

use DI\Container;
use SamihSoylu\Crunchyroll\Core\Framework\AppEnv;

return function (Container $container) {
    $container->set(AppEnv::class, function() {
        return AppEnv::from($_ENV['APP_ENV']);
    });
};