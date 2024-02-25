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
    /**
     * @var string
     */
    private const LOVE_APPLE = 'I love apple.';

    // Replace multiple occurrences of multiple characters
    /**
     * @var string[]
     */
    private const SEARCH = [
        'H',
        'e',
        'W',
    ];

    /**
     * @var string[]
     */
    private const REPLACE = [
        'h',
        'x',
        'w',
    ];

    /**
     * @var string
     */
    private const SUBJECT = 'Hello World';


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
        // Basic test
        self::assertEquals('b', StringManipulation::strReplace('a', 'b', 'a'));

        // Replace multiple characters
        self::assertEquals('helloworld', StringManipulation::strReplace(['H', 'W'], ['h', 'w'], 'Helloworld'));

        // Replace multiple occurrences of a single character
        self::assertEquals('hxllo world', StringManipulation::strReplace('e', 'x', 'hello world'));
        self::assertEquals('hxllo world', StringManipulation::strReplace(self::SEARCH, self::REPLACE, self::SUBJECT));
    }


    public function testStrReplace(): void
    {
        $result = StringManipulation::strReplace('apple', 'banana', self::LOVE_APPLE);
        self::assertEquals('I love banana.', $result);
    }
}
