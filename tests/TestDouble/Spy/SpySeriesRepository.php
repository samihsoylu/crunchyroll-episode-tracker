<?php

declare(strict_types=1);

namespace SamihSoylu\Crunchyroll\Tests\TestDouble\Spy;

use PHPUnit\Framework\TestCase;
use Samihsoylu\Crunchyroll\Api\Notion\Repository\SeriesRepositoryInterface;

final class SpySeriesRepository extends TestCase implements SeriesRepositoryInterface
{
    private bool $updateInvoked = false;

    public function __construct()
    {
        parent::__construct(self::class);
    }

    public function getById(string $databaseId): object
    {
        return new \StdClass();
    }

    public function update(object $object): void
    {
        $this->updateInvoked = true;
    }

    public function assertUpdateInvoked(): void
    {
        self::assertTrue($this->updateInvoked, 'Update was not invoked');
    }
}