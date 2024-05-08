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

use Abeliani\StringTranslator\Drivers\Translit;
use Abeliani\StringTranslator\TextTranslator;
use Codeception\Test\Unit;

class TranslitDriverTest extends Unit
{
    private TextTranslator $translator;

    protected function _setUp(): void
    {
        $this->translator = new TextTranslator(new Translit);
        parent::_setUp();
    }

    public function testRuEnSuccess(): void
    {
        $this->translator->setSource('Борщ', 'ru');
        $this->assertEquals('Borsch', $this->translator->translate('en'));

        $this->translator->setSource('Яблоко', 'ru');
        $this->assertEquals('Yabloko', $this->translator->translate('en'));

        $this->translator->setSource('Цыкатуха', 'ru');
        $this->assertEquals('Cikatuha', $this->translator->translate('en'));

        $this->translator->setSource('Ночь', 'ru');
        $this->assertEquals('Noch', $this->translator->translate('en'));

        $this->translator->setSource('Семёнишна', 'ru');
        $this->assertEquals('Semenishna', $this->translator->translate('en'));

        $this->translator->setSource('Мэрия', 'ru');
        $this->assertEquals('Meriya', $this->translator->translate('en'));

        $this->translator->setSource('Подъезд', 'ru');
        $this->assertEquals('Podezd', $this->translator->translate('en'));

        $this->translator->setSource('Дождь', 'ru');
        $this->assertEquals('Dojd', $this->translator->translate('en'));
    }

    public function testEnRuSuccess(): void
    {
        $this->translator->setSource('Code', 'en');
        $this->assertEquals('Коде', $this->translator->translate('ru'));

        $this->translator->setSource('World', 'en');
        $this->assertEquals('Ворлд', $this->translator->translate('ru'));

        $this->translator->setSource('Chocolate', 'en');
        $this->assertEquals('Чоколате', $this->translator->translate('ru'));

        $this->translator->setSource('Night', 'en');
        $this->assertEquals('Нигхт', $this->translator->translate('ru'));

        $this->translator->setSource('Couch', 'en');
        $this->assertEquals('Коуч', $this->translator->translate('ru'));

        $this->translator->setSource('School', 'en');
        $this->assertEquals('Щоол', $this->translator->translate('ru'));

        $this->translator->setSource('quit', 'en');
        $this->assertEquals('кьюуит', $this->translator->translate('ru'));
    }

    public function testNothingTranslateSuccess(): void
    {
        $this->translator->setSource('  ', 'en');
        $this->assertEquals('  ', $this->translator->translate('ru'));

        $this->translator->setSource('123', 'en');
        $this->assertEquals('123', $this->translator->translate('ru'));
    }

    public function testCustomDictNothingTranslateSuccess(): void
    {
        $driver = new Translit(['ru-num' => ['д' => '1', 'х' => '2', 'г' => '3']]);
        $translator = new TextTranslator($driver);

        $translator->setSource('123', 'num');
        $this->assertEquals('123', $translator->translate('ru'));
    }

    public function testWrongPairFail(): void
    {
        $this->expectExceptionMessage('There is no dict for `num-en` pair');

        $driver = new Translit(['ru-num' => []]);
        $translator = new TextTranslator($driver);

        $translator->setSource('tss', 'num');
        $this->assertEquals('tss', $translator->translate('en'));
    }

    public function testWrongFromLangFail(): void
    {
        $this->expectExceptionMessage('There is no dict for `zoo-boo` pair');

        $this->translator->setSource('boo', 'zoo');
        $this->translator->translate('boo');
    }

    public function testWrongToLangFail(): void
    {
        $this->expectExceptionMessage('There is no dict for `ru-boo` pair');

        $this->translator->setSource('текст', 'ru');
        $this->translator->translate('boo');
    }
}
