<?php

declare(strict_types=1);

namespace SamihSoylu\CrunchyrollSyncer\Util;

use UnexpectedValueException;

final class Assert
{
    /**
     * @phpstan-assert !null $value
     * @psalm-assert !null $value
     *
     * @throws UnexpectedValueException
     */
    public static function notNull(mixed $value, string $message): void
    {
        if (is_null($value)) {
            throw new UnexpectedValueException($message);
        }
    }
}
