<?php

use DI\Container;
use Samihsoylu\Crunchyroll\Tests\TestDouble\Dummy\DummyObjectInterface;
use Samihsoylu\Crunchyroll\Tests\TestDouble\Dummy\DummyProdObject;

return function(Container $container) {
    $container->set(DummyObjectInterface::class, function() {
        return new DummyProdObject();
    });
};