<?php

declare(strict_types=1);

namespace SamihSoylu\Crunchyroll\Tests\Framework\Core;

use Notion\Databases\Properties\SelectOption;
use SamihSoylu\Crunchyroll\Api\Notion\Entity\Field\Episode;
use SamihSoylu\Crunchyroll\Tests\Framework\Builder\AnimeEpisodeBuilder;
use SamihSoylu\Crunchyroll\Tests\Framework\Builder\FakeSerieBuilder;
use SamihSoylu\Crunchyroll\Tests\Framework\TestDouble\Fake\FakeAnimeEpisodeRepository;
use SamihSoylu\Crunchyroll\Tests\Framework\TestDouble\Spy\SpySeriesRepository;

final class ActionTestHelper
{
    public function createCrunchyrollToNotionSyncActionSpies(
        string $serieName = 'Naruto',
        int $notionCurrentSeason = 1,
        int $notionCurrentEpisodeNumber = 1,
        ?int $crunchyrollLatestSeason = null,
        ?int $crunchyrollLatestEpisodeNumber = null,
        ?bool $isEmptyNotion = false,
        ?SelectOption $currentEpisodeStatus = null,
    ): array {
        $notionSpy = new SpySeriesRepository();

        if (!$isEmptyNotion) {
            $notionSpy->setAllSeries(
                (new FakeSerieBuilder())
                    ->withName($serieName)
                    ->withCurrentEpisode(new Episode($notionCurrentSeason, $notionCurrentEpisodeNumber))
                    ->withCurrentEpisodeStatus($currentEpisodeStatus)
                    ->build()
            );
        }

        $crunchyrollSpy = new FakeAnimeEpisodeRepository();

        if ($crunchyrollLatestSeason !== null && $crunchyrollLatestEpisodeNumber !== null) {
            $crunchyrollSpy->setLatestEpisodes(
                (new AnimeEpisodeBuilder())
                    ->withSeriesTitle($serieName)
                    ->withSeasonNumber($crunchyrollLatestSeason)
                    ->withEpisodeNumber($crunchyrollLatestEpisodeNumber)
                    ->build()
            );
        }

        return [$notionSpy, $crunchyrollSpy];
    }
}
