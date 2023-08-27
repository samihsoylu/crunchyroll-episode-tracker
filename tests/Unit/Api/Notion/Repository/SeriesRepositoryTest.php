<?php

declare(strict_types=1);

use Mockery\MockInterface;
use Notion\Notion;
use Notion\Databases\Client as DatabaseClient;
use Notion\Pages\Client as PagesClient;
use Notion\Databases\Database;
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
    $serie = Serie::fromApiPage(getMockPageForSeriesRepositoryTest());

    $repo->update($serie);
});


function getMockPageForSeriesRepositoryTest(): Page
{
    $json = file_get_contents(__DIR__ . '/MockPage.json');
    $array = json_decode($json, true);

    return Page::fromArray($array);
}

function getMockedNotion(?PagesClient $mockedPages = null): MockInterface|Notion
{
    $mockedNotion = Mockery::mock(Notion::class);
    $mockedDatabaseClient = Mockery::mock(DatabaseClient::class);
    $mockedPagesClient = $mockedPages ?? Mockery::mock(PagesClient::class);

    $mockedPage = getMockPageForSeriesRepositoryTest();  // Assuming getMockPageForSeriesRepositoryTest() returns a mock Page object
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