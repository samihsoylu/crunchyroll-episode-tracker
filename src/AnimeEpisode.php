<?php

declare(strict_types=1);

namespace Samihsoylu\Crunchyroll;

use DateTimeImmutable;
use SimpleXMLElement;

final class AnimeEpisode {
    private string $title;
    private string $link;
    private string $description;
    private string $seriesTitle;
    private string $episodeTitle;
    private string $episodeNumber;
    private string $duration;
    private string $publisher;
    private string $publishedDate;

    private function __construct() {}

    public static function fromFeed(SimpleXMLElement $item): self
    {
        $instance = new self();

        $instance->title = (string) $item->title;
        $instance->link = (string) $item->link;
        $instance->description = (string) $item->description;

        $namespaces = $item->getNamespaces(true);
        $crunchyroll = $item->children($namespaces['crunchyroll']);

        $instance->seriesTitle = (string) $crunchyroll->seriesTitle;
        $instance->episodeTitle = (string) $crunchyroll->episodeTitle;
        $instance->episodeNumber = (string) $crunchyroll->episodeNumber;
        $instance->duration = (string) $crunchyroll->duration;
        $instance->publisher = (string) $crunchyroll->publisher;
        $instance->publishedDate = (string) $crunchyroll->premiumPubDate;

        return $instance;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getSeriesTitle(): string
    {
        return $this->seriesTitle;
    }

    public function getEpisodeTitle(): string
    {
        return $this->episodeTitle;
    }

    public function getEpisodeNumber(): string
    {
        return $this->episodeNumber;
    }

    public function getDuration(): string
    {
        return $this->duration;
    }

    public function getPublisher(): string
    {
        return $this->publisher;
    }

    public function getPublishedDate(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->publishedDate);
    }
}