<?php

declare(strict_types=1);

namespace Samihsoylu\Crunchyroll\Api\Notion\Repository;

use Brd6\NotionSdkPhp\Client;
use Brd6\NotionSdkPhp\Resource\Database;
use Brd6\NotionSdkPhp\Resource\Page;

final class DatabaseRepository implements DatabaseRepositoryInterface
{
    public function __construct(
        private readonly Client $notion,
    ) {}

    public function getById(string $databaseId): object
    {
        $response = $this->notion->databases()->query($databaseId);

        /** @var Page[] $pages */
        $pages = $response->getResults();

        foreach ($pages as $page) {
            echo "Id {$page->getId()}\n ";
            print_r($page);
            exit(0);
        }
    }

    public function update(object $object): void
    {
        // we need to reference the database
        // we need to reference the page
//        $this->notion->databases()->update(
//            (new Database())
//                ->setId($id)
//                ->setObject()
//        )
    }
}