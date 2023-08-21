<?php

declare(strict_types=1);

namespace Samihsoylu\Crunchyroll\Api\Notion;

use Brd6\NotionSdkPhp\Client;
use Brd6\NotionSdkPhp\Resource\Database;
use Brd6\NotionSdkPhp\Resource\Page;
use Samihsoylu\Crunchyroll\Api\Notion\Repository\DatabaseRepository;
use Samihsoylu\Crunchyroll\Api\Notion\Repository\DatabaseRepositoryInterface;

final class NotionApiClient
{
    public function __construct(
        private readonly DatabaseRepositoryInterface $databaseRepository,
    ) {}

    public function getDatabaseRepository(): DatabaseRepositoryInterface
    {
        return $this->databaseRepository;
    }
}