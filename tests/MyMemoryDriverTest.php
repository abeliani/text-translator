<?php declare(strict_types=1);

namespace Abeliani\StringTranslator\Tests;

use Abeliani\StringTranslator\Drivers\Core\DriverException;
use Abeliani\StringTranslator\Drivers\MyMemory;
use Codeception\Specify;
use Codeception\Test\Unit;
use GuzzleHttp\Client;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class MyMemoryDriverTest extends Unit
{
    use Specify;

    private Client $client;
    private StreamInterface $stream;
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
        $this->stream = $this->createStub(StreamInterface::class);
        $this->response = $this->createStub(ResponseInterface::class);

        parent::setUp();
    }

    public function testSuccess(): void
    {
        $this->stream->method('getContents')
            ->willReturn('{"responseData": {"translatedText": "Test."}, "responseStatus": 200}');

        $this->response->method('getBody')
            ->willReturn($this->stream);
        $this->response->method('getStatusCode')
            ->willReturn(200);

        $this->client->method('sendRequest')
            ->willReturn($this->response);

        $this->specify('Clear dot after translated text', function () {
            $result = (new MyMemory('', $this->client, $this->request))
                ->handle('Тест', 'ru', 'en');

            $this->assertEquals('Test', $result);
        });

        $this->specify('Keep dot after translated text', function () {
            $result = (new MyMemory('', $this->client, $this->request))
                ->handle('Тест.', 'ru', 'en');

            $this->assertEquals('Test.', $result);
        });

        $this->specify('Nothing to translate', function () {
            $result = (new MyMemory('', $this->client, $this->request))
                ->handle('123', 'ru', 'en');

            $this->assertEquals('123', $result);
        });
    }

    public function testFail(): void
    {
        $this->expectException(DriverException::class);

        $this->stream->method('getContents')
            ->willReturn('{"responseData": {"wrongResponse": "Error"}}');

        $this->response->method('getBody')
            ->willReturn($this->stream);
        $this->response->method('getStatusCode')
            ->willReturn(200);

        $this->client->method('sendRequest')
            ->willReturn($this->response);

        (new MyMemory('', $this->client, $this->request))
            ->handle('Foo', 'tr', 'ge');
    }

    public function testErrorStatusCode(): void
    {
        $this->expectException(DriverException::class);

        $this->response->method('getBody')
            ->willReturn($this->stream);
        $this->response->method('getStatusCode')
            ->willReturn(500);

        $this->client->method('sendRequest')
            ->willReturn($this->response);

        (new MyMemory('', $this->client, $this->request))
            ->handle('Foo', 'tr', 'ge');
    }
}
