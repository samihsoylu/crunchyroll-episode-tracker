<?php

declare(strict_types=1);

namespace SamihSoylu\Crunchyroll\Api\Notion\Entity\Option;

use Notion\Databases\Properties\SelectOption;

final readonly class EpisodeStatus
{
    private function __construct() {}

    public static function newEpisode(): SelectOption
    {
        return SelectOption::fromName('New Episode');
    }

    public static function watched(): SelectOption
    {
        return SelectOption::fromName('Watched');
    }
}