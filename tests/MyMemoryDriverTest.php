<?php declare(strict_types=1);

namespace Abeliani\StringTranslator\Tests;

use Abeliani\StringTranslator\Drivers\MyMemoryDriver;
use Codeception\Specify;
use Codeception\Test\Unit;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class MyMemoryDriverTest extends Unit
{
    use Specify;

    public function testSuccess(): void
    {
        $stream = $this->createStub(StreamInterface::class);
        $stream->method('getContents')
            ->willReturn('{"responseData": {"translatedText": "Bar."}}');

        $response = $this->createStub(ResponseInterface::class);
        $response->method('getBody')
            ->willReturn($stream);
        $response->method('getStatusCode')
            ->willReturn(200);

        $client = $this->createStub(Client::class);
        $client->method('get')
            ->willReturn($response);

        $this->specify('Clear dot after translated text', function () use ($client) {
            $driver = (new MyMemoryDriver())
                ->init($client)
                ->setTranslatable('Foo', 'tr');

            $this->assertEquals('Bar', $driver->translate('en'));
        });

        $this->specify('Nothing to translate', function () use ($client) {
            $driver = (new MyMemoryDriver())
                ->init($client)
                ->setTranslatable('123', 'tr');

            $this->assertEquals('123', $driver->translate('en'));
        });
    }

    public function testFail(): void
    {
        $stream = $this->createStub(StreamInterface::class);
        $stream->method('getContents')
            ->willReturn('{"responseData": {"wrongResponse": "Error"}}');

        $response = $this->createStub(ResponseInterface::class);
        $response->method('getBody')
            ->willReturn($stream);

        $response->method('getStatusCode')
            ->willReturn(200);

        $client = $this->createStub(Client::class);
        $client->method('get')
            ->willReturn($response);

        $client = $this->createStub(Client::class);
        $driver = (new MyMemoryDriver())
            ->init($client)
            ->setTranslatable('Foo', 'tr');

        $this->assertNull($driver->translate('en'));
    }

    public function testWrongResponse(): void
    {
        $stream = $this->createStub(StreamInterface::class);
        $stream->method('getContents')
            ->willReturn('{"responseData": {"wrongResponse": "Error"}}');

        $response = $this->createStub(ResponseInterface::class);
        $response->method('getBody')
            ->willReturn($stream);

        $response->method('getStatusCode')
            ->willReturn(200);

        $client = $this->createStub(Client::class);
        $client->method('get')
            ->willReturn($response);

        $driver = (new MyMemoryDriver())
            ->init($client)
            ->setTranslatable('Foo', 'tr');

        $this->assertNull($driver->translate('en'));
    }

    public function testErrorStatusCode(): void
    {
        $response = $this->createStub(ResponseInterface::class);
        $response->method('getStatusCode')
            ->willReturn(500);

        $client = $this->createStub(Client::class);
        $client->method('get')
            ->willReturn($response);

        $driver = (new MyMemoryDriver())
            ->init($client)
            ->setTranslatable('Foo', 'tr');

        $this->assertNull($driver->translate('en'));
    }
}
