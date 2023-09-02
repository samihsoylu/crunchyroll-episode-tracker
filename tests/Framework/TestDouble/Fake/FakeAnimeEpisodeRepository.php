<?php

declare(strict_types=1);

namespace SamihSoylu\CrunchyrollSyncer\Tests\Framework\TestDouble\Fake;

use SamihSoylu\CrunchyrollSyncer\Api\Crunchyroll\Entity\AnimeEpisode;
use SamihSoylu\CrunchyrollSyncer\Api\Crunchyroll\Repository\AnimeEpisodeRepositoryInterface;

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
