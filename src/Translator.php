<?php

declare(strict_types=1);

namespace Abeliani\StringTranslator;

use Abeliani\StringTranslator\Drivers\Core\DriverInterface;

abstract class Translator
{
    protected string $text, $lang;

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
