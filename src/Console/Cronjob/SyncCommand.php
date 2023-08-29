<?php

declare(strict_types=1);

namespace SamihSoylu\Crunchyroll\Console\Cronjob;

use SamihSoylu\Crunchyroll\Cronjob\CronjobInterface;
use SamihSoylu\Crunchyroll\Cronjob\CrunchyrollToNotionSync;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class SyncCommand extends Command
{
    public function __construct(
        private CronjobInterface $syncer,
        private string $notionDatabaseId,
    ) {
        parent::__construct();
    }

    public function configure()
    {
        $this->setName('cronjob:sync')
            ->setDescription('Sync status updates to Notion');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var CrunchyrollToNotionSync $syncer */
        $syncer = $this->syncer;

        $syncer->__invoke($this->notionDatabaseId);

        return self::SUCCESS;
    }
}
