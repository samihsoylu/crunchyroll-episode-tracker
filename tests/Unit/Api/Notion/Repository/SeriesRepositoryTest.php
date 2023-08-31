<?php

declare(strict_types=1);

use Notion\Pages\Client as PagesClient;
use SamihSoylu\Crunchyroll\Api\Notion\Entity\Serie;
use SamihSoylu\Crunchyroll\Api\Notion\Entity\SerieInterface;
use SamihSoylu\Crunchyroll\Api\Notion\Repository\SeriesRepository;

it('should get all series from a database', function () {
    $fakePage = testKit()->notion()->loadFakePage();
    $mockNotionApiClient = testKit()->notion()->createMock($fakePage);

    $repository = new SeriesRepository($mockNotionApiClient);
    $series = $repository->getAll('some_database_id');

    expect($series)->toBeArray();
    foreach ($series as $serie) {
        expect($serie)->toBeInstanceOf(SerieInterface::class);
    }
});

it('should update a series', function () {
    $fakePage = testKit()->notion()->loadFakePage();
    $mockPagesClient = Mockery::mock(PagesClient::class);

    $mockPagesClient->shouldReceive('update')
        ->once();

    $mockNotionApiClient = testKit()
        ->notion()
        ->createMock($fakePage, $mockPagesClient);

    $repository = new SeriesRepository($mockNotionApiClient);
    $serie = Serie::fromApiPage($fakePage);

    $repository->update($serie);
});
