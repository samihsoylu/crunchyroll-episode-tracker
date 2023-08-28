<?php

declare(strict_types=1);

use Mockery\MockInterface;
use Notion\Databases\Client as DatabaseClient;
use Notion\Databases\Database;
use Notion\Notion;
use Notion\Pages\Client as PagesClient;
use Notion\Pages\Page;
use SamihSoylu\Crunchyroll\Api\Notion\Entity\Serie;
use SamihSoylu\Crunchyroll\Api\Notion\Entity\SerieInterface;
use SamihSoylu\Crunchyroll\Api\Notion\Repository\SeriesRepository;

it('should get all series from a database', function () {
    $notion = getMockedNotion();
    $repo = new SeriesRepository($notion);

    $series = $repo->getAll('some_database_id');

    expect($series)->toBeArray()
        ->and($series[0])->toBeInstanceOf(SerieInterface::class);
});

it('should update a series', function () {
    $mockPages = Mockery::mock(PagesClient::class);
    $mockPages->shouldReceive('update')->once();

    $notion = getMockedNotion($mockPages);

    $repo = new SeriesRepository($notion);
    $serie = Serie::fromApiPage(getMockpage());

    $repo->update($serie);
});


function getMockedNotion(?PagesClient $mockedPages = null): MockInterface|Notion
{
    $mockedNotion = Mockery::mock(Notion::class);
    $mockedDatabaseClient = Mockery::mock(DatabaseClient::class);
    $mockedPagesClient = $mockedPages ?? Mockery::mock(PagesClient::class);

    $mockedPage = getMockpage();
    $mockedDatabase = Mockery::mock(Database::class);

    $mockedNotion->shouldReceive('databases')
        ->andReturn($mockedDatabaseClient);

    $mockedNotion->shouldReceive('pages')
        ->andReturn($mockedPagesClient);

    $mockedDatabaseClient->shouldReceive('find')
        ->andReturn($mockedDatabase);

    $mockedDatabaseClient->shouldReceive('queryAllPages')
        ->andReturn([$mockedPage]);

    $mockedPagesClient->shouldReceive('update')
        ->andReturn($mockedPage);

    return $mockedNotion;
}
