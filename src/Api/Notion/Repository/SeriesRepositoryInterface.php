<?php

declare(strict_types=1);

namespace SamihSoylu\CrunchyrollSyncer\Api\Notion\Repository;

use SamihSoylu\CrunchyrollSyncer\Api\Notion\Entity\SerieInterface;

interface SeriesRepositoryInterface
{
    /**
     * @param string $databaseId
     * @return SerieInterface[]
     */
    public function getAll(string $databaseId): array;

    public function update(SerieInterface $serie): void;
}
