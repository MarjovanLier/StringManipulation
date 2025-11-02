<?php

declare(strict_types=1);
use MarjovanLier\StringManipulation\StringManipulation;

/**
 * Provides a set of seconds to test.
 *
 * @return array<int, array<int, bool|int>>
 *
 * @psalm-return list{list{0, true}, list{30, true}, list{59, true}, list{-1, false}, list{60, false}, list{100,
 *     false}}
 */
dataset('provideSeconds', fn(): array => [

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
test('is valid second', function (int $second, bool $expectedResult): void {
    $reflectionMethod = (new ReflectionClass(StringManipulation::class))->getMethod('isValidSecond');

    $result = $reflectionMethod->invoke(null, $second);

    expect($result)->toBe($expectedResult);
})->with('provideSeconds');
