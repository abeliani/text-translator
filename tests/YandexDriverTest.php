<?php declare(strict_types=1);

namespace Abeliani\StringTranslator\Tests;

use Abeliani\StringTranslator\Drivers\Client\DriverTranslationClientInterface;
use Abeliani\StringTranslator\Drivers\YandexDriver;
use Codeception\Specify;
use Codeception\Test\Unit;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class YandexDriverTest extends Unit
{
    use Specify;

   public function testSuccess(): void
    {
        $stream = $this->createStub(StreamInterface::class);
        $stream->method('getContents')
            ->willReturn('{"code": 200, "lang": "ru-en", "text": ["Test"]}');

        $response = $this->createStub(ResponseInterface::class);
        $response->method('getBody')
            ->willReturn($stream);
        $response->method('getStatusCode')
            ->willReturn(200);

        $client = $this->createStub(Client::class);
        $client->method('get')
            ->willReturn($response);

        $driver = (new YandexDriver(''))
            ->init($client)
            ->setTranslatable('Test', 'ru');

        $this->assertEquals('Test', $driver->translate('en'));
    }

    public function testWrongResponse(): void
    {
        $stream = $this->createStub(StreamInterface::class);
        $stream->method('getContents')
            ->willReturn('{"result": "Test"}');

        $response = $this->createStub(ResponseInterface::class);
        $response->method('getBody')
            ->willReturn($stream);
        $response->method('getStatusCode')
            ->willReturn(200);

        $client = $this->createStub(Client::class);
        $client->method('get')
            ->willReturn($response);

        $driver = (new YandexDriver(''))
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

        $driver = (new YandexDriver(''))
            ->init($client)
            ->setTranslatable('Тест', 'ru');

        $this->assertNull($driver->translate('en'));
    }
}
