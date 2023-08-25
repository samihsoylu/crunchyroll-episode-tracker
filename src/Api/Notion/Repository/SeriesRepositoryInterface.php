<?php

declare(strict_types=1);

namespace SamihSoylu\Crunchyroll\Api\Notion\Repository;

use Notion\Pages\Page;
use SamihSoylu\Crunchyroll\Api\Notion\Entity\Serie;

interface SeriesRepositoryInterface
{
    /**
     * @param string $databaseId
     * @return Serie[]
     */
    public function getAllSeriesByDatabaseId(string $databaseId): array;

    public function updateSerie(Page $page): void;
}