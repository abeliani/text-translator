<?php declare(strict_types=1);

namespace Abeliani\StringTranslator\Tests;

use Abeliani\StringTranslator\Drivers\Driver\DriverInterface;
use Codeception\Test\Unit;
use Abeliani\StringTranslator\Drivers\Driver\AbstractTranslationDriver;
use Abeliani\StringTranslator\StringTranslator;

class StringTranslatorTest extends Unit
{
    public function testSuccess(): void
    {
        $driver = $this->createStub(DriverInterface::class);
        $driver->method('setTranslatable')
            ->willReturnSelf();

        $driver->method('init')
            ->willReturnSelf();

        $driver->method('translate')
            ->willReturn('Foo');

        $prepared = StringTranslator::prepareTranslator('Bar', 'ru', $driver);
        $translator = StringTranslator::translate('Bar', 'tr', 'en', $driver);

        $this->assertEquals('Foo', $translator);
        $this->assertEquals('Foo', $prepared->translate('en'));
    }
}
