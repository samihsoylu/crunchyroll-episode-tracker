<?php

declare(strict_types=1);

namespace SamihSoylu\CrunchyrollSyncer\Api\Crunchyroll\Repository;

use SamihSoylu\CrunchyrollSyncer\Api\Crunchyroll\Entity\AnimeEpisode;

interface AnimeEpisodeRepositoryInterface
{
    /**
     * @return AnimeEpisode[]
     */
    public function getLatestEpisodes(): array;
}
