<?php

declare(strict_types=1);

namespace Samihsoylu\Crunchyroll\Api\Crunchyroll;

use Samihsoylu\Crunchyroll\Api\Crunchyroll\Repository\AnimeEpisodeRepository;
use Samihsoylu\Crunchyroll\Api\Crunchyroll\Repository\AnimeEpisodeRepositoryInterface;

final class CrunchyrollApiClient
{
    public function __construct(
        private readonly AnimeEpisodeRepositoryInterface $repository,
    ) {}
    public function getAnimeEpisodeRepository(): AnimeEpisodeRepositoryInterface
    {
        return $this->repository;
    }
}