<?php

/**
 * This file is part of the TextTranslator Project.
 *
 * @package     TextTranslator
 * @author      Anatolii Belianin <belianianatoli@gmail.com>
 * @license     See LICENSE.md.md for license information
 * @link        https://github.com/abeliani/text-translator
 */

declare(strict_types=1);

namespace Abeliani\StringTranslator;

use Abeliani\StringTranslator\Drivers\Core\DriverException;

final class TextTranslator extends Translator
{
    /**
     * @param string $to target language code
     *
     * @return string translated text
     * @throws DriverException
     */
    public function translate(string $to): string
    {
        if (!isset($this->text) || !isset($this->lang)) {
            throw new DriverException('Please, set source text and it lang');
        }

        return $this->driver->handle($this->text, $this->lang, $to);
    }
}
