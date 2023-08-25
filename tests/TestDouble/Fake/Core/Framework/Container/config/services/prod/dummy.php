<?php

use DI\Container;
use SamihSoylu\Crunchyroll\Tests\TestDouble\Dummy\DummyObjectInterface;
use SamihSoylu\Crunchyroll\Tests\TestDouble\Dummy\DummyProdObject;

return function(Container $container) {
    $container->set(DummyObjectInterface::class, function() {
        return new DummyProdObject();
    });
};