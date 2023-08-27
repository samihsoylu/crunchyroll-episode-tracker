<?php

declare(strict_types=1);

namespace SamihSoylu\Crunchyroll\Api\Notion;

use SamihSoylu\Crunchyroll\Api\Notion\Repository\SeriesRepository;
use SamihSoylu\Crunchyroll\Api\Notion\Repository\SeriesRepositoryInterface;

final readonly class NotionApiClient
{
    public function __construct(
        private readonly SeriesRepositoryInterface $seriesRepository,
    ) {}

    public function series(): SeriesRepositoryInterface
    {
        return $this->seriesRepository;
    }
}