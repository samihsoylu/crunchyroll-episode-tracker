<?php

declare(strict_types=1);

namespace SamihSoylu\CrunchyrollSyncer\Tests\Framework\Core;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

final class TestKit
{
    public function __construct(
        private NotionTestHelper $notionTestHelper,
        private CrunchyrollTestHelper $crunchyrollTestHelper,
        private ActionTestHelper $actionTestHelper,
    ) {
    }

    public function crunchyroll(): CrunchyrollTestHelper
    {
        return $this->crunchyrollTestHelper;
    }

    public function notion(): NotionTestHelper
    {
        return $this->notionTestHelper;
    }

    public function action(): ActionTestHelper
    {
        return $this->actionTestHelper;
    }

    public function getTestDoubleDir(): string
    {
        return dirname(__DIR__) . '/TestDouble';
    }

    public function executeConsoleCommand(Command $command, array $args = []): CommandTester
    {
        $console = new Application();
        $console->add($command);

        $tester = new CommandTester($console->find($command->getName()));
        $tester->execute($args);

        return $tester;
    }
}
