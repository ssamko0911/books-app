<?php declare(strict_types=1);

namespace App\Utils;

abstract class BaseSanitizer
{
    abstract protected function sanitize(array $data): array;
}
