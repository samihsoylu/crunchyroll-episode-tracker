<?php

declare(strict_types=1);

namespace SamihSoylu\CrunchyrollSyncer\Tests\Framework\TestDouble\Spy;

use SamihSoylu\CrunchyrollSyncer\Api\Notion\Entity\Serie;
use SamihSoylu\CrunchyrollSyncer\Api\Notion\Entity\SerieInterface;
use SamihSoylu\CrunchyrollSyncer\Api\Notion\Repository\SeriesRepositoryInterface;

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
