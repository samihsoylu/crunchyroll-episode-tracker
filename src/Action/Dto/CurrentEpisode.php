<?php

declare(strict_types=1);

namespace SamihSoylu\CrunchyrollSyncer\Action\Dto;

use SamihSoylu\CrunchyrollSyncer\Api\Crunchyroll\Entity\AnimeEpisode;
use SamihSoylu\CrunchyrollSyncer\Api\Notion\Entity\Field\Episode;

final class CurrentEpisode
{
    public function __construct(
        private Episode $episode,
    ) {
    }

    public function isOld(AnimeEpisode $episode): bool
    {
        return $this->episode->isOldEpisode(
            $episode->getSeasonNumber(),
            $episode->getEpisodeNumber(),
        );
    }

    public function isBehind(AnimeEpisode $episode): bool
    {
        return $this->episode->isBehindMultipleEpisodes(
            $episode->getSeasonNumber(),
            $episode->getEpisodeNumber()
        );
    }
}
