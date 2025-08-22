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
 * @covers \MarjovanLier\StringManipulation\StringManipulation::isValidSecond
 */
final class IsValidSecondTest extends TestCase
{
    /**
     * Provides a set of seconds to test.
     *
     * @return array<int, array<int, bool|int>>
     *
     * @psalm-return list{list{0, true}, list{30, true}, list{59, true}, list{-1, false}, list{60, false}, list{100,
     *     false}}
     */
    public static function provideSeconds(): array
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
     * Tests the isValidSecond method.
     *
     * @dataProvider provideSeconds
     */
    #[DataProvider('provideSeconds')]
    public function testIsValidSecond(int $second, bool $expectedResult): void
    {
        $reflectionMethod = (new ReflectionClass(StringManipulation::class))->getMethod('isValidSecond');

        $result = $reflectionMethod->invoke(null, $second);

        self::assertSame($expectedResult, $result);
    }
}
