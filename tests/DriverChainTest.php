<?php declare(strict_types=1);

namespace Abeliani\StringTranslator\Tests;

use Abeliani\StringTranslator\Drivers\MyMemoryDriver;
use Abeliani\StringTranslator\Drivers\YandexDriver;
use Codeception\Test\Unit;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class DriverChainTest extends Unit
{
    public function testSuccess(): void
    {
        $stream = $this->createStub(StreamInterface::class);
        $stream->method('getContents')
            ->willReturn('{"responseData": {"translatedText": "Bar"}}');

        $response = $this->createStub(ResponseInterface::class);
        $response->method('getBody')
            ->willReturn($stream);
        $response->method('getStatusCode')
            ->willReturn(200);

        $client = $this->createStub(Client::class);
        $client->method('get')
            ->willReturn($response);

        $chain = (new YandexDriver('', new MyMemoryDriver()))
            ->init($client)
            ->setTranslatable('Test', 'gb');

        $this->assertEquals('Bar', $chain->translate('en'));
    }
}
