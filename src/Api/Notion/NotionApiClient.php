<?php

declare(strict_types=1);

namespace SamihSoylu\CrunchyrollSyncer\Api\Notion;

use SamihSoylu\CrunchyrollSyncer\Api\Notion\Repository\SeriesRepositoryInterface;

final readonly class NotionApiClient
{
    public function __construct(
        private readonly SeriesRepositoryInterface $seriesRepository,
    ) {
    }

    public function series(): SeriesRepositoryInterface
    {
        return $this->seriesRepository;
    }
}
