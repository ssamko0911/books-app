<?php declare(strict_types=1);

namespace App\Utils;

abstract class BaseSanitizer
{
    abstract protected static function sanitize(array $data): array;
}
