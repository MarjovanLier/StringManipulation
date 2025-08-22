<?php

declare(strict_types=1);

namespace MarjovanLier\StringManipulation\Tests\Unit;

use MarjovanLier\StringManipulation\StringManipulation;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \MarjovanLier\StringManipulation\StringManipulation::trim
 */
final class TrimTest extends TestCase
{
    private const string DEFAULT_TRIM_CHARACTERS = " \t\n\r\0\x0B";


    /**
     * @return array<int, array<int, string>>
     */
    public static function trimDataProvider(): array
    {
        return array_merge(
            self::getBasicTrimCases(),
            self::getAdvancedTrimCases(),
            self::getSpecialTrimCases(),
        );
    }

    /**
     * @return array<int, array<int, string>>
     */
    private static function getBasicTrimCases(): array
    {
        return [
            // Basic tests
            [' hello ', self::DEFAULT_TRIM_CHARACTERS, 'hello'],
            ["\thello\t", self::DEFAULT_TRIM_CHARACTERS, 'hello'],
            ["\nhello\n", self::DEFAULT_TRIM_CHARACTERS, 'hello'],
            // Tests with custom characters
            ['[hello]', '[]', 'hello'],
            ['(hello)', '()', 'hello'],
            // Tests with empty strings
            ['', self::DEFAULT_TRIM_CHARACTERS, ''],
            // Tests with no characters to trim
            ['hello', 'z', 'hello'],
            // Multiple consecutive whitespace
            ['   hello   ', self::DEFAULT_TRIM_CHARACTERS, 'hello'],
            ["\t\t\thello\t\t\t", self::DEFAULT_TRIM_CHARACTERS, 'hello'],
            ["\n\r\n\rhello\n\r\n\r", self::DEFAULT_TRIM_CHARACTERS, 'hello'],
            // Mixed whitespace types
            [" \t\n\rhello \t\n\r", self::DEFAULT_TRIM_CHARACTERS, 'hello'],
        ];
    }

    /**
     * @return array<int, array<int, string>>
     */
    private static function getAdvancedTrimCases(): array
    {
        return [
            // Unicode whitespace characters
            ["\u{00A0}hello\u{00A0}", "\u{00A0}", 'hello'],
            ["\u{2000}hello\u{2000}", "\u{2000}", 'hello'],
            // Complex custom character sets
            ['***hello***', '*', 'hello'],
            ['abcdefghelloabcdefg', 'abcdefg', 'hello'],
            ['.,;!hello.,;!', '.,;!', 'hello'],
            // Only trim characters
            ['   ', self::DEFAULT_TRIM_CHARACTERS, ''],
            ['***', '*', ''],
            // Null bytes and special characters
            ["\0hello\0", "\0", 'hello'],
            ["\x0Bhello\x0B", "\x0B", 'hello'],
        ];
    }

    /**
     * @return array<int, array<int, string>>
     */
    private static function getSpecialTrimCases(): array
    {
        return [
            // One-sided trimming scenarios
            ['   hello', ' ', 'hello'],
            ['hello   ', ' ', 'hello'],
            // Numbers with trim characters
            ['   12345   ', self::DEFAULT_TRIM_CHARACTERS, '12345'],
            // Long strings
            ['   ' . str_repeat('hello', 100) . '   ', self::DEFAULT_TRIM_CHARACTERS, str_repeat('hello', 100)],
            // Multiple character trim set
            ['abcXYZabc', 'abc', 'XYZ'],
        ];
    }


    /**
     * @dataProvider trimDataProvider
     */
    #[DataProvider('trimDataProvider')]
    public function testTrim(string $input, string $characters, mixed $expected): void
    {
        self::assertEquals($expected, StringManipulation::trim($input, $characters));
    }


    /**
     * Test negative flow scenarios for trim function.
     */
    public function testTrimNegativeFlow(): void
    {
        // Very large character set
        $hugeCharSet = str_repeat('abcdefghijklmnopqrstuvwxyz', 50);
        $text = 'xyz middle content abc';
        $result = StringManipulation::trim($text, $hugeCharSet);
        self::assertEquals(' middle content ', $result);

        // Empty character set - should return original string
        $text = '   hello world   ';
        self::assertEquals($text, StringManipulation::trim($text, ''));

        // Characters not present in string
        $text = 'hello world';
        self::assertEquals($text, StringManipulation::trim($text, 'xyz'));

        // All characters are trim characters
        $text = '   ';
        self::assertEquals('', StringManipulation::trim($text, ' '));

        // Malformed Unicode sequences (binary data)
        $binaryData = "\x80\x81\x82hello\x83\x84\x85";
        $result = StringManipulation::trim($binaryData, "\x80\x81\x82\x83\x84\x85");
        self::assertEquals('hello', $result);

        // Very long string with performance implications
        $longString = str_repeat('a', 10000) . 'content' . str_repeat('b', 10000);
        $startTime = microtime(true);
        $result = StringManipulation::trim($longString, 'ab');
        $duration = microtime(true) - $startTime;
        self::assertEquals('content', $result);
        self::assertLessThan(1.0, $duration, 'Trim operation should complete within reasonable time');

        // Unicode edge cases - invalid UTF-8
        $invalidUtf8 = "\xFF\xFE" . 'hello' . "\xFF\xFE";
        $result = StringManipulation::trim($invalidUtf8, "\xFF\xFE");
        self::assertEquals('hello', $result);

        // Null bytes in character set
        $text = "\0\x01hello\x01\0";
        $result = StringManipulation::trim($text, "\0\x01");
        self::assertEquals('hello', $result);

        // Special regex characters in trim set
        $text = '.*+hello+*.';
        $result = StringManipulation::trim($text, '.*+');
        self::assertEquals('hello', $result);
    }


    /**
     * Test edge cases and boundary conditions.
     */
    public function testTrimEdgeCases(): void
    {
        // Single character string with trim character
        self::assertEquals('', StringManipulation::trim('a', 'a'));

        // Single character string without trim character
        self::assertEquals('b', StringManipulation::trim('b', 'a'));

        // String with only whitespace variations
        $whitespaceOnly = " \t\n\r\0\x0B";
        self::assertEquals('', StringManipulation::trim($whitespaceOnly, self::DEFAULT_TRIM_CHARACTERS));

        // Mixed control characters
        $controlChars = "\x01\x02\x03\x04\x05";
        $text = $controlChars . 'content' . $controlChars;
        $result = StringManipulation::trim($text, $controlChars);
        self::assertEquals('content', $result);

        // Unicode boundary characters
        $text = "\u{00A0}\u{2000}content\u{2000}\u{00A0}";
        $result = StringManipulation::trim($text, "\u{00A0}\u{2000}");
        self::assertEquals('content', $result);

        // Overlapping character ranges
        $text = 'abcdefg';
        $result = StringManipulation::trim($text, 'abcgfe');
        self::assertEquals('d', $result);

        // Maximum length character set
        $allAscii = '';
        for ($i = 32; $i <= 126; ++$i) {
            $allAscii .= chr($i);
        }

        $text = 'Hello World!';
        $result = StringManipulation::trim($text, $allAscii);
        self::assertEquals('', $result);
    }
}
