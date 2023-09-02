<?php

declare(strict_types=1);

namespace SamihSoylu\CrunchyrollSyncer\Tests\Framework\TestDouble\Spy;

use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;

final class SpyLogger extends AbstractLogger implements LoggerInterface
{
    private array $logs = [];

    public function log($level, \Stringable|string $message, array $context = []): void
    {
        $this->logs[] = $message;
    }

    /**
     * @return string[]
     */
    public function getLogs(): array
    {
        return $this->logs;
    }
}
