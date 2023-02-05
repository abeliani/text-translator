<?php declare(strict_types=1);

namespace Abeliani\StringTranslator\Drivers;

use Abeliani\StringTranslator\Drivers\Driver\AbstractTranslationDriver;
use Abeliani\StringTranslator\Drivers\Driver\DriverException;
use GuzzleHttp\Exception\GuzzleException;

class MyMemoryDriver extends AbstractTranslationDriver
{
    public function __construct(?AbstractTranslationDriver $driver = null)
    {
        parent::__construct($driver);
    }

    protected function processing(string $to): ?string
    {
        $url = sprintf(
            'https://mymemory.translated.net/api/get?q=%s&langpair=%s|%s',
            urlencode($this->inputText),
            $this->from,
            $to
        );

        try {
            $response = $this->getHttpClient()->get($url);
        } catch (GuzzleException $e) {
            throw new DriverException($e->getMessage());
        }

        if ($response->getStatusCode() == 200) {
            $response = json_decode($response->getBody()->getContents());

            if (!isset($response->responseData->translatedText)) {
                throw new DriverException('Wrong response structure');
            }

            return $this->translatedTextNormalize($response->responseData->translatedText);
        }

        return null;
    }

    private function translatedTextNormalize(string $translated): string
    {
        if (!str_ends_with($this->inputText, '.')) {
            return rtrim($translated, '.');
        }

        return $translated;
    }
}
