<?php

declare(strict_types=1);

namespace MarjovanLier\StringManipulation\Tests\Unit;

use MarjovanLier\StringManipulation\StringManipulation;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \MarjovanLier\StringManipulation\StringManipulation::strReplace
 */
final class StrReplaceTest extends TestCase
{
    private const string LOVE_APPLE = 'I love apple.';

    /**
     * @var array<int, string>
     */
    private const array SEARCH = [
        'H',
        'e',
        'W',
    ];

    /**
     * @var array<int, string>
     */
    private const array REPLACE = [
        'h',
        'x',
        'w',
    ];

    private const string SUBJECT = 'Hello World';


    public function testStrReplaceBasicFunctionality(): void
    {
        // Test with not found search
        $result = StringManipulation::strReplace('pineapple', 'banana', self::LOVE_APPLE);
        self::assertEquals(self::LOVE_APPLE, $result);

        // Basic test
        self::assertEquals('b', StringManipulation::strReplace('a', 'b', 'a'));

        // Replace multiple characters
        self::assertEquals('helloworld', StringManipulation::strReplace(['H', 'W'], ['h', 'w'], 'Helloworld'));

        // Replace multiple occurrences of a single character
        self::assertEquals('hxllo world', StringManipulation::strReplace('e', 'x', 'hello world'));
        self::assertEquals('hxllo world', StringManipulation::strReplace(self::SEARCH, self::REPLACE, self::SUBJECT));

        // Basic replacement test
        $result = StringManipulation::strReplace('apple', 'banana', self::LOVE_APPLE);
        self::assertEquals('I love banana.', $result);
    }


    /**
     * Test that specifically targets the single character optimisation path.
     * This kills the IncrementInteger mutation by ensuring behaviour is different
     * when search string length is exactly 1.
     */
    public function testSingleCharacterOptimisation(): void
    {
        // Test with a single character (should use strtr optimisation).
        $result1 = StringManipulation::strReplace('a', 'z', 'banana');
        self::assertSame('bznznz', $result1);

        // Test with a two-character string (should use str_replace).
        $result2 = StringManipulation::strReplace('an', 'z', 'banana');
        self::assertSame('bzza', $result2);

        // This verifies the behaviour difference - if the mutation changes the length check.
        // from === 1 to === 2, both calls would produce the same behaviour, and this test would fail.
    }

    /**
     * Test that specifically targets the distinction between single character and non-single character.
     * This kills the Identical mutation that changes === 1 to !== 1
     */
    public function testSingleCharacterVsMultipleCharacter(): void
    {
        // Create a scenario where strtr and str_replace have observable differences.

        // Case 1: Using a single character replacement (should use strtr).
        $subject = 'abababa';
        $result1 = StringManipulation::strReplace('a', 'c', $subject);

        // Case 2: Using an array with equivalent replacements (should use str_replace).
        $result2 = StringManipulation::strReplace(['a'], ['c'], $subject);

        // Both should produce the same result despite taking different code paths.
        self::assertSame('cbcbcbc', $result1);
        self::assertSame($result1, $result2);

        // This next test specifically looks at behaviour that would be different.
        // if the optimisation wasn't properly working.

        // Using overlapping replacements, the order matters in str_replace but not in strtr.
        $complex = 'abcabc';

        // Directly using strtr for comparison.
        $expected = strtr($complex, ['a' => 'z', 'z' => 'y']);

        // Using our optimised function which should handle this the same way.
        $actual = StringManipulation::strReplace('a', 'z', $complex);
        self::assertSame('zbczbc', $actual);
        self::assertSame($expected, $actual);
    }

    /**
     * Edge case test that verifies the empty string optimisation
     */
    public function testEmptyStringOptimisation(): void
    {
        // Test that empty subject returns empty string immediately.
        $result = StringManipulation::strReplace('a', 'b', '');
        self::assertSame('', $result);

        // Test that empty search/replace with non-empty subject works correctly.
        $result = StringManipulation::strReplace('', 'x', 'abc');
        self::assertSame('abc', $result);
    }


    /**
     * Test comprehensive array-based string replacements.
     */
    public function testArrayReplacements(): void
    {
        // Multiple search/replace arrays
        $searches = ['cat', 'dog', 'bird'];
        $replacements = ['feline', 'canine', 'avian'];
        $text = 'The cat, dog, and bird are animals.';
        $expected = 'The feline, canine, and avian are animals.';
        self::assertEquals($expected, StringManipulation::strReplace($searches, $replacements, $text));

        // Array with overlapping matches
        $searches = ['ab', 'bc', 'ca'];
        $replacements = ['X', 'Y', 'Z'];
        $text = 'abcabc';
        $result = StringManipulation::strReplace($searches, $replacements, $text);
        // Actual behaviour: 'ab' -> 'X', then 'bc' -> 'Y' doesn't match because 'b' is gone
        self::assertEquals('XcXc', $result);

        // Arrays with different character lengths
        $searches = ['a', 'bb', 'ccc'];
        $replacements = ['AAA', 'B', 'c'];
        $text = 'a bb ccc';
        $expected = 'AAA B c';
        self::assertEquals($expected, StringManipulation::strReplace($searches, $replacements, $text));

        // Empty replacements
        $searches = ['remove', 'delete', 'erase'];
        $replacements = ['', '', ''];
        $text = 'remove this, delete that, erase everything';
        $expected = ' this,  that,  everything';
        self::assertEquals($expected, StringManipulation::strReplace($searches, $replacements, $text));
    }


