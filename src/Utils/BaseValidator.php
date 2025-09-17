<?php declare(strict_types=1);

namespace App\Utils;

abstract class BaseValidator
{
    abstract protected function validate(array $data): array;
}
