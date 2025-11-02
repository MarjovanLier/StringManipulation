<?php

declare(strict_types=1);
use MarjovanLier\StringManipulation\StringManipulation;

/**
 * @return array<int, array<int, string>>
 */
dataset('trimDataProvider', fn(): array => [

    // Basic tests
    [
        ' hello ',
        " \t\n\r\0\x0B",
        'hello',
    ],
    [
        "\thello\t",
        " \t\n\r\0\x0B",
        'hello',
    ],
    [
        "\nhello\n",
        " \t\n\r\0\x0B",
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
        " \t\n\r\0\x0B",
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
