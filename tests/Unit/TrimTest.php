<?php

declare(strict_types=1);
use MarjovanLier\StringManipulation\StringManipulation;

const DEFAULT_TRIM_CHARACTERS = " \t\n\r\0\x0B";
/**
 * @return array<int, array<int, string>>
 */
dataset('trimDataProvider', fn(): array => [

    // Basic tests
    [
        ' hello ',
        DEFAULT_TRIM_CHARACTERS,
        'hello',
    ],
    [
        "\thello\t",
        DEFAULT_TRIM_CHARACTERS,
        'hello',
    ],
    [
        "\nhello\n",
        DEFAULT_TRIM_CHARACTERS,
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
        DEFAULT_TRIM_CHARACTERS,
        '',
    ],
    // Tests with no characters to trim
    [
        'hello',
        'z',
        'hello',
    ],
]);
test('trim', function (string $input, string $characters, mixed $expected): void {
    expect(StringManipulation::trim($input, $characters))->toBe($expected);

})->with('trimDataProvider');
