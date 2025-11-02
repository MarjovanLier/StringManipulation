<?php

declare(strict_types=1);

namespace MarjovanLier\StringManipulation\Tests\Unit;

use MarjovanLier\StringManipulation\StringManipulation;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversMethod(\MarjovanLier\StringManipulation\StringManipulation::class, 'isValidDate')]
final class IsValidDateTest extends TestCase
{
    private const string DATE_TIME_FORMAT = 'Y-m-d H:i:s';

    private const string TIME_FORMAT = 'H:i:s';


    /**
     * Provides a set of valid dates and their respective formats.
     *
     * @return array<array<string>>
     *
     * @psalm-return list{list{'2023-09-06 12:30:00', 'Y-m-d H:i:s'}, list{'06-09-2023', 'd-m-Y'}, list{'2023-09-06',
     *     'Y-m-d'}, list{'2012-02-28', 'Y-m-d'}, list{'00:00:00', 'H:i:s'}, list{'23:59:59', 'H:i:s'},
     *     list{'29-02-2012', 'd-m-Y'}, list{'28-02-2023', 'd-m-Y'}, list{'2023-02-28', 'Y-m-d'}}
     *
     * @suppress PossiblyUnusedMethod
     */
    public static function provideValidDates(): array
    {
        return [
            [
                '2023-09-06 12:30:00',
                self::DATE_TIME_FORMAT,
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
                self::TIME_FORMAT,
            ],
            [
                '23:59:59',
                self::TIME_FORMAT,
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
        ];
    }


    /**
     * Provides a set of invalid dates and their respective formats.
     *
     * @return array<array<string>>
     *
     * @psalm-return list<array{0: string, 1: string}>
     */
    public static function provideInvalidDates(): array
    {
        return [
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
                self::DATE_TIME_FORMAT,
            ],
            [
                '2012-02-30 25:12:12',
                self::DATE_TIME_FORMAT,
            ],
            [
                '24:00:00',
                self::TIME_FORMAT,
            ],
            [
                '23:60:00',
                self::TIME_FORMAT,
            ],
            [
                '23:59:60',
                self::TIME_FORMAT,
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
                self::DATE_TIME_FORMAT,
            ],
            [
                '2012-02-28 24:12:12',
                self::DATE_TIME_FORMAT,
            ],
            [
                '2012-02-28 23:60:12',
                self::DATE_TIME_FORMAT,
            ],
            [
                '2012-02-28 23:59:60',
                self::DATE_TIME_FORMAT,
            ],
            [
                '0000-00-00 12:30:00',
                self::DATE_TIME_FORMAT,
            ],
            [
                '2023-09-06 12:61:12',
                self::DATE_TIME_FORMAT,
            ],
            [
                '2023-09-06 12:59:61',
                self::DATE_TIME_FORMAT,
            ],
            [
                '2023-09-06 25:30:00',
                self::DATE_TIME_FORMAT,
            ],
            [
                '2023-02-30 12:30:00',
                self::DATE_TIME_FORMAT,
            ],
            [
                '2023-02-30',
                'Y-m-d',
            ],
            [
                '25:30:00',
                self::TIME_FORMAT,
            ],
            [
                '12:61:00',
                self::TIME_FORMAT,
            ],
            [
                '12:59:61',
                self::TIME_FORMAT,
            ],
        ];
    }


    /**
     * @dataProvider provideValidDates
     */
    #[DataProvider('provideValidDates')]
    public function testValidDates(string $date, string $format): void
    {
        self::assertTrue(StringManipulation::isValidDate($date, $format));
    }


    /**
     * @dataProvider provideInvalidDates
     */
    #[DataProvider('provideInvalidDates')]
    public function testInvalidDates(string $date, string $format): void
    {
        self::assertFalse(StringManipulation::isValidDate($date, $format));
    }
}
