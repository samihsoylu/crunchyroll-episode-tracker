<?php

declare(strict_types=1);

namespace SamihSoylu\Crunchyroll\Console\Crunchyroll\Anime;

use SamihSoylu\Crunchyroll\Api\Crunchyroll\CrunchyrollApiClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class LatestCommand extends Command
{
    public function __construct(
        private CrunchyrollApiClient $crunchyroll,
    ) {
        parent::__construct();
    }

    public function configure()
    {
        $this->setName('cr:anime:latest')
            ->setDescription('List latest episodes from Crunchyroll');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $episodes = $this->crunchyroll->animeEpisode()->getLatestEpisodes();

        $table = new Table($output);
        $table->setHeaders(['Title', 'Season', 'Episode', 'Published']);

        foreach ($episodes as $episode) {
            $table->addRow([
                $episode->getSeriesTitle(),
                $episode->getSeasonNumber(),
                $episode->getEpisodeNumber(),
                $episode->getPublishedDate()->format("d-m-Y h:i"),
            ]);
        }

        $table->render();

        return self::SUCCESS;
    }
}
