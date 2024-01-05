<?php

declare(strict_types=1);

namespace MarjovanLier\StringManipulation\Tests\Unit;

use MarjovanLier\StringManipulation\StringManipulation;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @internal
 *
 * @covers \MarjovanLier\StringManipulation\StringManipulation::isvalidhour
 */
final class IsValidHourTest extends TestCase
{
    /**
     * Provides a set of Hours to test.
     *
     * @return array<int, array<int, bool|int>>
     *
     * @psalm-return list{list{0, true}, list{12, true}, list{23, true}, list{30, false}, list{59, false}, list{-1,
     *     false}, list{60, false}, list{100, false}}
     */
    public static function provideHours(): array
    {
        return [
            [
                0,
                true,
            ],
            [
                12,
                true,
            ],
            [
                23,
                true,
            ],
            [
                30,
                false,
            ],
            [
                59,
                false,
            ],
            [
                -1,
                false,
            ],
            [
                60,
                false,
            ],
            [
                100,
                false,
            ],
        ];
    }


    /**
     * Tests the isValidHour method.
     *
     * @dataProvider provideHours
     */
    public function testIsValidHour(int $Hour, bool $expectedResult): void
    {
        $reflectionMethod = (new ReflectionClass(StringManipulation::class))->getMethod('isValidHour');

        /**
         * @noinspection PhpExpressionResultUnusedInspection
         *
         * @psalm-suppress UnusedMethodCall
         */
        $reflectionMethod->setAccessible(true);

        $result = $reflectionMethod->invoke(null, $Hour);

        self::assertSame($expectedResult, $result);
    }
}
