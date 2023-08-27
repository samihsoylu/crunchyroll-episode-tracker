<?php

declare(strict_types=1);

namespace SamihSoylu\Crunchyroll\Api\Notion\Repository;

use Notion\Pages\Page;
use SamihSoylu\Crunchyroll\Api\Notion\Entity\Serie;
use SamihSoylu\Crunchyroll\Api\Notion\Entity\SerieInterface;

interface SeriesRepositoryInterface
{
    /**
     * @param string $databaseId
     * @return SerieInterface[]
     */
    public function getAll(string $databaseId): array;

    public function update(SerieInterface $serie): void;
}