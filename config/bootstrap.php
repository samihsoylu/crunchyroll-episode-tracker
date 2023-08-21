<?php

declare(strict_types=1);

use Brd6\NotionSdkPhp\Client;

use function Sentry\init;

require_once(dirname(__DIR__) . '/vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();
$dotenv->required(['NOTION_TOKEN', 'NOTION_DATABASE_ID']);

$container = require __DIR__ . '/container.php';

$sentryDsn = $_ENV['SENTRY_DSN'] ?? false;
if ($sentryDsn) {
    init(['dsn' => $sentryDsn]);
}
