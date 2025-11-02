<?php

declare(strict_types=1);

namespace MarjovanLier\StringManipulation\Tests\Unit;

use MarjovanLier\StringManipulation\StringManipulation;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversMethod(\MarjovanLier\StringManipulation\StringManipulation::class . '::nameFix
Special characters and complex scenarios test suite for nameFix function.
Covers names with numbers, special characters, and complex real-world combinations.::class', 'nameFix
Special characters and complex scenarios test suite for nameFix function.
Covers names with numbers, special characters, and complex real-world combinations.')]
final class NameFixSpecialCharactersTest extends TestCase
{
    /**
     * Test names with numbers and special characters.
     */
    public function testNamesWithNumbersAndSpecialChars(): void
    {
        $names = [
            // Names with numbers - ucwords doesn't capitalize after numbers
            'smith2' => 'Smith2',
            'john3rd' => 'John3rd', // ucwords doesn't see 'd' after '3' as new word
            'macbeth4' => 'MacBeth4',

            // Roman numerals (common in names)
            'john iii' => 'John Iii',
            'william iv' => 'William Iv',

            // Professional titles
            'smith jr' => 'Smith Jr',
            'jones sr' => 'Jones Sr',

            // Mixed alphanumeric
            'a1b2c3' => 'A1b2c3',

            // Special characters that should be preserved
            'smith&jones' => 'Smith&jones', // & creates word boundary for ucwords
            'o-brien' => 'O-Brien',  // Different from apostrophe
        ];

        foreach ($names as $input => $expected) {
            self::assertEquals($expected, StringManipulation::nameFix($input), sprintf("Failed for input: '%s'", $input));
        }
    }

    /**
     * Test complex real-world name combinations.
     */
    public function testComplexRealWorldNames(): void
    {
        $names = [
            // Complex Spanish names
            'maría josé garcía rodríguez' => 'Maria Jose Garcia Rodriguez',
            'josé maría de la cruz santos' => 'Jose Maria de la Cruz Santos',

            // Complex Dutch names
            'johannes van der van de berg' => 'Johannes van der van de Berg',
            'pieter van den van der saar' => 'Pieter van den van der Saar',

            // Complex Irish/Scottish combinations
            "seán o'brien macarthur" => "Sean O'brien MacArthur",
            "mary mcdonald o'sullivan" => "Mary McDonald O'sullivan",

            // Professional and academic titles (dots are removed by removeAccents/utf8Ansi)
            'dr. smith' => 'Dr Smith',
            'prof. van der waals' => 'Prof van der Waals',

            // Multiple prefixes and suffixes
            "von und zu mcdonald-o'brien jr." => "von Und Zu McDonald-O'brien Jr ",
        ];

        foreach ($names as $input => $expected) {
            self::assertEquals($expected, StringManipulation::nameFix($input), sprintf("Failed for input: '%s'", $input));
        }
    }

    /**
     * Test numeric-only inputs.
     */
    public function testNumericInputs(): void
    {
        $names = [
            'num_123' => ['123', '123'],
            'num_12345' => ['12345', '12345'],
            'num_0' => ['0', '0'],
            'num_999999' => ['999999', '999999'],
        ];

        foreach ($names as [$input, $expected]) {
            self::assertEquals($expected, StringManipulation::nameFix($input), sprintf("Failed for input: '%s'", $input));
        }
    }

    /**
     * Test special character only inputs.
     */
    public function testSpecialCharacterInputs(): void
    {
        $names = [
            '!@#$%' => '!@#$%',
            '& ()' => '& ()', // & creates word boundary, ucwords capitalizes after it
            '[]{}' => '[]{}',
            '+=/-' => '+=/-',
            '~`^' => '~`^',
        ];

        foreach ($names as $input => $expected) {
            self::assertEquals($expected, StringManipulation::nameFix($input), sprintf("Failed for input: '%s'", $input));
        }
    }
}
