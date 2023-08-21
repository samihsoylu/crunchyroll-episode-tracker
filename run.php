<?php

declare(strict_types=1);

use Samihsoylu\Crunchyroll\Cronjob\Sync;

/** @var DI\Container $container */
require __DIR__ . '/config/bootstrap.php';

$sync = $container->get(Sync::class);
$sync($_ENV['NOTION_DATABASE_ID']);
