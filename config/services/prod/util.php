<?php

declare(strict_types=1);

use DI\Container;
use SamihSoylu\CrunchyrollSyncer\Util\Contract\SleepHelperInterface;
use SamihSoylu\CrunchyrollSyncer\Util\SleepHelper;

return function (Container $container) {
    $container->set(SleepHelperInterface::class, function (Container $container) {
        return $container->get(SleepHelper::class);
    });
};
