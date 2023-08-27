<?php

declare(strict_types=1);

use Notion\Databases\Properties\SelectOption;
use SamihSoylu\Crunchyroll\Api\Notion\Entity\Option\EpisodeStatus;

it('should return a new episode select option', function () {
    $selectOption = EpisodeStatus::newEpisode();
    expect($selectOption)->toBeInstanceOf(SelectOption::class)
        ->and($selectOption->name)->toBe('New Episode');
});

it('should return a watched select option', function () {
    $selectOption = EpisodeStatus::watched();
    expect($selectOption)->toBeInstanceOf(SelectOption::class)
        ->and($selectOption->name)->toBe('Watched');
});
