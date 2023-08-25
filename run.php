<?php

declare(strict_types=1);

use Samihsoylu\Crunchyroll\Core\Framework\Core\Kernel;
use Samihsoylu\Crunchyroll\Cronjob\Sync;

require __DIR__ . '/config/bootstrap.php';

$kernel = Kernel::boot();
$sync = $kernel->container->get(Sync::class);
$sync($_ENV['NOTION_DATABASE_ID']);
