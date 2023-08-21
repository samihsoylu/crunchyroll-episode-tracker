<?php

declare(strict_types=1);

namespace Samihsoylu\Crunchyroll\Tests\TestDouble\Spy;

use Samihsoylu\Crunchyroll\Api\Notion\Repository\DatabaseRepositoryInterface;
use Samihsoylu\Crunchyroll\Tests\TestCase;

final class SpyDatabaseRepository extends TestCase implements DatabaseRepositoryInterface
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