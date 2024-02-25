<?php

declare(strict_types=1);

namespace MarjovanLier\StringManipulation\Tests\Unit;

use MarjovanLier\StringManipulation\StringManipulation;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \MarjovanLier\StringManipulation\StringManipulation::trim
 */
final class TrimTest extends TestCase
{
    private const DEFAULT_TRIM_CHARACTERS = " \t\n\r\0\x0B";


    /**
     * @return array<int, array<int, string>>
     */
    public static function trimDataProvider(): array
    {
        return [
            // Basic tests
            [
                ' hello ',
                self::DEFAULT_TRIM_CHARACTERS,
                'hello',
            ],
            [
                "\thello\t",
                self::DEFAULT_TRIM_CHARACTERS,
                'hello',
            ],
            [
                "\nhello\n",
                self::DEFAULT_TRIM_CHARACTERS,
                'hello',
            ],
            // Tests with custom characters
            [
                '[hello]',
                '[]',
                'hello',
            ],
            [
                '(hello)',
                '()',
                'hello',
            ],
            // Tests with empty strings
            [
                '',
                self::DEFAULT_TRIM_CHARACTERS,
                '',
            ],
            // Tests with no characters to trim
            [
                'hello',
                'z',
                'hello',
            ],
        ];
    }


    /**
     * @dataProvider trimDataProvider
     */
    public function testTrim(string $input, string $characters, mixed $expected): void
    {
        self::assertEquals($expected, StringManipulation::trim($input, $characters));
    }
}
