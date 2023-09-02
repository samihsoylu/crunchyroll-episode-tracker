<?php

declare(strict_types=1);

namespace SamihSoylu\CrunchyrollSyncer\Tests\Framework\Core;

use Mockery;
use Mockery\MockInterface;
use Notion\Databases\Client as DatabaseClient;
use Notion\Databases\Database;
use Notion\Notion;
use Notion\Pages\Client as PagesClient;
use Notion\Pages\Page;

final class NotionTestHelper
{
    public function loadFakePage(): Page
    {
        $json = file_get_contents(dirname(__DIR__) . '/TestDouble/Fake/FakeNotionPage.json');
        $array = json_decode($json, true);

        return Page::fromArray($array);
    }

    public function createMock(Page $fakePage, ?MockInterface $mockPagesClient = null): MockInterface|Notion
    {
        $mockedNotion = Mockery::mock(Notion::class);
        $mockedDatabaseClient = Mockery::mock(DatabaseClient::class);
        $mockedPagesClient = $mockPagesClient ?? Mockery::mock(PagesClient::class);

        $mockedDatabase = Mockery::mock(Database::class);

        $mockedNotion->shouldReceive('databases')
            ->andReturn($mockedDatabaseClient);

        $mockedNotion->shouldReceive('pages')
            ->andReturn($mockedPagesClient);

        $mockedDatabaseClient->shouldReceive('find')
            ->andReturn($mockedDatabase);

        $mockedDatabaseClient->shouldReceive('queryAllPages')
            ->andReturn([$fakePage]);

        $mockedPagesClient->shouldReceive('update')
            ->andReturn($fakePage);

        return $mockedNotion;
    }
}
