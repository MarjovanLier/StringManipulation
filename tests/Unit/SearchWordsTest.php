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
    private const string HELLO_WORLD_LOWERCASE = 'hello world';


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


    public function testSearchWordsBasicFunctionality(): void
    {
        // Test lowercase output
        $result = StringManipulation::searchWords('HeLLo_World');
        self::assertEquals(self::HELLO_WORLD_LOWERCASE, $result);

        // Test regardless of input case
        $result = StringManipulation::searchWords('HeLLo_{WorLD}_(Test)');
        self::assertEquals('hello world test', $result);

        // Test with brackets and symbols
        $words = '{Hello/World?}';
        $result = StringManipulation::searchWords($words);
        self::assertEquals(self::HELLO_WORLD_LOWERCASE, $result);

        // Test uppercase input
        $words = 'HELLO WORLD';
        $result = StringManipulation::searchWords($words);
        self::assertEquals(self::HELLO_WORLD_LOWERCASE, $result);

        // Test with unlisted special characters
        $words = '[Hello*World!]';
        $result = StringManipulation::searchWords($words);
        self::assertEquals('[hello world!]', $result);
    }


    /**
     * Test comprehensive edge cases and boundary conditions for searchWords.
     */
    public function testSearchWordsComprehensiveEdgeCases(): void
    {
        // Unicode edge cases
        $unicodeTests = [
            // Mixed scripts
            'Hello мир' => 'hello мир',
            'Café 中文' => 'cafe 中文',
            'Test Γεια' => 'test Γεια',

            // Emoji and symbols
            'Hello 🌍 World' => 'hello 🌍 world',
            'Price $19.99' => 'price $19 99',
            'Temperature 25°C' => 'temperature 25°c',

            // Combining characters
            'café' => 'cafe',  // Regular é
            "cafe\u{0301}" => "cafe\u{0301}",  // e + combining acute
        ];

        foreach ($unicodeTests as $input => $expected) {
            self::assertEquals($expected, StringManipulation::searchWords($input), 'Failed for input: ' . $input);
        }

        // Special character patterns
        $specialCharTests = [
            '!!URGENT!!' => '!!urgent!!',
            '---separator---' => '---separator---',
            '***important***' => 'important',
            '+++plus+++' => '+++plus+++',
            '|||pipe|||' => '|||pipe|||',

            // Brackets and parentheses
            '[important]' => '[important]',
            '(note)' => 'note',
            '{data}' => 'data',
            '<tag>' => '<tag>',

            // Mixed punctuation
            'Hello, World!' => 'hello world!',
            'Dr. Smith Jr.' => 'dr smith jr',
            'U.S.A.' => 'u s a',
            'Ph.D.' => 'ph d',
        ];

        foreach ($specialCharTests as $input => $expected) {
            self::assertEquals($expected, StringManipulation::searchWords($input), 'Failed for input: ' . $input);
        }

        // Whitespace and formatting edge cases
        $whitespaceTests = [
            "  multiple   spaces  " => 'multiple spaces',
            "\ttab\tseparated\t" => 'tab	separated',
            "\nnewline\nseparated\n" => "newline\nseparated",
            "\rmixed\r\nlinebreaks\n" => "mixed\r\nlinebreaks",
            "no\x00null\x00bytes" => "no\x00null\x00bytes",
        ];

        foreach ($whitespaceTests as $input => $expected) {
            self::assertEquals($expected, StringManipulation::searchWords($input), 'Failed for input: ' . $input);
        }
    }


    /**
     * Test performance and stress scenarios for searchWords.
     */
    public function testSearchWordsPerformance(): void
    {
        // Large string with many special characters
        $largeText = str_repeat('Hello-World@Test#123$Special%Characters^And&More*', 1000);
        $startTime = microtime(true);
        $result = StringManipulation::searchWords($largeText);
        $duration = microtime(true) - $startTime;

        self::assertLessThan(1.0, $duration, 'Large string processing should be efficient');
        self::assertNotNull($result);
        self::assertStringContainsString('hello-world test', $result);
        self::assertStringNotContainsString('@', $result);
        self::assertStringContainsString('#', $result);

        // String with many consecutive special characters
        $consecutiveSpecial = str_repeat('!!@@##$$%%^^&&**(())', 500) . 'content' . str_repeat('!!@@##$$%%^^&&**(())', 500);
        $startTime = microtime(true);
        $result = StringManipulation::searchWords($consecutiveSpecial);
        $duration = microtime(true) - $startTime;

        self::assertLessThan(0.5, $duration, 'Consecutive special characters should be handled efficiently');
        self::assertNotNull($result);
        self::assertStringContainsString('content', $result);

        // Unicode stress test
        $unicodeStress = str_repeat('Café-Résumé@Naïve#Zürich$München%Ålesund^Øresund&Björk', 100);
        $startTime = microtime(true);
        $result = StringManipulation::searchWords($unicodeStress);
        $duration = microtime(true) - $startTime;

        self::assertLessThan(1.0, $duration, 'Unicode processing should be efficient');
        self::assertNotNull($result);
        self::assertStringContainsString('cafe-resume naive', $result);
    }


    /**
     * Test negative flow scenarios for searchWords.
     */
    public function testSearchWordsAdvancedNegativeFlow(): void
    {
        // Binary data and control characters
        $binaryData = "\x00\x01\x02hello\x03\x04\x05world\x06\x07\x08";
        $result = StringManipulation::searchWords($binaryData);
        self::assertNotNull($result);
        self::assertStringContainsString('hello', $result);
        self::assertStringContainsString('world', $result);

        // Malformed Unicode sequences
        $malformedUtf8 = "hello\xFF\xFEworld";
        $result = StringManipulation::searchWords($malformedUtf8);
        self::assertNotNull($result);
        self::assertStringContainsString('hello', $result);
        self::assertStringContainsString('world', $result);

        // Very long individual "words" (no spaces)
        $longWord = str_repeat('a', 10000);
        $result = StringManipulation::searchWords($longWord);
        self::assertEquals(strtolower($longWord), $result);

        // String with only special characters
        $onlySpecial = '!@#$%^&*()[]{}|\\:";\'<>?,./-_+=~`';
        $result = StringManipulation::searchWords($onlySpecial);
        self::assertNotNull($result);
        self::assertStringNotContainsString('@', $result);
        // Note: # is actually preserved in the output
        self::assertStringContainsString('#', $result);

        // Mixed encoding attempts
        $convertedString = mb_convert_encoding('café', 'ISO-8859-1', 'UTF-8');
        $mixedEncoding = 'hello' . ($convertedString !== false ? $convertedString : '') . 'world';
        $result = StringManipulation::searchWords($mixedEncoding);
        self::assertNotNull($result);

        // Edge case with Mac/Mc prefixes in various contexts
        $macTests = [
            'MacArthur-MacDonald' => 'macarthur-macdonald',
            'mcbride@mcdonald.com' => 'mcbride mc donald com',
            'Mac&Cheese' => 'mac&cheese',
            'Mc#Test' => 'mc#test',
        ];

        foreach ($macTests as $input => $expected) {
            self::assertEquals($expected, StringManipulation::searchWords($input), 'Failed for Mac/Mc test: ' . $input);
        }
    }


    /**
     * Test real-world scenarios and complex inputs.
     */
    public function testSearchWordsRealWorldScenarios(): void
    {
        // Email addresses
        $emails = [
            'contact@example.com' => 'contact example com',
            'user.name+tag@domain.co.uk' => 'user name+tag domain co uk',
            'test123@sub.domain.org' => 'test123 sub domain org',
        ];

        foreach ($emails as $input => $expected) {
            self::assertEquals($expected, StringManipulation::searchWords($input));
        }

        // URLs
        $urls = [
            'https://www.example.com/path' => 'https www example com path',
            'ftp://files.domain.org:8080' => 'ftp files domain org 8080',
            'http://sub-domain.test.io' => 'http sub-domain test io',
        ];

        foreach ($urls as $input => $expected) {
            self::assertEquals($expected, StringManipulation::searchWords($input));
        }

        // File paths
        $paths = [
            '/home/user/documents/file.txt' => 'home user documents file txt',
            'C:\\Users\\Name\\Desktop\\test.doc' => 'c users name desktop test doc',
            './relative/path/file.php' => 'relative path file php',
        ];

        foreach ($paths as $input => $expected) {
            self::assertEquals($expected, StringManipulation::searchWords($input));
        }

        // Social media and hashtags
        $social = [
            '#hashtag @username' => '#hashtag username',
            '@mention #tag123' => 'mention #tag123',
            'Follow @user_name for #updates!' => 'follow user name for #updates!',
        ];

        foreach ($social as $input => $expected) {
            self::assertEquals($expected, StringManipulation::searchWords($input));
        }

        // Technical content
        $technical = [
            'function_name($param1, $param2)' => 'function name $param1 $param2',
            'array["key"] = value;' => 'array[ key ] = value;',
            'object.method().property' => 'object method property',
            'SELECT * FROM table_name WHERE id=123' => 'select from table name where id=123',
        ];

        foreach ($technical as $input => $expected) {
            self::assertEquals($expected, StringManipulation::searchWords($input));
        }
    }
}
