<?php

declare(strict_types=1);

namespace MarjovanLier\StringManipulation\Tests\Unit;

use MarjovanLier\StringManipulation\StringManipulation;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \MarjovanLier\StringManipulation\StringManipulation::isValidDate
 */
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


    /**
     * Test edge cases and boundary conditions for date validation.
     */
    public function testDateValidationEdgeCases(): void
    {
        // Leap year edge cases
        self::assertTrue(StringManipulation::isValidDate('2000-02-29', 'Y-m-d')); // Leap year (divisible by 400)
        self::assertTrue(StringManipulation::isValidDate('2004-02-29', 'Y-m-d')); // Leap year (divisible by 4)
        self::assertFalse(StringManipulation::isValidDate('1900-02-29', 'Y-m-d')); // Not leap year (divisible by 100, not 400)
        self::assertFalse(StringManipulation::isValidDate('2023-02-29', 'Y-m-d')); // Not leap year

        // Month boundary cases
        self::assertTrue(StringManipulation::isValidDate('2023-01-31', 'Y-m-d')); // January 31 days
        self::assertFalse(StringManipulation::isValidDate('2023-02-31', 'Y-m-d')); // February doesn't have 31 days
        self::assertTrue(StringManipulation::isValidDate('2023-03-31', 'Y-m-d')); // March 31 days
        self::assertFalse(StringManipulation::isValidDate('2023-04-31', 'Y-m-d')); // April only has 30 days
        self::assertTrue(StringManipulation::isValidDate('2023-05-31', 'Y-m-d')); // May 31 days
        self::assertFalse(StringManipulation::isValidDate('2023-06-31', 'Y-m-d')); // June only has 30 days
        self::assertTrue(StringManipulation::isValidDate('2023-12-31', 'Y-m-d')); // December 31 days

        // Time boundary cases
        self::assertTrue(StringManipulation::isValidDate('00:00:00', 'H:i:s')); // Midnight
        self::assertTrue(StringManipulation::isValidDate('23:59:59', 'H:i:s')); // Last second of day
        self::assertFalse(StringManipulation::isValidDate('24:00:00', 'H:i:s')); // Invalid hour
        self::assertFalse(StringManipulation::isValidDate('23:60:00', 'H:i:s')); // Invalid minute
        self::assertFalse(StringManipulation::isValidDate('23:59:60', 'H:i:s')); // Invalid second

        // Year boundary cases
        self::assertTrue(StringManipulation::isValidDate('0001-01-01', 'Y-m-d')); // Minimum year
        self::assertTrue(StringManipulation::isValidDate('9999-12-31', 'Y-m-d')); // Maximum year
        self::assertFalse(StringManipulation::isValidDate('0000-01-01', 'Y-m-d')); // Year 0 doesn't exist

        // Complex format edge cases
        self::assertTrue(StringManipulation::isValidDate('31-12-2023 23:59:59', 'd-m-Y H:i:s'));
        self::assertFalse(StringManipulation::isValidDate('32-12-2023 23:59:59', 'd-m-Y H:i:s'));

        // Different separators
        self::assertTrue(StringManipulation::isValidDate('2023/12/31', 'Y/m/d'));
        self::assertTrue(StringManipulation::isValidDate('2023.12.31', 'Y.m.d'));
        self::assertTrue(StringManipulation::isValidDate('31.12.2023', 'd.m.Y'));
    }


    /**
     * Test performance and stress scenarios for date validation.
     */
    public function testDateValidationPerformance(): void
    {
        // Large batch of date validations
        $dates = [];
        for ($year = 2020; $year <= 2025; ++$year) {
            for ($month = 1; $month <= 12; ++$month) {
                for ($day = 1; $day <= 28; ++$day) { // Safe range for all months
                    $dates[] = sprintf('%04d-%02d-%02d', $year, $month, $day);
                }
            }
        }

        $startTime = microtime(true);
        $validCount = 0;
        foreach ($dates as $date) {
            if (StringManipulation::isValidDate($date, 'Y-m-d')) {
                ++$validCount;
            }
        }

        $duration = microtime(true) - $startTime;

        self::assertEquals(count($dates), $validCount); // All should be valid
        self::assertLessThan(2.0, $duration, 'Batch date validation should complete efficiently');

        // Stress test with complex formats
        $complexDates = [
            '2023-12-31 23:59:59',
            '31-12-2023 00:00:00',
            '2023/01/01 12:30:45',
            '01.01.2023 06:15:30',
        ];

        $formats = [
            'Y-m-d H:i:s',
            'd-m-Y H:i:s',
            'Y/m/d H:i:s',
            'd.m.Y H:i:s',
        ];

        $startTime = microtime(true);
        foreach ($complexDates as $index => $date) {
            self::assertTrue(StringManipulation::isValidDate($date, $formats[$index]));
        }

        $duration = microtime(true) - $startTime;
        self::assertLessThan(0.1, $duration, 'Complex format validation should be fast');
    }


    /**
     * Test negative flow scenarios for date validation.
     */
    public function testDateValidationNegativeFlow(): void
    {
        // Malformed dates
        $malformedDates = [
            ['', 'Y-m-d'],
            ['not-a-date', 'Y-m-d'],
            ['2023-13-01', 'Y-m-d'], // Invalid month
            ['2023-00-01', 'Y-m-d'], // Invalid month
            ['2023-01-00', 'Y-m-d'], // Invalid day
            ['2023-01-32', 'Y-m-d'], // Invalid day
            ['2023/01/01', 'Y-m-d'], // Wrong separator
            ['2023-1-1', 'Y-m-d'], // Missing zero padding
        ];

        foreach ($malformedDates as [$date, $format]) {
            self::assertFalse(StringManipulation::isValidDate($date, $format), sprintf("Date '%s' should be invalid for format '%s'", $date, $format));
        }

        // Time validation edge cases
        $invalidTimes = [
            ['25:00:00', 'H:i:s'], // Hour too high
            ['12:60:00', 'H:i:s'], // Minute too high
            ['12:30:60', 'H:i:s'], // Second too high
            ['-1:30:00', 'H:i:s'], // Negative hour
            ['12:-1:00', 'H:i:s'], // Negative minute
            ['12:30:-1', 'H:i:s'], // Negative second
        ];

        foreach ($invalidTimes as [$time, $format]) {
            self::assertFalse(StringManipulation::isValidDate($time, $format), sprintf("Time '%s' should be invalid for format '%s'", $time, $format));
        }

        // Format mismatch scenarios
        $formatMismatches = [
            ['2023-01-01', 'd-m-Y'], // Date format doesn't match
            ['01-01-2023', 'Y-m-d'], // Date format doesn't match
            ['2023-01-01 12:30:00', 'Y-m-d'], // Extra time component
            ['12:30:00', 'Y-m-d H:i:s'], // Missing date component
            ['2023', 'Y-m-d'], // Incomplete date
            ['01-01', 'Y-m-d'], // Incomplete date
        ];

        foreach ($formatMismatches as [$date, $format]) {
            self::assertFalse(StringManipulation::isValidDate($date, $format), sprintf("Date '%s' should not match format '%s'", $date, $format));
        }
    }
}
