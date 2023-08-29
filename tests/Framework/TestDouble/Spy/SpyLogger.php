<?php

declare(strict_types=1);

namespace SamihSoylu\Crunchyroll\Tests\Framework\TestDouble\Spy;

use Psr\Log\LoggerInterface;

final class SpyLogger implements LoggerInterface
{
    private array $logs = [];

    public function emergency(\Stringable|string $message, array $context = []): void
    {
        $this->logs[] = $message;
    }

    public function alert(\Stringable|string $message, array $context = []): void
    {
        $this->logs[] = $message;
    }

    public function critical(\Stringable|string $message, array $context = []): void
    {
        $this->logs[] = $message;
    }

    public function error(\Stringable|string $message, array $context = []): void
    {
        $this->logs[] = $message;
    }

    public function warning(\Stringable|string $message, array $context = []): void
    {
        $this->logs[] = $message;
    }

    public function notice(\Stringable|string $message, array $context = []): void
    {
        $this->logs[] = $message;
    }

    public function info(\Stringable|string $message, array $context = []): void
    {
        $this->logs[] = $message;
    }

    public function debug(\Stringable|string $message, array $context = []): void
    {
        $this->logs[] = $message;
    }

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
