<?php

declare(strict_types=1);

use DI\Container;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use SamihSoylu\CrunchyrollSyncer\Core\Framework\AppEnv;
use Symfony\Component\Console\Application;

return function (Container $container) {
    $container->set(AppEnv::class, function () {
        return AppEnv::from($_ENV['APP_ENV']);
    });

    $container->set(LoggerInterface::class, function () {
        if ($_ENV['ENABLE_DEBUG_MODE'] !== 'true') {
            return new NullLogger();
        }

        $logger = new Logger('debug-log');
        $logger->pushHandler(new StreamHandler($_ENV['LOG_DIR'] . '/debug.log'));

        return $logger;
    });

    $container->set(Application::class, function () {
        return new Application($_ENV['APP_NAME'], $_ENV['APP_VERSION']);
    });
};
