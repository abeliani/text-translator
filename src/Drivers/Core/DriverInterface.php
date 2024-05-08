<?php

/**
 * This file is part of the StringTranslator Project.
 *
 * @package     StringTranslator
 * @author      Anatolii Belianin <belianianatoli@gmail.com>
 * @license     See LICENSE.md for license information
 * @link        https://github.com/abeliani/string-translator
 */

namespace Abeliani\StringTranslator\Drivers\Core;

interface DriverInterface
{
    public function handle(string $text, string $from, string $to): string;
}
