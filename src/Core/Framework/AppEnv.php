<?php

declare(strict_types=1);

namespace Samihsoylu\Crunchyroll\Core\Framework;

enum AppEnv: string
{
    case PROD = 'prod';
    case TEST = 'test';
    case DEV  = 'dev';
}