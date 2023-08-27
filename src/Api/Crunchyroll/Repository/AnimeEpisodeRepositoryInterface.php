<?php

declare(strict_types=1);

namespace SamihSoylu\Crunchyroll\Api\Crunchyroll\Repository;

use SamihSoylu\Crunchyroll\Api\Crunchyroll\Entity\AnimeEpisode;

interface AnimeEpisodeRepositoryInterface
{
    /**
     * @return AnimeEpisode[]
     */
    public function getLatestEpisodes(): array;
}
