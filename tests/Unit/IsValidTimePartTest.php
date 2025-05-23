<?php

declare(strict_types=1);

namespace MarjovanLier\StringManipulation\Tests\Unit;

use MarjovanLier\StringManipulation\StringManipulation;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @internal
 *
 * @covers \MarjovanLier\StringManipulation\StringManipulation::isValidTimePart
 */
final class IsValidTimePartTest extends TestCase
{
    /**
     * Provides a set of date and time parts to test.
     *
     * @return array<int, array<int, array<string, int>|bool>>
     *
     * @psalm-return list<array{0: array{year: int, month: int, day: int, hour: int, minute: int, second: int}, 1: bool}>
     */
    public static function provideTimeParts(): array
    {
        return [
            [
                [
                    'year' => 2023,
                    'month' => 12,
                    'day' => 25,
                    'hour' => 0,
                    'minute' => 0,
                    'second' => 0,
                ],
                true,
            ],
            [
                [
                    'year' => 2023,
                    'month' => 12,
                    'day' => 25,
                    'hour' => 23,
                    'minute' => 59,
                    'second' => 59,
                ],
                true,
            ],
            [
                [
                    'year' => 2023,
                    'month' => 12,
                    'day' => 25,
                    'hour' => -1,
                    'minute' => 0,
                    'second' => 0,
                ],
                false,
            ],
            [
                [
                    'year' => 2023,
                    'month' => 12,
                    'day' => 25,
                    'hour' => 24,
                    'minute' => 0,
                    'second' => 0,
                ],
                false,
            ],
            [
                [
                    'year' => 2023,
                    'month' => 12,
                    'day' => 25,
                    'hour' => 0,
                    'minute' => -1,
                    'second' => 0,
                ],
                false,
            ],
            [
                [
                    'year' => 2023,
                    'month' => 12,
                    'day' => 25,
                    'hour' => 0,
                    'minute' => 60,
                    'second' => 0,
                ],
                false,
            ],
            [
                [
                    'year' => 2023,
                    'month' => 12,
                    'day' => 25,
                    'hour' => 0,
                    'minute' => 0,
                    'second' => -1,
                ],
                false,
            ],
            [
                [
                    'year' => 2023,
                    'month' => 12,
                    'day' => 25,
                    'hour' => 0,
                    'minute' => 0,
                    'second' => 60,
                ],
                false,
            ],
            [
                [
                    'year' => 2023,
                    'month' => 2,
                    'day' => 30,
                    'hour' => 12,
                    'minute' => 0,
                    'second' => 0,
                ],
                false,  // Invalid date - Feb 30
            ],
            [
                [
                    'year' => 2023,
                    'month' => 13,
                    'day' => 1,
                    'hour' => 12,
                    'minute' => 0,
                    'second' => 0,
                ],
                false,  // Invalid month
            ],
        ];
    }


    /**
     * Tests the isValidTimePart method.
     *
     * @param array{year?: int, month?: int, day?: int, hour: int, minute: int, second: int} $timeParts
     *
     * @dataProvider provideTimeParts
     */
    #[DataProvider('provideTimeParts')]
    public function testIsValidTimePart(array $timeParts, bool $expectedResult): void
    {
        $reflectionMethod = (new ReflectionClass(StringManipulation::class))->getMethod('isValidTimePart');

        /**
         * @noinspection PhpExpressionResultUnusedInspection
         *
         * @psalm-suppress UnusedMethodCall
         */
        $reflectionMethod->setAccessible(true);

        $result = $reflectionMethod->invoke(null, $timeParts);

        self::assertSame($expectedResult, $result);
    }
}
