<?php

declare(strict_types=1);

namespace MarjovanLier\StringManipulation\Tests\Unit;

use MarjovanLier\StringManipulation\StringManipulation;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \MarjovanLier\StringManipulation\StringManipulation::utf8Ansi
 */
final class Utf8AnsiTest extends TestCase
{
    /**
     * @var array<string, string>
     */
    private const array UTF8_TO_ANSI_MAP = [
        '\u00c0' => 'À',
        '\u00c1' => 'Á',
        '\u00c2' => 'Â',
        '\u00c3' => 'Ã',
        '\u00c4' => 'Ä',
        '\u00c5' => 'Å',
        '\u00c6' => 'Æ',
        '\u00c7' => 'Ç',
        '\u00c8' => 'È',
        '\u00c9' => 'É',
        '\u00ca' => 'Ê',
        '\u00cb' => 'Ë',
        '\u00cc' => 'Ì',
        '\u00cd' => 'Í',
        '\u00ce' => 'Î',
        '\u00cf' => 'Ï',
        '\u00d1' => 'Ñ',
        '\u00d2' => 'Ò',
        '\u00d3' => 'Ó',
        '\u00d4' => 'Ô',
        '\u00d5' => 'Õ',
        '\u00d6' => 'Ö',
        '\u00d8' => 'Ø',
        '\u00d9' => 'Ù',
        '\u00da' => 'Ú',
        '\u00db' => 'Û',
        '\u00dc' => 'Ü',
        '\u00dd' => 'Ý',
        '\u00df' => 'ß',
        '\u00e0' => 'à',
        '\u00e1' => 'á',
        '\u00e2' => 'â',
        '\u00e3' => 'ã',
        '\u00e4' => 'ä',
        '\u00e5' => 'å',
        '\u00e6' => 'æ',
        '\u00e7' => 'ç',
        '\u00e8' => 'è',
        '\u00e9' => 'é',
        '\u00ea' => 'ê',
        '\u00eb' => 'ë',
        '\u00ec' => 'ì',
        '\u00ed' => 'í',
        '\u00ee' => 'î',
        '\u00ef' => 'ï',
        '\u00f0' => 'ð',
        '\u00f1' => 'ñ',
        '\u00f2' => 'ò',
        '\u00f3' => 'ó',
        '\u00f4' => 'ô',
        '\u00f5' => 'õ',
        '\u00f6' => 'ö',
        '\u00f8' => 'ø',
        '\u00f9' => 'ù',
        '\u00fa' => 'ú',
        '\u00fb' => 'û',
        '\u00fc' => 'ü',
        '\u00fd' => 'ý',
        '\u00ff' => 'ÿ',
    ];


    public function testUtf8Ansi(): void
    {
        // This represents the UTF-8 encoded character 'À'
        $string = '\u00c0';
        $result = StringManipulation::utf8Ansi($string);
        self::assertEquals('À', $result);
    }


    /**
     * Test the utf8Ansi function.
     */
    public function testUtf8AnsiFunction(): void
    {
        foreach (self::UTF8_TO_ANSI_MAP as $utf8 => $ansi) {
            self::assertEquals($ansi, StringManipulation::utf8Ansi($utf8));
        }

        // Test an empty string
        self::assertEquals('', StringManipulation::utf8Ansi(''));

        // Test null input
        self::assertEquals('', StringManipulation::utf8Ansi(null));
    }


    public function testUtf8AnsiWithInvalidCharacter(): void
    {
        // Invalid UTF-8 encoded character
        $string = '\uZZZZ';
        $result = StringManipulation::utf8Ansi($string);
        self::assertEquals($string, $result);
    }
}
