<?php

use DI\Container;
use Samihsoylu\Crunchyroll\Tests\TestDouble\Dummy\DummyObjectInterface;
use Samihsoylu\Crunchyroll\Tests\TestDouble\Dummy\DummyTestObject;

return function(Container $container) {
    $container->set(DummyObjectInterface::class, function() {
        return new DummyTestObject();
    });
};