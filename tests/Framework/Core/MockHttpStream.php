<?php

declare(strict_types=1);

namespace SamihSoylu\Crunchyroll\Tests\Framework\Core;

final class MockHttpStream
{
    public static bool|string $mockResponse;
    private bool $eof = false;
    private bool $readFailed = false;
    public $context = null;

    public function stream_open(string $path, string $mode, int $options, ?string &$opened_path): bool
    {
        $this->eof = false;
        $this->readFailed = (self::$mockResponse === false);
        return !$this->readFailed;
    }

    public function stream_read(int $count): bool|string
    {
        if ($this->eof || $this->readFailed) {
            return false;
        }

        $this->eof = true;
        return self::$mockResponse;
    }

    public function stream_eof(): bool
    {
        return $this->eof || $this->readFailed;
    }

    public function stream_stat(): array
    {
        return [
            'dev' => 0,
            'ino' => 0,
            'mode' => 33206, // regular file + readable + writable
            'nlink' => 0,
            'uid' => 0,
            'gid' => 0,
            'rdev' => 0,
            'size' => isset(self::$mockResponse) ? strlen((string)self::$mockResponse) : 0,
            'atime' => time(),
            'mtime' => time(),
            'ctime' => time(),
            'blksize' => 4096,
            'blocks' => 0
        ];
    }
}