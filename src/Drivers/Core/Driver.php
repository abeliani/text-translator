<?php

declare(strict_types=1);

namespace Abeliani\StringTranslator\Drivers\Core;

abstract class Driver implements DriverInterface
{
    public function __construct(protected ?DriverInterface $successor = null)
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
