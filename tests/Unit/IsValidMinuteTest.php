<?php

declare(strict_types=1);

namespace MarjovanLier\StringManipulation\Tests\Unit;

use MarjovanLier\StringManipulation\StringManipulation;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @internal
 *
 * @covers StringManipulation::isValidMinute
 */
final class IsValidMinuteTest extends TestCase
{
    /**
     * Provides a set of Minutes to test.
     *
     * @return array<int, array<int, bool|int>>
     *
     * @psalm-return list{list{0, true}, list{30, true}, list{59, true}, list{-1, false}, list{60, false}, list{100,
     *     false}}
     */
    public static function provideMinutes(): array
    {
        return [
            [
                0,
                true,
            ],
            [
                30,
                true,
            ],
            [
                59,
                true,
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
     * Tests the isValidMinute method.
     *
     * @dataProvider provideMinutes
     */
    public function testIsValidMinute(int $Minute, bool $expectedResult): void
    {
        $reflectionMethod = (new ReflectionClass(StringManipulation::class))->getMethod('isValidMinute');

        /**
         * @noinspection PhpExpressionResultUnusedInspection
         *
         * @psalm-suppress UnusedMethodCall
         */
        $reflectionMethod->setAccessible(true);

        $result = $reflectionMethod->invoke(null, $Minute);

        self::assertSame($expectedResult, $result);
    }
}
