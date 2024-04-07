<?php

declare(strict_types=1);

namespace Abeliani\StringTranslator\Drivers\Core;

abstract class Driver implements DriverInterface
{
    public function __construct(protected ?DriverInterface $successor = null)
    {
    }

    /**
     * @throws DriverException
     */
    public function handle(string $text, string $from, string $to): string
    {
        $processed = $this->processing($text, $from, $to);

        if (empty($processed) && $this->successor !== null) {
            $processed = $this->successor->processing($text, $from, $to);
        }

        if (!isset($processed)) {
            throw new DriverException('Translate request processing filed.');
        }

        return $processed;
    }

    abstract protected function processing(string $text, string $from, string $to): ?string;
}
