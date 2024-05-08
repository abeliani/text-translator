<?php

/**
 * This file is part of the StringTranslator Project.
 *
 * @package     StringTranslator
 * @author      Anatolii Belianin <belianianatoli@gmail.com>
 * @license     See LICENSE.md for license information
 * @link        https://github.com/abeliani/string-translator
 */

declare(strict_types=1);

namespace Abeliani\StringTranslator\Tests;

use Abeliani\StringTranslator\Drivers\Core\DriverInterface;
use Abeliani\StringTranslator\TextTranslator;
use Codeception\Test\Unit;

class DriverTest extends Unit
{
    public function testSuccess(): void
    {
        $driver = $this->createStub(DriverInterface::class);
        $driver->method('handle')->willReturn('Foo');
        $translator = new TextTranslator($driver);

        $this->assertEquals('Foo', $translator->setSource('foo', 'so')->translate('me'));
    }
}
