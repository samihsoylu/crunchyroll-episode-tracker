<?php

declare(strict_types=1);

namespace SamihSoylu\Crunchyroll\Api\Crunchyroll;

use SamihSoylu\Crunchyroll\Api\Crunchyroll\Repository\AnimeEpisodeRepositoryInterface;

final class CrunchyrollApiClient
{
    public function __construct(
        private readonly AnimeEpisodeRepositoryInterface $repository,
    ) {
    }

    public function animeEpisode(): AnimeEpisodeRepositoryInterface
    {
        return $this->repository;
    }
}
