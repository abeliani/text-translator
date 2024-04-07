<?php

declare(strict_types=1);

namespace Abeliani\StringTranslator\Drivers\Utils;

trait MapDictUtils
{
    protected function addUpperCase(array $map): array
    {
        foreach ($map as $pair => $dict) {
            foreach ($dict as $from => $to) {
                $map[$pair][$this->upperStrategy($from)] = $this->upperStrategy($to);
            }
        }

        return $map;
    }

    private function upperStrategy(string $item): string
    {
        return mb_strlen($item) > 1 ? ucfirst($item) : mb_strtoupper($item);
    }
}
