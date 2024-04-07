<?php declare(strict_types=1);

namespace Abeliani\StringTranslator\Tests;

use Abeliani\StringTranslator\Drivers\MyMemory;
use Abeliani\StringTranslator\Drivers\Translit;
use Abeliani\StringTranslator\TextTranslator;
use Codeception\Test\Unit;
use GuzzleHttp\Client;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class DriverChainTest extends Unit
{
    private Client $client;
    private RequestInterface $request;
    private ResponseInterface $response;

    protected function setUp(): void
    {
        $uri =  $this->createStub(UriInterface::class);
        $uri->method('withScheme')
            ->willReturnSelf();
        $uri->method('withHost')
            ->willReturnSelf();
        $uri->method('withPath')
            ->willReturnSelf();
        $uri->method('withQuery')
            ->willReturnSelf();

        $this->request = $this->createStub(RequestInterface::class);
        $this->request->method('getUri')
            ->willReturn($uri);
        $this->request->method('withUri')
            ->willReturnSelf();
        $this->request->method('withMethod')
            ->willReturnSelf();

        $this->client = $this->createStub(Client::class);
        $this->response = $this->createStub(ResponseInterface::class);

        parent::setUp();
    }

    public function testNextDriverUsed(): void
    {
        $this->response->method('getStatusCode')
            ->willReturn(401);

        $this->client->method('sendRequest')
            ->willReturn($this->response);

        $chain = (new MyMemory('', $this->client, $this->request, new Translit));
        $translator = new TextTranslator($chain);
        $translator->setSource('бар', 'ru');

        $this->assertEquals('bar', $translator->translate('en'));
    }

    public function testFirstDriverUsed(): void
    {
        $this->response->method('getStatusCode')
            ->willReturn(200);

        $body = $this->createStub(StreamInterface::class);
        $body->method('getContents')
            ->willReturn('{"responseData":{"translatedText":"Привет Мир!","match":1}}');

        $this->response->method('getBody')
            ->willReturn($body);

        $this->client->method('sendRequest')
            ->willReturn($this->response);

        $chain = (new MyMemory('', $this->client, $this->request, new Translit));
        $translator = new TextTranslator($chain);
        $translator->setSource('Hello World!', 'en');

        $this->assertEquals('Привет Мир!', $translator->translate('ru'));
    }
}
