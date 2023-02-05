<?php declare(strict_types=1);

namespace Abeliani\StringTranslator\Drivers;

use Abeliani\StringTranslator\Drivers\Driver\AbstractTranslationDriver;
use Abeliani\StringTranslator\Drivers\Driver\DriverException;
use GuzzleHttp\Exception\GuzzleException;

class YandexDriver extends AbstractTranslationDriver
{
    public function __construct(private readonly string $apiKey, ?AbstractTranslationDriver $driver = null)
    {
        parent::__construct($driver);
    }

    protected function processing(string $to): ?string
    {
        $url = sprintf(
            'https://translate.yandex.net/api/v1.5/tr.json/translate?key=%s&text=%s&lang=%s-%s',
            $this->apiKey,
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

            if (!isset($response->text[0])) {
                throw new DriverException('Wrong response structure');
            }

            return $response->text[0];
        }

        return null;
    }
}
