<?php

declare(strict_types=1);

namespace SamihSoylu\Crunchyroll\Tests\Framework\TestDouble\Fake;

use SamihSoylu\Crunchyroll\Api\Crunchyroll\Entity\AnimeEpisode;
use SamihSoylu\Crunchyroll\Api\Crunchyroll\Repository\AnimeEpisodeRepositoryInterface;

final class FakeAnimeEpisodeRepository implements AnimeEpisodeRepositoryInterface
{
    /** @var AnimeEpisode[] */
    private array $latestEpisodes = [];

    public function getLatestEpisodes(): array
    {
        $mappedEpisodes = [];

        foreach ($this->latestEpisodes as $episode) {
            $mappedEpisodes[strtolower($episode->getSeriesTitle())] = $episode;
        }

        return $mappedEpisodes;
    }

    public function setLatestEpisodes(AnimeEpisode ...$latestEpisodes): void
    {
        $this->latestEpisodes = $latestEpisodes;
    }
}
