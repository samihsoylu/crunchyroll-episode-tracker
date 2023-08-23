<?php

declare(strict_types=1);

use Brd6\NotionSdkPhp\Client;
use Brd6\NotionSdkPhp\ClientOptions;
use DI\Container;

return function (Container $container) {
    $container->set(Client::class, function() {
        $options = (new ClientOptions())
            ->setAuth($_ENV['NOTION_TOKEN']);

        return new Client($options);
    });
};