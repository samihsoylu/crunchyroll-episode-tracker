<?php

declare(strict_types=1);

use SamihSoylu\CrunchyrollSyncer\Api\Notion\Entity\Field\Episode;

it('should be instantiated and converted to a string', function () {
    $episode = new Episode(1, 1);
    expect((string) $episode)->toBe('S01E01');
});

it('should be instantiated from a string', function () {
    $episode = Episode::fromString('S01E01');
    expect((string) $episode)->toBe('S01E01');
});

it('should throw an exception for invalid string formats', function () {
    Episode::fromString('invalidString');
})->throws(InvalidArgumentException::class);

it('should determine if an episode is older', function () {
    $episode = new Episode(1, 1);

    expect($episode->isOldEpisode(1, 2))->toBeTrue()
        ->and($episode->isOldEpisode(2, 1))->toBeTrue()
        ->and($episode->isOldEpisode(1, 1))->toBeFalse()
        ->and($episode->isOldEpisode(0, 1))->toBeFalse();
});

it('it should handle double-digit seasons and episodes', function () {
    $episode = new Episode(12, 46);
    expect((string) $episode)->toBe('S12E46');

    $newEpisode = Episode::fromString('S12E46');
    expect((string) $newEpisode)->toBe('S12E46');
});

it('should update and return the season number', function () {
    $episode = new Episode(1, 1);
    $episode->setSeasonNumber(2);

    expect($episode->getSeasonNumber())->toBe(2);
});

it('should update and return the episode number', function () {
    $episode = new Episode(1, 1);
    $episode->setEpisodeNumber(2);

    expect($episode->getEpisodeNumber())->toBe(2);
});

it('should handle edge cases in isOldEpisode method', function () {
    $episode = new Episode(1, 1);

    expect($episode->isOldEpisode(0, 0))->toBeFalse();
});

it('should determine if is behind multiple episodes', function () {
    $episode = new Episode(2, 1);

    expect($episode->isBehindMultipleEpisodes(2, 2))->toBeFalse()
        ->and($episode->isBehindMultipleEpisodes(2, 3))->toBeTrue()
        ->and($episode->isBehindMultipleEpisodes(3, 1))->toBeTrue()
        ->and($episode->isBehindMultipleEpisodes(1, 1))->toBeFalse();
});
