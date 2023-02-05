<?php declare(strict_types=1);

namespace Abeliani\StringTranslator\Drivers\Driver;

use Psr\Http\Client\ClientInterface;

abstract class AbstractTranslationDriver implements DriverInterface
{
    protected string $from;
    protected string $inputText;
    private ClientInterface $client;

    private ?AbstractTranslationDriver $driver = null;

    public function __construct(?AbstractTranslationDriver $driver = null)
    {
        $this->driver = $driver;
    }

    public function init(ClientInterface $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function setTranslatable(string $text, string $from): self
    {
        $this->from = $from;
        $this->inputText = $text;

        return $this;
    }

    public function translate(string $to): ?string
    {
        if (!$this->isNeedTranslate()) {
            return $this->inputText;
        }

        try {
            $translated = $this->processing($to);
        } catch (DriverException $e) {
            if ($this->driver !== null) {
                $translated = $this->driver
                    ->init($this->client)
                    ->setTranslatable($this->inputText, $this->from)
                    ->translate($to);
            }
        }

        return $translated ?? null;
    }

    protected function getHttpClient(): ClientInterface
    {
        return $this->client;
    }

    /**
     * @throws DriverException
     */
    abstract protected function processing(string $to): ?string;

    private function isNeedTranslate(): bool
    {
        return preg_match('~[[:alpha:]]+~u', $this->inputText) > 0;
    }
}
