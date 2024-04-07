<?php

declare(strict_types=1);

namespace Abeliani\StringTranslator;

use Abeliani\StringTranslator\Drivers\Core\DriverException;

final class TextTranslator extends Translator
{
    /**
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
