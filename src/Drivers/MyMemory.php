<?php

declare(strict_types=1);

namespace Abeliani\StringTranslator\Drivers;

use Abeliani\StringTranslator\Drivers\Utils\TextCheckerTrait;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;

/**
 * key d0cf9a3e0a60f8e1e880
 * @see https://mymemory.translated.net
 */
final class MyMemory extends Core\OnlineDriver
{
    use TextCheckerTrait;

    public function __construct(
        private readonly ?string $apiKey,
        ClientInterface $client,
        RequestInterface $request,
        ?Core\DriverInterface $successor = null,
    ) {
        $uri = $request
            ->getUri()
            ->withScheme(self::SCHEME)
            ->withHost('mymemory.translated.net')
            ->withPath('/api/get');

        parent::__construct($client, $request->withUri($uri), $successor);
    }

    /**
     * @throws ClientExceptionInterface|Core\DriverException|\JsonException
     */
    protected function processing(string $text, string $from, string $to): string
    {
        if (!$this->containsWord($text)) {
            return $text;
        }

        if (!mb_check_encoding($text, 'UTF-8')) {
            throw new Core\DriverException('Text must be UTF-8.');
        }

        if (mb_strlen($text, '8bit') > 500) {
            throw new Core\DriverException('Text max length 500 bytes.');
        }

        $query = sprintf(
            'q=%s%s&langpair=%s|%s', ($this->apiKey ? "key={$this->apiKey}&" : ''), urlencode($text), $from, $to
        );

        $request = $this->request
            ->withMethod(self::METH_GET)
            ->withUri($this->request->getUri()->withQuery($query));

        $response = $this->client->sendRequest($request);

        if ($response->getStatusCode() !== 200) {
            throw new Core\DriverException(
                "Request failed: {$response->getReasonPhrase()}",
                $response->getStatusCode()
            );
        }

        $body = $response->getBody()->getContents();
        $parsedBody = json_decode($body, true,112,JSON_THROW_ON_ERROR);

        if (!isset($parsedBody['responseData']['translatedText'])) {
            throw new Core\DriverException(
                "Failed to parse mymemory response: {$body}",
                500
            );
        }

        if (!str_ends_with($text, '.')) {
            // mymemory is adding last dot any way
            return rtrim($parsedBody['responseData']['translatedText'], '.');
        }

        return $parsedBody['responseData']['translatedText'];
    }
}
