<?php

declare(strict_types=1);

namespace SamihSoylu\CrunchyrollSyncer\Tests\Framework\Core;

use Notion\Databases\Properties\SelectOption;
use SamihSoylu\CrunchyrollSyncer\Api\Notion\Entity\Field\Episode;
use SamihSoylu\CrunchyrollSyncer\Tests\Framework\Builder\AnimeEpisodeBuilder;
use SamihSoylu\CrunchyrollSyncer\Tests\Framework\Builder\FakeSerieBuilder;
use SamihSoylu\CrunchyrollSyncer\Tests\Framework\TestDouble\Fake\FakeAnimeEpisodeRepository;
use SamihSoylu\CrunchyrollSyncer\Tests\Framework\TestDouble\Spy\SpySeriesRepository;

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
