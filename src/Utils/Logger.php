<?php declare(strict_types=1);

namespace App\Utils;

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger as MonologLogger;
class Logger
{
    private static ?MonologLogger $logger = null;

    public static function getLogger(): MonologLogger
    {
        if (null === self::$logger) {
            self::$logger = new MonologLogger('app-book-recommendation');

            self::$logger->pushHandler(new StreamHandler(
                __DIR__ . '/../../var/log/app-book-recommendation.log',
                Level::Debug
                )
            );
        }

        return self::$logger;
    }
}
