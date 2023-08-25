<?php

declare(strict_types=1);

use Brd6\NotionSdkPhp\Client;
use Psr\Container\ContainerInterface;
use SamihSoylu\Crunchyroll\Core\Framework\AppEnv;
use SamihSoylu\Crunchyroll\Core\Framework\Core\ContainerFactory;
use function Sentry\init;

$_ENV['ROOT_DIR'] = dirname(__DIR__);
$_ENV['APP_CONFIG_DIR'] = $_ENV['ROOT_DIR'] . '/config';

require_once($_ENV['ROOT_DIR'] . '/vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable($_ENV['ROOT_DIR']);
$dotenv->load();
$dotenv->required(['APP_ENV', 'NOTION_TOKEN', 'NOTION_DATABASE_ID']);

$sentryDsn = $_ENV['SENTRY_DSN'] ?? false;
if ($sentryDsn) {
    init(['dsn' => $sentryDsn]);
}
