<?php

declare(strict_types=1);

namespace SamihSoylu\Crunchyroll\Tests\Framework\TestDouble\Spy;

use SamihSoylu\Crunchyroll\Api\Notion\Entity\Serie;
use SamihSoylu\Crunchyroll\Api\Notion\Entity\SerieInterface;
use SamihSoylu\Crunchyroll\Api\Notion\Repository\SeriesRepositoryInterface;

final class SpySeriesRepository implements SeriesRepositoryInterface
{
    /** @var SerieInterface[] */
    private array $allSeries = [];

    /** @var Serie[] */
    private array $updatedSeries = [];

    public function getAll(string $databaseId): array
    {
        return $this->allSeries;
    }

    public function setAllSeries(SerieInterface ...$series): void
    {
        $this->allSeries = $series;
    }

    public function update(SerieInterface $serie): void
    {
        $this->updatedSeries[] = $serie;
    }

    public function getUpdatedSeries(): array
    {
        return $this->updatedSeries;
    }
}
