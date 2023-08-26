<?php

use DI\Container;
use SamihSoylu\Crunchyroll\Tests\Framework\TestDouble\Dummy\DummyObjectInterface;
use SamihSoylu\Crunchyroll\Tests\Framework\TestDouble\Dummy\DummyTestObject;

return function(Container $container) {
    $container->set(DummyObjectInterface::class, function() {
        return new DummyTestObject();
    });
};