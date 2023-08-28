<?php

declare(strict_types=1);

namespace SamihSoylu\Crunchyroll\Api\Notion\Entity\Option;

use Notion\Databases\Properties\SelectOption;

final readonly class EpisodeStatus
{
    public const NEW_EPISODE = 'New Episode';
    public const WATCHED = 'Watched';

    public static function newEpisode(): SelectOption
    {
        return SelectOption::fromName(self::NEW_EPISODE);
    }

    public static function watched(): SelectOption
    {
        return SelectOption::fromName(self::WATCHED);
    }
}
