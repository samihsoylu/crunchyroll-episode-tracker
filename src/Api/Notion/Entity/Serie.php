<?php

declare(strict_types=1);

namespace SamihSoylu\Crunchyroll\Api\Notion\Entity;

use Notion\Common\RichText;
use Notion\Databases\Properties\SelectOption;
use Notion\Pages\Page;
use Notion\Pages\Properties\RichTextProperty;
use Notion\Pages\Properties\Select;
use Notion\Pages\Properties\Url;
use SamihSoylu\Crunchyroll\Api\Notion\Entity\Field\Episode;
use SamihSoylu\Crunchyroll\Api\Notion\Entity\Option\EpisodeStatus;

class Serie implements SerieInterface
{
    private const FIELD_NAME = 'Name';

    private const FIELD_CURRENT_EPISODE = 'Current episode';
    private const FIELD_PREVIOUS_EPISODE = 'Previous episode';

    private const FIELD_CURRENT_EPISODE_STATUS = 'Current episode status';
    private const FIELD_CURRENT_EPISODE_URL = 'Current episode url';

    protected function __construct(
        private Page $page,
    ) {
    }

    public function getName(): string
    {
        return $this->page->properties()->get(self::FIELD_NAME)
            ->toArray()['title'][0]['plain_text'];
    }

    public function getCurrentEpisode(): Episode
    {
        $episode = $this->page->properties()->get(self::FIELD_CURRENT_EPISODE)
            ->toArray()['rich_text'][0]['plain_text'];

        return Episode::fromString($episode);
    }

    public function setCurrentEpisode(Episode $currentEpisode): self
    {
        /** @var RichTextProperty $currentEpisodeProperty */
        $currentEpisodeProperty = $this->page->properties()->get(self::FIELD_CURRENT_EPISODE);

        $updatedProperty = $currentEpisodeProperty->changeText(RichText::fromString((string) $currentEpisode));
        $updatedProperties = $this->page->properties()->change(self::FIELD_CURRENT_EPISODE, $updatedProperty);

        $this->page = $this->page->changeProperties($updatedProperties->getAll());

        return $this;
    }

    public function setPreviousEpisode(Episode $previousEpisode): self
    {
        /** @var RichTextProperty $previousEpisodeProperty */
        $previousEpisodeProperty = $this->page->properties()->get(self::FIELD_PREVIOUS_EPISODE);

        $updatedProperty = $previousEpisodeProperty->changeText(RichText::fromString((string) $previousEpisode));
        $updatedProperties = $this->page->properties()->change(self::FIELD_PREVIOUS_EPISODE, $updatedProperty);

        $this->page = $this->page->changeProperties($updatedProperties->getAll());

        return $this;
    }

    public function setCurrentEpisodeUrl(string $url): self
    {
        /** @var Url $currentEpisodeUrlProperty */
        $currentEpisodeUrlProperty = $this->page->properties()->get(self::FIELD_CURRENT_EPISODE_URL);

        $updatedProperty = $currentEpisodeUrlProperty->changeUrl($url);
        $updatedProperties = $this->page->properties()->change(self::FIELD_CURRENT_EPISODE_URL, $updatedProperty);

        $this->page = $this->page->changeProperties($updatedProperties->getAll());

        return $this;
    }

    public function getCurrentEpisodeStatus(): string
    {
        return $this->page->properties()->get(self::FIELD_CURRENT_EPISODE_STATUS)
            ->toArray()['select']['name'];
    }

    public function setCurrentEpisodeStatus(SelectOption $status): self
    {
        /** @var Select $currentEpisodeStatusProperty */
        $currentEpisodeStatusProperty = $this->page->properties()->get(self::FIELD_CURRENT_EPISODE_STATUS);

        $updatedProperty = $currentEpisodeStatusProperty->changeOption($status);
        $updatedProperties = $this->page->properties()->change(self::FIELD_CURRENT_EPISODE_STATUS, $updatedProperty);

        $this->page = $this->page->changeProperties($updatedProperties->getAll());

        return $this;
    }

    public function isMarkedAsNewEpisode(): bool
    {
        return $this->getCurrentEpisodeStatus() === EpisodeStatus::NEW_EPISODE;
    }

    public static function fromApiPage(Page $page): self
    {
        return new self($page);
    }

    public function toApiPage(): Page
    {
        return $this->page;
    }
}
