<?php

declare(strict_types=1);

namespace Samihsoylu\Crunchyroll\Api\Crunchyroll\Entity;

use DateTimeImmutable;
use SimpleXMLElement;

final class AnimeEpisode {
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

    private function __construct() {}

    public static function fromSimpleXmlElement(SimpleXMLElement $item): self
    {
        $instance = new self();

        $instance->title = (string) $item->title;
        $instance->link = (string) $item->link;
        $instance->description = (string) $item->description;

        $namespaces = $item->getNamespaces(true);
        $crunchyroll = $item->children($namespaces['crunchyroll']);

        $instance->seriesTitle = (string) $crunchyroll->seriesTitle;
        $instance->episodeTitle = (string) $crunchyroll->episodeTitle;
        $instance->episodeNumber = (int) $crunchyroll->episodeNumber;
        $instance->seasonNumber = (int) ($crunchyroll->season ?? 1);
        $instance->duration = (string) $crunchyroll->duration;
        $instance->publisher = (string) $crunchyroll->publisher;
        $instance->publishedDate = (string) $crunchyroll->premiumPubDate;

        return $instance;
    }

    /**
     * @param array<string, scalar> $struct
     * @return self
     */
    public static function fromArray(array $struct): self
    {
        $self = new self();

        foreach ($struct as $fieldName => $fieldValue) {
            if (property_exists($self, $fieldName)) {
                $self->{$fieldName} = $fieldValue;
            }
        }

        return $self;
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

    public function getEpisodeNumber(): int
    {
        return $this->episodeNumber;
    }

    public function getSeasonNumber(): int
    {
        return $this->seasonNumber;
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