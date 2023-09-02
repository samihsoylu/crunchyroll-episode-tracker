<?php

declare(strict_types=1);

namespace SamihSoylu\CrunchyrollSyncer\Tests\Framework\TestDouble\Spy;

use SamihSoylu\CrunchyrollSyncer\Action\ActionInterface;

final class SpyAction implements ActionInterface
{
    private bool $invoked = false;
    private array $args = [];

    public function __invoke(mixed ...$args): void
    {
        $this->invoked = true;
        $this->args = $args;
    }

    public function assertInvoked(): void
    {
        if (!$this->invoked) {
            throw new \RuntimeException('Failed to assert that action was invoked');
        }
    }

    public function assertInvokedWith(mixed ...$args): void
    {
        if ($args !== $this->args) {
            $expected = implode(', ', $args);
            $actual = implode(', ', $this->args);

            throw new \RuntimeException(
                "Failed to assert that action was invoked with [{$expected}] actually invoked with [{$actual}]"
            );
        }
    }
}
