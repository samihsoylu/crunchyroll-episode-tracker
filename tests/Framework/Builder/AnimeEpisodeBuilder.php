<?php

declare(strict_types=1);

namespace SamihSoylu\Crunchyroll\Tests\Framework\Builder;

use SamihSoylu\Crunchyroll\Api\Crunchyroll\Entity\AnimeEpisode;

final class AnimeEpisodeBuilder
{
    private string $title;
    private string $link;
    private string $description;
    private string $seriesTitle;
    private string $episodeTitle;
    private int $seasonNumber;
    private int $episodeNumber;
    private string $duration;
    private string $publisher;
    private string $publishedDate;

    public function __construct()
    {
        $this->setDefaults();
    }

    public function withTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function withLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function withDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function withSeriesTitle(string $seriesTitle): self
    {
        $this->seriesTitle = $seriesTitle;

        return $this;
    }

    public function withEpisodeTitle(string $episodeTitle): self
    {
        $this->episodeTitle = $episodeTitle;

        return $this;
    }

    public function withSeasonNumber(int $seasonNumber): self
    {
        $this->seasonNumber = $seasonNumber;

        return $this;
    }

    public function withEpisodeNumber(int $episodeNumber): self
    {
        $this->episodeNumber = $episodeNumber;

        return $this;
    }

    public function withDuration(string $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function withPublisher(string $publisher): self
    {
        $this->publisher = $publisher;

        return $this;
    }

    public function withPublishedDate(string $publishedDate): self
    {
        $this->publishedDate = $publishedDate;

        return $this;
    }

    private function setDefaults(): void
    {
        $this->title = 'Blah';
        $this->link = 'http://crunchyroll.com/blah/';
        $this->description = '';
        $this->seriesTitle = 'Blah';
        $this->episodeTitle = 'Blah One';
        $this->episodeNumber = 2;
        $this->seasonNumber = 1;
        $this->duration = '60';
        $this->publisher = 'Samih';
        $this->publishedDate = '2022-03-02 10:03:04';
    }
    public function build(): AnimeEpisode
    {
        return AnimeEpisode::fromArray([
            'title' => $this->title,
            'link' => $this->link,
            'description' => $this->description,
            'seriesTitle' => $this->seriesTitle,
            'episodeTitle' => $this->episodeTitle,
            'episodeNumber' => $this->episodeNumber,
            'seasonNumber' => $this->seasonNumber,
            'duration' => $this->duration,
            'publisher' => $this->publisher,
            'publishedDate' => $this->publishedDate,
        ]);
    }
}