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


    public function testStrReplaceWithNotFoundSearch(): void
    {
        $result = StringManipulation::strReplace('pineapple', 'banana', self::LOVE_APPLE);
        self::assertEquals(self::LOVE_APPLE, $result);
    }


    /**
     * Test the strReplace function.
     */
    public function testStrReplaceFunction(): void
    {
        // Basic test.
        self::assertEquals('b', StringManipulation::strReplace('a', 'b', 'a'));

        // Replace multiple characters.
        self::assertEquals('helloworld', StringManipulation::strReplace(['H', 'W'], ['h', 'w'], 'Helloworld'));

        // Replace multiple occurrences of a single character.
        self::assertEquals('hxllo world', StringManipulation::strReplace('e', 'x', 'hello world'));
        self::assertEquals('hxllo world', StringManipulation::strReplace(self::SEARCH, self::REPLACE, self::SUBJECT));
    }


    public function testStrReplace(): void
    {
        $result = StringManipulation::strReplace('apple', 'banana', self::LOVE_APPLE);
        self::assertEquals('I love banana.', $result);
    }


    /**
     * Test that specifically targets the single character optimization path.
     * This kills the IncrementInteger mutation by ensuring behavior is different
     * when search string length is exactly 1.
     */
    public function testSingleCharacterOptimization(): void
    {
        // Test with a single character (should use strtr optimization).
        $result1 = StringManipulation::strReplace('a', 'z', 'banana');
        self::assertSame('bznznz', $result1);

        // Test with a two-character string (should use str_replace).
        $result2 = StringManipulation::strReplace('an', 'z', 'banana');
        self::assertSame('bzza', $result2);

        // This verifies the behavior difference - if the mutation changes the length check.
        // from === 1 to === 2, both calls would produce the same behavior, and this test would fail.
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

        // This next test specifically looks at behavior that would be different.
        // if the optimization wasn't properly working.

        // Using overlapping replacements, the order matters in str_replace but not in strtr.
        $complex = 'abcabc';

        // Directly using strtr for comparison.
        $expected = strtr($complex, ['a' => 'z', 'z' => 'y']);

        // Using our optimized function which should handle this the same way.
        $actual = StringManipulation::strReplace('a', 'z', $complex);
        self::assertSame('zbczbc', $actual);
        self::assertSame($expected, $actual);
    }

    /**
     * Edge case test that verifies the empty string optimization
     */
    public function testEmptyStringOptimization(): void
    {
        // Test that empty subject returns empty string immediately.
        $result = StringManipulation::strReplace('a', 'b', '');
        self::assertSame('', $result);

        // Test that empty search/replace with non-empty subject works correctly.
        $result = StringManipulation::strReplace('', 'x', 'abc');
        self::assertSame('abc', $result);
    }
}
