<?php

declare(strict_types=1);

use function Sentry\init;

ini_set('display_errors', 1);
ini_set('log_errors', 0);
error_reporting(E_ALL);

$_ENV['ROOT_DIR'] = dirname(__DIR__);
$_ENV['APP_CONFIG_DIR'] = $_ENV['ROOT_DIR'] . '/config';
$_ENV['LOG_DIR'] = $_ENV['ROOT_DIR'] . '/var/log';

$_ENV['APP_NAME'] = 'Crunchyroll';
$_ENV['APP_VERSION'] = 'v1.0.0';

require_once($_ENV['ROOT_DIR'] . '/vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable($_ENV['ROOT_DIR']);
$dotenv->load();

$dotenv->required(['APP_ENV', 'NOTION_TOKEN', 'NOTION_DATABASE_ID', 'CRUNCHYROLL_RSS_FEED_URL']);

$sentryDsn = $_ENV['SENTRY_DSN'] ?? false;
if ($sentryDsn && $_ENV['APP_ENV'] === 'prod') {
    init(['dsn' => $sentryDsn]);
}
