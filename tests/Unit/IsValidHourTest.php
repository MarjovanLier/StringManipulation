<?php

declare(strict_types=1);
use MarjovanLier\StringManipulation\StringManipulation;

/**
 * Provides a set of Hours to test.
 *
 * @return array<int, array<int, bool|int>>
 *
 * @psalm-return list{list{0, true}, list{12, true}, list{23, true}, list{30, false}, list{59, false}, list{-1,
 *     false}, list{60, false}, list{100, false}}
 */
dataset('provideHours', fn(): array => [
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
]);
test('is valid hour', function (int $hour, bool $expectedResult): void {
    $reflectionMethod = (new ReflectionClass(StringManipulation::class))->getMethod('isValidHour');

    /**
     * @noinspection PhpExpressionResultUnusedInspection
     *
     * @psalm-suppress UnusedMethodCall
     */
    $reflectionMethod->setAccessible(true);

    $result = $reflectionMethod->invoke(null, $hour);

    expect($result)->toBe($expectedResult);
})->with('provideHours');
