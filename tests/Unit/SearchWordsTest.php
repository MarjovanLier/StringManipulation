<?php

declare(strict_types=1);

namespace MarjovanLier\StringManipulation\Tests\Unit;

use MarjovanLier\StringManipulation\StringManipulation;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \MarjovanLier\StringManipulation\StringManipulation::searchWords
 */
final class SearchWordsTest extends TestCase
{
    private const HELLO_WORLD_LOWERCASE = 'hello world';


    /**
     * Test the searchWords function.
     */
    public function testSearchWordsFunction(): void
    {
        // Basic tests
        self::assertEquals('macdonald', StringManipulation::searchWords('MacDonald'));
        self::assertEquals(self::HELLO_WORLD_LOWERCASE, StringManipulation::searchWords('Hello World'));
        self::assertEquals(self::HELLO_WORLD_LOWERCASE, StringManipulation::searchWords('Hèllo Wørld'));
        self::assertEquals('a b c', StringManipulation::searchWords('a/b/c'));
        self::assertEquals(self::HELLO_WORLD_LOWERCASE, StringManipulation::searchWords('hello_world'));
    }


    /**
     * Negative tests for the searchWords function.
     */
    public function testSearchWordsFunctionNegative(): void
    {
        // Passing null
        self::assertNull(StringManipulation::searchWords(null));

        // Passing numbers
        self::assertEquals('12345', StringManipulation::searchWords('12345'));

        // Passing special characters
        self::assertEquals('! #$%', StringManipulation::searchWords('!@#$%'));

        // Passing strings with extra spaces
        self::assertEquals(self::HELLO_WORLD_LOWERCASE, StringManipulation::searchWords('  hello   world  '));

        // Passing strings with mixed special characters and extra spaces
        self::assertEquals(self::HELLO_WORLD_LOWERCASE, StringManipulation::searchWords('hello / world'));
        self::assertEquals(self::HELLO_WORLD_LOWERCASE, StringManipulation::searchWords('  hello / world  '));
    }


    public function testSearchWordsReturnsLowercaseOutput(): void
    {
        $result = StringManipulation::searchWords('HeLLo_World');
        self::assertEquals(self::HELLO_WORLD_LOWERCASE, $result);
    }


    public function testSearchWordsReturnsLowercaseOutputRegardlessOfInputCase(): void
    {
        $result = StringManipulation::searchWords('HeLLo_{WorLD}_(Test)');
        self::assertEquals('hello world test', $result);
    }


    public function testSearchWords(): void
    {
        $words = '{Hello/World?}';
        $result = StringManipulation::searchWords($words);
        self::assertEquals(self::HELLO_WORLD_LOWERCASE, $result);
    }


    public function testSearchWordsUpper(): void
    {
        $words = 'HELLO WORLD';
        $result = StringManipulation::searchWords($words);
        self::assertEquals(self::HELLO_WORLD_LOWERCASE, $result);
    }


    public function testSearchWordsWithUnlistedSpecialCharacters(): void
    {
        $words = '[Hello*World!]';
        $result = StringManipulation::searchWords($words);
        self::assertEquals('[hello world!]', $result);
    }
}
