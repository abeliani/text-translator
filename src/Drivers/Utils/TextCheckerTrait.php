<?php

declare(strict_types=1);

namespace Abeliani\StringTranslator\Drivers\Utils;

trait TextCheckerTrait
{
    protected function isEmpty(string $text): bool
    {
        return empty(trim($text));
    }

    protected function containsWord(string $text): bool
    {
        return preg_match('~[[:alpha:]]+~u', $text) > 0;
    }
}
