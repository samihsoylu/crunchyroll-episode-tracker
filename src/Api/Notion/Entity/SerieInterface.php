<?php

declare(strict_types=1);

namespace SamihSoylu\Crunchyroll\Api\Notion\Entity;

use Notion\Databases\Properties\SelectOption;
use Notion\Pages\Page;
use SamihSoylu\Crunchyroll\Api\Notion\Entity\Field\Episode;

interface SerieInterface
{
    public function getName(): string;

    public function getCurrentEpisode(): Episode;

    public function setCurrentEpisode(Episode $currentEpisode): self;

    public function setPreviousEpisode(Episode $previousEpisode): self;

    public function setCurrentEpisodeUrl(string $url): self;

    public function setCurrentEpisodeStatus(SelectOption $status): self;

    public static function fromApiPage(Page $page): self;

    public function toApiPage(): Page;
}
