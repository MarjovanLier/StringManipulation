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
 * @covers \MarjovanLier\StringManipulation\StringManipulation::isValidMinute
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
    #[DataProvider('provideMinutes')]
    public function testIsValidMinute(int $minute, bool $expectedResult): void
    {
        $reflectionMethod = (new ReflectionClass(StringManipulation::class))->getMethod('isValidMinute');

        $result = $reflectionMethod->invoke(null, $minute);

        self::assertSame($expectedResult, $result);
    }
}
