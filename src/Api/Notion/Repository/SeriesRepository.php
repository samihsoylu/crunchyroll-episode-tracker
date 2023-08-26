<?php

declare(strict_types=1);

namespace SamihSoylu\Crunchyroll\Api\Notion\Repository;

use Notion\Notion;
use Notion\Pages\Page;
use Notion\Pages\Properties\PropertyCollection;
use Notion\Pages\Properties\Url;
use SamihSoylu\Crunchyroll\Api\Notion\Entity\Serie;

final readonly class SeriesRepository implements SeriesRepositoryInterface
{
    private Notion $notion;

    public function __construct(
        private string $token,
    ) {
        $this->notion = Notion::create($this->token);
    }

    /**
     * @return Serie[]
     */
    public function getAllSeriesByDatabaseId(string $databaseId): array
    {
        $database = $this->notion->databases()->find($databaseId);

        return array_map(function (Page $page) {
            return Serie::fromApiPage($page);
        }, $this->notion->databases()->queryAllPages($database));
    }

    public function updateSerie(Serie $serie): void
    {
        $this->notion->pages()->update($serie->toApiPage());
    }
}