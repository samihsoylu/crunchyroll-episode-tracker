<?php

declare(strict_types=1);

use SamihSoylu\Crunchyroll\Api\Crunchyroll\CrunchyrollApiClient;
use SamihSoylu\Crunchyroll\Api\Crunchyroll\Entity\AnimeEpisode;
use SamihSoylu\Crunchyroll\Console\Crunchyroll\Anime\LatestCommand;
use SamihSoylu\Crunchyroll\Tests\Framework\Builder\AnimeEpisodeBuilder;
use SamihSoylu\Crunchyroll\Tests\Framework\TestDouble\Spy\SpyAnimeEpisodeRepository;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

it('should display latest episodes correctly', function () {
    $repositorySpy = buildSpyRepositoryWithLatestEpisodes();
    $commandTester = initializeCommandTester($repositorySpy);

    $commandTester->execute([]);
    $output = $commandTester->getDisplay();

    validateOutputContainsEpisodeDetails($output, [
        'Naruto' => ['season' => 2, 'episode' => 21, 'date' => '28-08-2023 03:00'],
        'One Piece' => ['season' => 22, 'episode' => 1000, 'date' => '28-08-2023 03:05'],
    ]);
});

function buildSpyRepositoryWithLatestEpisodes(): SpyAnimeEpisodeRepository
{
    $repositorySpy = new SpyAnimeEpisodeRepository();
    $repositorySpy->setLatestEpisodes(
        buildAnimeEpisode('Naruto', 2, 21, '28-08-2023 03:00'),
        buildAnimeEpisode('One Piece', 22, 1000, '28-08-2023 03:05')
    );
    return $repositorySpy;
}

function buildAnimeEpisode(string $title, int $season, int $episode, string $date): AnimeEpisode
{
    return (new AnimeEpisodeBuilder())
        ->withSeriesTitle($title)
        ->withSeasonNumber($season)
        ->withEpisodeNumber($episode)
        ->withPublishedDate($date)
        ->build();
}

function initializeCommandTester(SpyAnimeEpisodeRepository $repositorySpy): CommandTester
{
    $application = new Application();
    $command = new LatestCommand(new CrunchyrollApiClient($repositorySpy));
    $application->add($command);
    return new CommandTester($application->find('cr:anime:latest'));
}

function validateOutputContainsEpisodeDetails(string $output, array $episodeDetails): void
{
    foreach ($episodeDetails as $title => $details) {
        expect($output)->toContain($title)
            ->and($output)->toContain((string) $details['season'])
            ->and($output)->toContain((string) $details['episode'])
            ->and($output)->toContain($details['date']);
    }
}
