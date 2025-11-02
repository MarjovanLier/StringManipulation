<?php

declare(strict_types=1);

use MarjovanLier\StringManipulation\StringManipulation;

/**
 * Provides a set of valid dates and their respective formats.
 *
 * @return array<array<string>>
 *
 * @psalm-return list{list{'2023-09-06 12:30:00', 'Y-m-d H:i:s'}, list{'06-09-2023', 'd-m-Y'}, list{'2023-09-06',
 *     'Y-m-d'}, list{'2012-02-28', 'Y-m-d'}, list{'00:00:00', 'H:i:s'}, list{'23:59:59', 'H:i:s'},
 *     list{'29-02-2012', 'd-m-Y'}, list{'28-02-2023', 'd-m-Y'}, list{'2023-02-28', 'Y-m-d'}}
 */
dataset('provideValidDates', fn(): array => [

    [
        '2023-09-06 12:30:00',
        'Y-m-d H:i:s',
    ],
    [
        '06-09-2023',
        'd-m-Y',
    ],
    [
        '2023-09-06',
        'Y-m-d',
    ],
    [
        '2012-02-28',
        'Y-m-d',
    ],
    [
        '00:00:00',
        'H:i:s',
    ],
    [
        '23:59:59',
        'H:i:s',
    ],
    [
        '29-02-2012',
        'd-m-Y',
    ],
    [
        '28-02-2023',
        'd-m-Y',
    ],
    [
        '2023-02-28',
        'Y-m-d',
    ],
]);
/**
 * Provides a set of invalid dates and their respective formats.
 *
 * @return array<array<string>>
 *
 * @psalm-return list<array{0: string, 1: string}>
 */
dataset('provideInvalidDates', fn(): array => [

    [
        '2023-09-06 12:30:00',
        'Y-m-d',
    ],
    [
        '2023-09-06',
        'd-m-Y',
    ],
    [
        '06-09-2023',
        'Y-m-d',
    ],
    [
        '2012-02-30 12:12:12',
        'Y-m-d H:i:s',
    ],
    [
        '2012-02-30 25:12:12',
        'Y-m-d H:i:s',
    ],
    [
        '24:00:00',
        'H:i:s',
    ],
    [
        '23:60:00',
        'H:i:s',
    ],
    [
        '23:59:60',
        'H:i:s',
    ],
    [
        '30-02-2012',
        'd-m-Y',
    ],
    [
        '31-04-2023',
        'd-m-Y',
    ],
    [
        '2012-02-30 12:12:12',
        'Y-m-d H:i:s',
    ],
    [
        '2012-02-28 24:12:12',
        'Y-m-d H:i:s',
    ],
    [
        '2012-02-28 23:60:12',
        'Y-m-d H:i:s',
    ],
    [
        '2012-02-28 23:59:60',
        'Y-m-d H:i:s',
    ],
    [
        '0000-00-00 12:30:00',
        'Y-m-d H:i:s',
    ],
    [
        '2023-09-06 12:61:12',
        'Y-m-d H:i:s',
    ],
    [
        '2023-09-06 12:59:61',
        'Y-m-d H:i:s',
    ],
    [
        '2023-09-06 25:30:00',
        'Y-m-d H:i:s',
    ],
    [
        '2023-02-30 12:30:00',
        'Y-m-d H:i:s',
    ],
    [
        '2023-02-30',
        'Y-m-d',
    ],
    [
        '25:30:00',
        'H:i:s',
    ],
    [
        '12:61:00',
        'H:i:s',
    ],
    [
        '12:59:61',
        'H:i:s',
    ],
]);
test('valid dates', function (string $date, string $format): void {
    expect(StringManipulation::isValidDate($date, $format))->toBeTrue();

})->with('provideValidDates');
test('invalid dates', function (string $date, string $format): void {
    expect(StringManipulation::isValidDate($date, $format))->toBeFalse();
})->with('provideInvalidDates');
