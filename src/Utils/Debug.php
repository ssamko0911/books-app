<?php declare(strict_types=1);

namespace App\Utils;
class Debug
{
    public static function dd(mixed $vars): void
    {
        echo '<pre>';
        var_dump($vars);
        echo '</pre>';
        die();
    }
}
