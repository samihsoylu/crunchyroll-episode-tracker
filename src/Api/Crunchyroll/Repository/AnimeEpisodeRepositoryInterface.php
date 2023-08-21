<?php

declare(strict_types=1);

namespace Samihsoylu\Crunchyroll\Api\Crunchyroll\Repository;

use Samihsoylu\Crunchyroll\Api\Crunchyroll\Entity\AnimeEpisode;

interface AnimeEpisodeRepositoryInterface
{
    /**
     * @return AnimeEpisode[]
     */
    public function getLatestEpisodes(): array;
}