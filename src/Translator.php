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

use Abeliani\StringTranslator\Drivers\Core\DriverInterface;

abstract class Translator
{
    protected string $text, $lang;

    /**
     * Helps to use a driver non directly and translate a text to different language.
     *
     *      $translator = new TextTranslator($driver);
     *      $translator->setSource('some text', 'en');
     *
     *      print $translator->translate('ru');
     *      print $translator->translate('ge');
     *
     * @see
     * @param DriverInterface $driver
     */
    public function __construct(protected DriverInterface $driver)
    {
    }

    public function setSource(string $text, string $lang): self
    {
        $this->text = $text;
        $this->lang = $lang;

        return $this;
    }

    abstract function translate(string $to): string;
}
