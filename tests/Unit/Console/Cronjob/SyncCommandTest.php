<?php

declare(strict_types=1);

use SamihSoylu\Crunchyroll\Console\Cronjob\SyncCommand;
use SamihSoylu\Crunchyroll\Cronjob\CronjobInterface;
use SamihSoylu\Crunchyroll\Tests\Framework\TestDouble\Spy\SpyCronjob;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

it('should invoke crunchyroll to notion sync', function () {
    $expectedNotionDatabaseId = 'some-id';
    $spyCronjob = new SpyCronjob();

    $commandTester = initializeSyncCommandTester($spyCronjob, $expectedNotionDatabaseId);
    $commandTester->execute([]);

    expect($spyCronjob->getActualArguments())->toBe([$expectedNotionDatabaseId]);
});


function initializeSyncCommandTester(CronjobInterface $sync, string $notionDatabaseId): CommandTester
{
    $application = new Application();
    $command = new SyncCommand($sync, $notionDatabaseId);
    $application->add($command);

    return new CommandTester($application->find('cronjob:sync'));
}
