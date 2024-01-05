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
    /**
     * @return array<int, array<int, string>>
     */
    public static function trimDataProvider(): array
    {
        return [
            // Basic tests
            [
                ' hello ',
                " \t\n\r\0\x0B",
                'hello',
            ],
            [
                "\thello\t",
                " \t\n\r\0\x0B",
                'hello',
            ],
            [
                "\nhello\n",
                " \t\n\r\0\x0B",
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
                " \t\n\r\0\x0B",
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
