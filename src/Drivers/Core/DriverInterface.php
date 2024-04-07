<?php

namespace Abeliani\StringTranslator\Drivers\Core;

interface DriverInterface
{
    public function handle(string $text, string $from, string $to): string;
}
