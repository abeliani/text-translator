<?php

namespace Abeliani\StringTranslator\Drivers\Driver;

use Psr\Http\Client\ClientInterface;

interface DriverInterface
{
    public function translate(string $to): ?string;

    public function setTranslatable(string $text, string $from): DriverInterface;

    public function init(ClientInterface $client): DriverInterface;
}