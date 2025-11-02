<?php

declare(strict_types=1);
use MarjovanLier\StringManipulation\StringManipulation;

/**
 * Provides a set of Minutes to test.
 *
 * @return array<int, array<int, bool|int>>
 *
 * @psalm-return list{list{0, true}, list{30, true}, list{59, true}, list{-1, false}, list{60, false}, list{100,
 *     false}}
 */
dataset('provideMinutes', fn(): array => [
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
]);
test('is valid minute', function (int $minute, bool $expectedResult): void {
    $reflectionMethod = (new ReflectionClass(StringManipulation::class))->getMethod('isValidMinute');

    /**
     * @noinspection PhpExpressionResultUnusedInspection
     *
     * @psalm-suppress UnusedMethodCall
     */
    $reflectionMethod->setAccessible(true);

    $result = $reflectionMethod->invoke(null, $minute);

    expect($result)->toBe($expectedResult);
})->with('provideMinutes');