    /**
     * Test case-sensitive string replacements.
     */
    public function testCaseSensitiveReplacements(): void
    {
        // Basic case sensitivity
        $text = 'Hello hello HELLO';
        self::assertEquals('Hi hello HELLO', StringManipulation::strReplace('Hello', 'Hi', $text));
        self::assertEquals('Hello Hi HELLO', StringManipulation::strReplace('hello', 'Hi', $text));
        self::assertEquals('Hello hello HI', StringManipulation::strReplace('HELLO', 'HI', $text));

        // Mixed case arrays
        $searches = ['Cat', 'DOG', 'bIrD'];
        $replacements = ['Feline', 'CANINE', 'AvIaN'];
        $text = 'Cat DOG bIrD cat dog bird';
        $expected = 'Feline CANINE AvIaN cat dog bird';
        self::assertEquals($expected, StringManipulation::strReplace($searches, $replacements, $text));
    }


    /**
     * Test string replacements with special characters.
     */
    public function testSpecialCharacterReplacements(): void
    {
        // Replace special characters
        $text = 'Hello@World#Test$Example%Done';
        self::assertEquals('Hello_World#Test$Example%Done', StringManipulation::strReplace('@', '_', $text));
        self::assertEquals('Hello@World_Test$Example%Done', StringManipulation::strReplace('#', '_', $text));

        // Multiple special character replacements
        $searches = ['@', '#', '$', '%'];
        $replacements = ['_AT_', '_HASH_', '_DOLLAR_', '_PERCENT_'];
        $expected = 'Hello_AT_World_HASH_Test_DOLLAR_Example_PERCENT_Done';
        self::assertEquals($expected, StringManipulation::strReplace($searches, $replacements, $text));

        // Unicode special characters
        $unicodeText = 'Café→Restaurant←Menu';
        self::assertEquals('Café_Restaurant←Menu', StringManipulation::strReplace('→', '_', $unicodeText));
        self::assertEquals('Café→Restaurant_Menu', StringManipulation::strReplace('←', '_', $unicodeText));
    }


    /**
     * Test string replacements with numbers.
     */
    public function testNumberReplacements(): void
    {
        // Replace numbers
        $text = 'Version 1.2.3 released on 2023-09-06';
        self::assertEquals('Version X.2.3 released on 2023-09-06', StringManipulation::strReplace('1', 'X', $text));

        // Replace multiple numbers
        $searches = ['1', '2', '3'];
        $replacements = ['ONE', 'TWO', 'THREE'];
        $expected = 'Version ONE.TWO.THREE released on TWO0TWOTHREE-09-06';
        self::assertEquals($expected, StringManipulation::strReplace($searches, $replacements, $text));

        // Replace number patterns
        $dateText = '2023-09-06 14:30:15';
        self::assertEquals('XXXX-09-06 14:30:15', StringManipulation::strReplace('2023', 'XXXX', $dateText));
        self::assertEquals('2023-XX-06 14:30:15', StringManipulation::strReplace('09', 'XX', $dateText));
    }


    /**
     * Test performance, whitespace and real-world scenarios.
     */
    public function testAdvancedReplacementScenarios(): void
    {
        // Performance: Large text with multiple replacements
        $largeText = str_repeat('The quick brown fox jumps over the lazy dog. ', 100);
        $result = StringManipulation::strReplace('fox', 'cat', $largeText);
        self::assertStringContainsString('cat', $result);
        self::assertStringNotContainsString('fox', $result);

        // Performance: Many small replacements
        $text = str_repeat('abcdefghijklmnopqrstuvwxyz', 10);
        $searches = ['a', 'e', 'i', 'o', 'u'];
        $replacements = ['A', 'E', 'I', 'O', 'U'];
        $result = StringManipulation::strReplace($searches, $replacements, $text);
        self::assertStringContainsString('A', $result);
        self::assertStringNotContainsString('a', $result);

        // Whitespace: Replace different types of whitespace
        $text = "Line1\tTab\nNewline\rCarriageReturn Line2";
        self::assertEquals("Line1 Tab\nNewline\rCarriageReturn Line2", StringManipulation::strReplace("\t", ' ', $text));

        // Whitespace: Normalise all whitespace
        $searches = ["\t", "\n", "\r"];
        $replacements = [' ', ' ', ' '];
        $expected = "Line1 Tab Newline CarriageReturn Line2";
        self::assertEquals($expected, StringManipulation::strReplace($searches, $replacements, $text));

        // Real-world: HTML entity replacement
        $htmlText = 'Caf&eacute; &amp; Restaurant &quot;Menu&quot;';
        $searches = ['&eacute;', '&amp;', '&quot;'];
        $replacements = ['é', '&', '"'];
        $expected = 'Café & Restaurant "Menu"';
        self::assertEquals($expected, StringManipulation::strReplace($searches, $replacements, $htmlText));

        // Real-world: URL slug creation
        $title = 'How to Learn PHP: A Complete Guide for Beginners!';
        $searches = [' ', ':', '!'];
        $replacements = ['-', '', ''];
        $expected = 'How-to-Learn-PHP-A-Complete-Guide-for-Beginners';
        self::assertEquals($expected, StringManipulation::strReplace($searches, $replacements, $title));

        // Real-world: File path normalisation
        $windowsPath = 'C:\\Users\\Name\\Documents\\File.txt';
        $unixPath = 'C:/Users/Name/Documents/File.txt';
        self::assertEquals($unixPath, StringManipulation::strReplace('\\', '/', $windowsPath));
    }
}
