<?php

declare(strict_types=1);

use SamihSoylu\Crunchyroll\Core\Framework\Core\Kernel;
use SamihSoylu\Crunchyroll\Cronjob\CrunchyrollToNotionSync;

require __DIR__ . '/config/bootstrap.php';

$kernel = Kernel::boot();
$sync = $kernel->container->get(CrunchyrollToNotionSync::class);
$sync($_ENV['NOTION_DATABASE_ID']);