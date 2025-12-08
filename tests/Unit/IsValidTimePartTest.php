<?php

declare(strict_types=1);

namespace MarjovanLier\StringManipulation\Tests\Unit;

use MarjovanLier\StringManipulation\StringManipulation;
use ReflectionClass;
use ReflectionException;

/**
 * Tests for the private isValidTimePart() method.
 *
 * These tests use Reflection to directly test defensive validation code
 * that is otherwise unreachable through the public API due to DateTime's
 * lenient parsing catching most invalid dates earlier.
 *
 * @internal
 */
final class IsValidTimePartTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test that isValidTimePart returns false for invalid date parts.
     *
     * This covers line 334: checkdate() failure path.
     *
     * @throws ReflectionException
     */
    public function testIsValidTimePartInvalidDateHappyFlow(): void
    {
        $reflectionMethod = $this->getIsValidTimePartMethod();

        // February 30th is not a valid date
        $invalidDateParts = [
            'year' => 2023,
            'month' => 2,
            'day' => 30,
            'hour' => 12,
            'minute' => 30,
            'second' => 45,
        ];

        self::assertFalse($reflectionMethod->invoke(null, $invalidDateParts));
    }

    /**
     * Test that isValidTimePart returns false for invalid hour.
     *
     * This covers line 339: hour validation failure path.
     *
     * @throws ReflectionException
     */
    public function testIsValidTimePartInvalidHourHappyFlow(): void
    {
        $reflectionMethod = $this->getIsValidTimePartMethod();

        // Hour 25 is invalid
        $invalidTimeParts = [
            'year' => 2023,
            'month' => 9,
            'day' => 6,
            'hour' => 25,
            'minute' => 30,
            'second' => 45,
        ];

        self::assertFalse($reflectionMethod->invoke(null, $invalidTimeParts));
    }

    /**
     * Test that isValidTimePart returns false for invalid minute.
     *
     * This also covers line 339: minute validation failure path.
     *
     * @throws ReflectionException
     */
    public function testIsValidTimePartInvalidMinuteHappyFlow(): void
    {
        $reflectionMethod = $this->getIsValidTimePartMethod();

        // Minute 60 is invalid
        $invalidTimeParts = [
            'year' => 2023,
            'month' => 9,
            'day' => 6,
            'hour' => 12,
            'minute' => 60,
            'second' => 45,
        ];

        self::assertFalse($reflectionMethod->invoke(null, $invalidTimeParts));
    }

    /**
     * Test that isValidTimePart returns true for valid date parts.
     *
     * @throws ReflectionException
     */
    public function testIsValidTimePartValidPartsHappyFlow(): void
    {
        $reflectionMethod = $this->getIsValidTimePartMethod();

        // Valid date and time parts
        $validParts = [
            'year' => 2023,
            'month' => 9,
            'day' => 6,
            'hour' => 12,
            'minute' => 30,
            'second' => 45,
        ];

        self::assertTrue($reflectionMethod->invoke(null, $validParts));
    }

    /**
     * Test edge case: February 29 in a leap year is valid.
     *
     * @throws ReflectionException
     */
    public function testIsValidTimePartLeapYearHappyFlow(): void
    {
        $reflectionMethod = $this->getIsValidTimePartMethod();

        // Feb 29, 2024 is valid (2024 is a leap year)
        $leapYearParts = [
            'year' => 2024,
            'month' => 2,
            'day' => 29,
            'hour' => 0,
            'minute' => 0,
            'second' => 0,
        ];

        self::assertTrue($reflectionMethod->invoke(null, $leapYearParts));
    }

    /**
     * Test edge case: February 29 in a non-leap year is invalid.
     *
     * @throws ReflectionException
     */
    public function testIsValidTimePartNonLeapYearNegativeFlow(): void
    {
        $reflectionMethod = $this->getIsValidTimePartMethod();

        // Feb 29, 2023 is invalid (2023 is not a leap year)
        $nonLeapYearParts = [
            'year' => 2023,
            'month' => 2,
            'day' => 29,
            'hour' => 12,
            'minute' => 0,
            'second' => 0,
        ];

        self::assertFalse($reflectionMethod->invoke(null, $nonLeapYearParts));
    }

    /**
     * Test edge case: month 0 is invalid.
     *
     * @throws ReflectionException
     */
    public function testIsValidTimePartMonthZeroNegativeFlow(): void
    {
        $reflectionMethod = $this->getIsValidTimePartMethod();

        // Month 0 is invalid
        $invalidMonthParts = [
            'year' => 2023,
            'month' => 0,
            'day' => 15,
            'hour' => 12,
            'minute' => 30,
            'second' => 0,
        ];

        self::assertFalse($reflectionMethod->invoke(null, $invalidMonthParts));
    }

    /**
     * Test edge case: month 13 is invalid.
     *
     * @throws ReflectionException
     */
    public function testIsValidTimePartMonthThirteenNegativeFlow(): void
    {
        $reflectionMethod = $this->getIsValidTimePartMethod();

        // Month 13 is invalid
        $invalidMonthParts = [
            'year' => 2023,
            'month' => 13,
            'day' => 15,
            'hour' => 12,
            'minute' => 30,
            'second' => 0,
        ];

        self::assertFalse($reflectionMethod->invoke(null, $invalidMonthParts));
    }

    /**
     * Test boundary: hour -1 is invalid.
     *
     * @throws ReflectionException
     */
    public function testIsValidTimePartNegativeHourNegativeFlow(): void
    {
        $reflectionMethod = $this->getIsValidTimePartMethod();

        // Negative hour is invalid
        $invalidHourParts = [
            'year' => 2023,
            'month' => 9,
            'day' => 6,
            'hour' => -1,
            'minute' => 30,
            'second' => 0,
        ];

        self::assertFalse($reflectionMethod->invoke(null, $invalidHourParts));
    }

    /**
     * Test boundary: minute -1 is invalid.
     *
     * @throws ReflectionException
     */
    public function testIsValidTimePartNegativeMinuteNegativeFlow(): void
    {
        $reflectionMethod = $this->getIsValidTimePartMethod();

        // Negative minute is invalid
        $invalidMinuteParts = [
            'year' => 2023,
            'month' => 9,
            'day' => 6,
            'hour' => 12,
            'minute' => -1,
            'second' => 0,
        ];

        self::assertFalse($reflectionMethod->invoke(null, $invalidMinuteParts));
    }

    /**
     * Get the isValidTimePart method via reflection.
     *
     * @throws ReflectionException
     *
     * @return \ReflectionMethod
     */
    private function getIsValidTimePartMethod(): \ReflectionMethod
    {
        $reflectionClass = new ReflectionClass(StringManipulation::class);

        return $reflectionClass->getMethod('isValidTimePart');
    }
}
