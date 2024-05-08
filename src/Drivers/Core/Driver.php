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

namespace Abeliani\StringTranslator\Drivers\Core;

abstract class Driver implements DriverInterface
{
    /**
     * Abstract Driver to implement chain of responsibility
     *
     * @param DriverInterface|null $successor
     */
    public function __construct(protected readonly ?DriverInterface $successor = null)
    {
    }

    /**
     * @throws DriverException|\Exception
     */
    public function handle(string $text, string $from, string $to): string
    {
        try {
            $processed = $this->processing($text, $from, $to);
        } catch (DriverException $e) {
            if (!$this->hasNextDriver()) {
                throw $e;
            }
        }

        if (empty($processed) && $this->hasNextDriver()) {
            $processed = $this->successor->processing($text, $from, $to);
        }

        if (!isset($processed)) {
            throw new DriverException('Translate request processing filed.');
        }

        return $processed;
    }

    /**
     * @throws DriverException|\Exception
     */
    abstract protected function processing(string $text, string $from, string $to): ?string;

    private function hasNextDriver(): bool
    {
        return $this->successor !== null;
    }
}
