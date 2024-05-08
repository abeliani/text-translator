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

namespace Abeliani\StringTranslator\Drivers;

use Abeliani\StringTranslator\Drivers\Core\DriverException;
use Abeliani\StringTranslator\Drivers\Utils\MapDictUtils;
use Abeliani\StringTranslator\Drivers\Utils\TextCheckerTrait;

final class Translit extends Core\Driver
{
    use MapDictUtils, TextCheckerTrait;

    private array $langMap;

    /**
     * Transliterate text by map
     *
     *      Борщ -> Borsch
     *      Ночь -> Noch
     *
     * @param array|null $langMap - custom translit map
     * @param Core\DriverInterface|null $successor - instance of next driver if current fail
     */
    public function __construct(
        ?array $langMap = null,
        ?Core\DriverInterface $successor = null,
    ) {
        if (!isset($this->langMap)) {
            $this->langMap = require __DIR__
                . DIRECTORY_SEPARATOR
                . 'Dicts'
                . DIRECTORY_SEPARATOR
                . 'translit.php';

            if (!empty($langMap)) {
                foreach ($langMap as $pair => $map) {
                    $this->langMap[$pair] = $map;
                }
            }

            $this->langMap = $this->addUpperCase($this->langMap);
        }

        parent::__construct($successor);
    }

    /**
     * @throws DriverException
     */
    protected function processing(string $text, string $from, string $to): string
    {
        if ($this->isEmpty($text) || !$this->containsWord($text)) {
            return $text;
        }

        if (!$map = $this->langMap[sprintf('%s-%s', $from, $to)] ?? null) {
            throw new Core\DriverException(sprintf('There is no dict for `%s-%s` pair', $from, $to));
        }

        return strtr($text, $map);
    }
}
