<?php

declare(strict_types=1);

namespace SamihSoylu\CrunchyrollSyncer\Api\Crunchyroll;

use SamihSoylu\CrunchyrollSyncer\Api\Crunchyroll\Repository\AnimeEpisodeRepositoryInterface;

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
