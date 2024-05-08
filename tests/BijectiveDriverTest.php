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

use Abeliani\StringTranslator\Drivers\Bijective;
use Abeliani\StringTranslator\TextTranslator;
use Codeception\Test\Unit;

class BijectiveDriverTest extends Unit
{
    private TextTranslator $translator;

    protected function _setUp(): void
    {
        $this->translator = new TextTranslator(new Bijective);
        parent::_setUp();
    }

    public function testRuEnSuccess(): void
    {
        $this->translator->setSource('Борщ', 'ru');
        $this->assertEquals('Borw_', $this->translator->translate('en'));

        $this->translator->setSource('Цыкатуха', 'ru');
        $this->assertEquals('C6katuha', $this->translator->translate('en'));

        $this->translator->setSource('Ночь', 'ru');
        $this->assertEquals('No4_', $this->translator->translate('en'));

        $this->translator->setSource('Семёнишна', 'ru');
        $this->assertEquals('Sem~eniwna', $this->translator->translate('en'));

        $this->translator->setSource('Мэр', 'ru');
        $this->assertEquals('M:er', $this->translator->translate('en'));

        $this->translator->setSource('Подъезд', 'ru');
        $this->assertEquals('Pod^ezd', $this->translator->translate('en'));

        $this->translator->setSource('Дождь', 'ru');
        $this->assertEquals('Dojd_', $this->translator->translate('en'));

        $this->translator->setSource('Сцхоол', 'ru');
        $this->assertEquals('School', $this->translator->translate('en'));
    }

    public function testEnRuSuccess(): void
    {
        $this->translator->setSource('Borw_', 'en');
        $this->assertEquals('Борщ', $this->translator->translate('ru'));

        $this->translator->setSource('C6katuha', 'en');
        $this->assertEquals('Цыкатуха', $this->translator->translate('ru'));

        $this->translator->setSource('No4_', 'en');
        $this->assertEquals('Ночь', $this->translator->translate('ru'));

        $this->translator->setSource('Sem~eniwna', 'en');
        $this->assertEquals('Семёнишна', $this->translator->translate('ru'));

        $this->translator->setSource('M:er', 'en');
        $this->assertEquals('Мэр', $this->translator->translate('ru'));

        $this->translator->setSource('Pod^ezd', 'en');
        $this->assertEquals('Подъезд', $this->translator->translate('ru'));

        $this->translator->setSource('Dojd_', 'en');
        $this->assertEquals('Дождь', $this->translator->translate('ru'));

        $this->translator->setSource('School', 'en');
        $this->assertEquals('Сцхоол', $this->translator->translate('ru'));
    }

    public function testNothingTranslateSuccess(): void
    {
        $this->translator->setSource('  ', 'en');
        $this->assertEquals('  ', $this->translator->translate('ru'));
    }

    public function testCustomDictSuccess(): void
    {
        $driver = new Bijective(['ru-num' => ['д' => '1', 'х' => '2', 'г' => '3']]);
        $translator = new TextTranslator($driver);

        $translator->setSource('123', 'num');
        $this->assertEquals('дхг', $translator->translate('ru'));
    }

    public function testWrongPairFail(): void
    {
        $this->expectExceptionMessage('There is no dict for `num-en` pair');

        $driver = new Bijective(['ru-num' => []]);
        $translator = new TextTranslator($driver);

        $translator->setSource('', 'num');
        $this->assertEquals('', $translator->translate('en'));
    }

    public function testWrongFromLangFail(): void
    {
        $this->expectExceptionMessage('Direction lang from `zoo` is not available');

        $this->translator->setSource('', 'zoo');
        $this->translator->translate('boo');
    }

    public function testWrongToLangFail(): void
    {
        $this->expectExceptionMessage('Direction lang to `boo` is not available');

        $this->translator->setSource('текст', 'ru');
        $this->translator->translate('boo');
    }
}
