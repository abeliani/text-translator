<?php

declare(strict_types=1);

namespace Abeliani\StringTranslator\Drivers;

use Abeliani\StringTranslator\Drivers\Core\DriverException;
use Abeliani\StringTranslator\Drivers\Utils\TextCheckerTrait;

class Bijective extends Core\Driver
{
    use TextCheckerTrait;

    public const RU = 'ru';
    public const EN = 'en';

    private array $langMap;

    private array $availableLangs = [
        self::RU,
        self::EN,
    ];

    public function __construct(
        ?array $langMap = null,
        private readonly bool $onlyWordsAllow = false,
        ?Core\DriverInterface $successor = null
    ) {
        if (!isset($this->langMap)) {
            $this->langMap = require __DIR__
                . DIRECTORY_SEPARATOR
                . 'Dicts'
                . DIRECTORY_SEPARATOR
                . 'bijective.php';
        }

        if (!empty($langMap)) {
            foreach ($langMap as $pair => $map) {
                $this->langMap[$pair] = $map;
                $this->availableLangs = array_unique(array_merge(explode('-', $pair), $this->availableLangs));
            }
        }

        parent::__construct($successor);
    }

    /**
     * @throws DriverException
     */
    protected function processing(string $text, string $from, string $to): string
    {
        if (!in_array($from, $this->availableLangs)) {
            throw new Core\DriverException(sprintf('Direction lang from `%s` is not available', $from));
        }

        if (!in_array($to, $this->availableLangs)) {
            throw new Core\DriverException(sprintf('Direction lang to `%s` is not available', $to));
        }

        if ($this->onlyWordsAllow && ($this->isEmpty($text) || !$this->containsWord($text))) {
            return $text;
        }

        $translitPair = [$from, $to];
        foreach ($this->langMap as $langPair => $map) {
            if (!empty(array_diff($translitPair, explode('-', $langPair)))) {
                continue;
            }
            if (!str_starts_with($langPair, $from)) {
                $map = array_flip($map);
            }

            return strtr($text, $map);
        }

        throw new Core\DriverException(sprintf('There is no dict for `%s-%s` pair', $from, $to));
    }
}
