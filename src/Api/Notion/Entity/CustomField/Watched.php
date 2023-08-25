<?php

declare(strict_types=1);

namespace SamihSoylu\Crunchyroll\Api\Notion\Entity\CustomField;

use Notion\Databases\Properties\SelectOption;

final readonly class Watched
{
    private function __construct() {}

    public static function newEpisodes(): SelectOption
    {
        return SelectOption::fromId('01809ef7-1295-4cbf-80c1-7f97c636c381');
    }

    public static function watched(): SelectOption
    {
        return SelectOption::fromId('55d1ef2d-0771-4610-845b-6384484fcccd');
    }
}