<?php

declare(strict_types=1);

use Brd6\NotionSdkPhp\Client;
use Psr\Container\ContainerInterface;
use Samihsoylu\Crunchyroll\Core\Framework\AppEnv;
use Samihsoylu\Crunchyroll\Core\Framework\Container\ContainerFactory;
use function Sentry\init;

$_ENV['ROOT_DIR'] = dirname(__DIR__);

require_once($_ENV['ROOT_DIR'] . '/vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable($_ENV['ROOT_DIR']);
$dotenv->load();
$dotenv->required(['APP_ENV', 'NOTION_TOKEN', 'NOTION_DATABASE_ID']);

function getContainer(): ContainerInterface
{
    $environment = AppEnv::from($_ENV['APP_ENV']);
    $factory = new ContainerFactory(__DIR__, $environment);

    return $factory->create();
}

$sentryDsn = $_ENV['SENTRY_DSN'] ?? false;
if ($sentryDsn) {
    init(['dsn' => $sentryDsn]);
}
