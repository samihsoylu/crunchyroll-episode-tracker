<?php

declare(strict_types=1);

namespace SamihSoylu\CrunchyrollSyncer\Api\Notion\Entity\Field;

final class Episode implements \Stringable
{
    private int $seasonNumber;
    private int $episodeNumber;

    public function __construct(int $seasonNumber, int $episodeNumber)
    {
        $this->seasonNumber = $seasonNumber;
        $this->episodeNumber = $episodeNumber;
    }

    public function getSeasonNumber(): int
    {
        return $this->seasonNumber;
    }

    public function setSeasonNumber(int $seasonNumber): Episode
    {
        $this->seasonNumber = $seasonNumber;

        return $this;
    }

    public function getEpisodeNumber(): int
    {
        return $this->episodeNumber;
    }

    public function setEpisodeNumber(int $episodeNumber): Episode
    {
        $this->episodeNumber = $episodeNumber;

        return $this;
    }

    public function isNewEpisode(int $seasonNumber, int $episodeNumber): bool
    {
        if ($this->seasonNumber < $seasonNumber) {
            return true;
        }

        if ($this->seasonNumber === $seasonNumber && $this->episodeNumber < $episodeNumber) {
            return true;
        }

        return false;
    }

    public function isBehindMultipleEpisodes(int $seasonNumber, int $episodeNumber): bool
    {
        if ($this->seasonNumber < $seasonNumber) {
            return true;
        }

        if ($this->seasonNumber === $seasonNumber && ($this->episodeNumber + 1) < $episodeNumber) {
            return true;
        }

        return false;
    }

    public function __toString(): string
    {
        return sprintf("S%02dE%02d", $this->seasonNumber, $this->episodeNumber);
    }

    public static function fromString(string $episode): self
    {
        if (preg_match('/S(\d+)E(\d+)/', $episode, $matches)) {
            return new self((int)$matches[1], (int)$matches[2]);
        }

        throw new \InvalidArgumentException("Invalid episode string format '{$episode}'");
    }
}
