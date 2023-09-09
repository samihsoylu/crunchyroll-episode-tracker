<?php

declare(strict_types=1);

namespace SamihSoylu\CrunchyrollSyncer\Tests\Framework\TestDouble\Fake;

use BadMethodCallException;
use Notion\Databases\Properties\SelectOption;
use Notion\Pages\Page;
use SamihSoylu\CrunchyrollSyncer\Api\Notion\Entity\Field\Episode;
use SamihSoylu\CrunchyrollSyncer\Api\Notion\Entity\Option\EpisodeStatus;
use SamihSoylu\CrunchyrollSyncer\Api\Notion\Entity\SerieInterface;

final class FakeSerie implements SerieInterface
{
    public function __construct(
        private readonly string $name,
        private Episode $currentEpisode,
        private Episode $previousEpisode,
        private string $currentEpisodeUrl,
        private SelectOption $currentEpisodeStatus,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCurrentEpisode(): Episode
    {
        return $this->currentEpisode;
    }

    public function setCurrentEpisode(Episode $currentEpisode): self
    {
        $this->currentEpisode = $currentEpisode;

        return $this;
    }

    public function setPreviousEpisode(Episode $previousEpisode): self
    {
        $this->previousEpisode = $previousEpisode;

        return $this;
    }

    public function setCurrentEpisodeUrl(string $url): self
    {
        $this->currentEpisodeUrl = $url;

        return $this;
    }

    public function setCurrentEpisodeStatus(SelectOption $status): self
    {
        $this->currentEpisodeStatus = $status;

        return $this;
    }

    public function isMarkedAsNewEpisode(): bool
    {
        return $this->getCurrentEpisodeStatus() === EpisodeStatus::NEW_EPISODE;
    }

    public static function fromApiPage(Page $page): self
    {
        throw new BadMethodCallException('Method fromApiPage not implemented');
    }

    public function toApiPage(): Page
    {
        throw new BadMethodCallException('Method toApiPage not implemented');
    }

    public function getCurrentEpisodeStatus(): ?string
    {
        return $this->currentEpisodeStatus->toArray()['name'];
    }
}
