<?php

declare(strict_types=1);

namespace SamihSoylu\Crunchyroll\Api\Notion\Entity;

use Notion\Databases\Properties\SelectOption;
use Notion\Pages\Page;
use Notion\Pages\Properties\PropertyCollection;
use Notion\Pages\Properties\RichTextProperty;
use Notion\Pages\Properties\Select;
use Notion\Pages\Properties\Url;

final class Serie
{
    private function __construct(
        private Page $page,
    ) {}

    public function getName(): string
    {
        return $this->page->properties()->get('Name')
            ->toArray()['title'][0]['plain_text'];
    }

    /**
     * @return string S01E01
     */
    public function getWatched(): string
    {
        return $this->page->properties()->get('Watched')
            ->toArray()['rich_text'][0]['plain_text'];
    }

    public function setNewEpisodeUrl(string $newEpisodeUrl): self
    {
        /** @var Url $property */
        $property = $this->page->properties()->get('New episode URL');

        $selectedUrl = $property->changeUrl($newEpisodeUrl);
        $properties = $this->page->properties()->change('New episode URL', $selectedUrl);

        $this->page = $this->page->changeProperties($properties->getAll());

        return $this;
    }

    public function setStatus(SelectOption $status): self
    {
        /** @var Select $property */
        $property = $this->page->properties()->get('Status');

        $selectedOption = $property->changeOption($status);
        $properties = $this->page->properties()->change('Status', $selectedOption);
        $this->page = $this->page->changeProperties($properties->getAll());

        return $this;
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