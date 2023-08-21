<?php

use Brd6\NotionSdkPhp\Client;
use Brd6\NotionSdkPhp\ClientOptions;
use DI\Container;
use Samihsoylu\Crunchyroll\Api\Notion\NotionApiClient;

$container = new DI\Container();

$container->set(Client::class, function() {
    $options = (new ClientOptions())
        ->setAuth($_ENV['NOTION_TOKEN']);

    return new Client($options);
});

//$container->set(NotionApiClient::class, function(Container $container) {
//    return new NotionApiClient(
//        $container->get(Client::class),
//        // other param,
//    );
//});

return $container;