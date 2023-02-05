<?php declare(strict_types=1);

namespace Abeliani\StringTranslator;

use Abeliani\StringTranslator\Drivers\Client\DriverClient;
use Abeliani\StringTranslator\Drivers\Client\DriverTranslationClientInterface;
use Abeliani\StringTranslator\Drivers\Driver\DriverInterface;
use GuzzleHttp\Client;
use Psr\Http\Client\ClientInterface;

class StringTranslator
{
    public static function translate(string $text, string $from, string $to, DriverInterface $driver): ?string
    {
        return self::prepareTranslator($text, $from, $driver)->translate($to);
    }

    public static function prepareTranslator(
        string $text,
        string $from,
        DriverInterface $driver,
        ClientInterface $client = null
    ): DriverInterface {
        return $driver->init($client ?? new Client())->setTranslatable($text, $from);
    }
}
