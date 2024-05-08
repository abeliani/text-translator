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
