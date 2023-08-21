<?php

declare(strict_types=1);

namespace Samihsoylu\Crunchyroll\Api\Notion\Repository;

interface DatabaseRepositoryInterface
{
    public function getById(string $databaseId): object;

    public function update(object $object): void;
}