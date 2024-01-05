<?php

declare(strict_types=1);

namespace MarjovanLier\StringManipulation\Tests\Unit;

use MarjovanLier\StringManipulation\StringManipulation;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @internal
 *
 * @covers \App\Utilities\StringManipulation::isValidTimePart
 */
final class IsValidTimePartTest extends TestCase
{
    /**
     * Provides a set of time parts to test.
     *
     * @return array<int, array<int, array<string, int>|bool>>
     *
     * @psalm-return list{list{array{hour: 0, minute: 0, second: 0}, true}, list{array{hour: 23, minute: 59, second:
     *     59}, true}, list{array{hour: -1, minute: 0, second: 0}, false}, list{array{hour: 24, minute: 0, second: 0},
     *     false}, list{array{hour: 0, minute: -1, second: 0}, false}, list{array{hour: 0, minute: 60, second: 0},
     *     false}, list{array{hour: 0, minute: 0, second: -1}, false}, list{array{hour: 0, minute: 0, second: 60},
     *     false}}
     */
    public static function provideTimeParts(): array
    {
        return [
            [
                [
                    'hour' => 0,
                    'minute' => 0,
                    'second' => 0,
                ],
                true,
            ],
            [
                [
                    'hour' => 23,
                    'minute' => 59,
                    'second' => 59,
                ],
                true,
            ],
            [
                [
                    'hour' => -1,
                    'minute' => 0,
                    'second' => 0,
                ],
                false,
            ],
            [
                [
                    'hour' => 24,
                    'minute' => 0,
                    'second' => 0,
                ],
                false,
            ],
            [
                [
                    'hour' => 0,
                    'minute' => -1,
                    'second' => 0,
                ],
                false,
            ],
            [
                [
                    'hour' => 0,
                    'minute' => 60,
                    'second' => 0,
                ],
                false,
            ],
            [
                [
                    'hour' => 0,
                    'minute' => 0,
                    'second' => -1,
                ],
                false,
            ],
            [
                [
                    'hour' => 0,
                    'minute' => 0,
                    'second' => 60,
                ],
                false,
            ],
        ];
    }


    /**
     * Tests the isValidTimePart method.
     *
     * @param array{year: int, month: int, day: int, hour: int, minute: int, second: int} $timeParts
     *
     * @dataProvider provideTimeParts
     */
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
