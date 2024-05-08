<?php

/**
 * This file is part of the StringTranslator Project.
 *
 * @package     StringTranslator
 * @author      Anatolii Belianin <belianianatoli@gmail.com>
 * @license     See LICENSE.md for license information
 * @link        https://github.com/abeliani/string-translator
 */

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
