<?php

declare(strict_types=1);

namespace MarjovanLier\StringManipulation;

use DateTime;

/**
 * @psalm-suppress UnusedClass
 */
class StringManipulation
{
    use AccentNormalization;
    use UnicodeMappings;


    /**
     * Transforms a string into a format suitable for database searching.
     *
     * This function performs several transformations on the input string to make it suitable for
     * searching within a database. The transformations include:
     * - Applying the name fixing standards via the `nameFix` function. (Refer to its PHPDoc for details.)
     * - Converting the string to lowercase.
     * - Replacing various special characters with spaces (e.g., '{', '}', '(', ')', etc.).
     * - Replacing underscores with spaces.
     * - Removing accents from characters.
     * - Reducing multiple spaces to a single space.
     *
     * For example, a string like "John_Doe@Example.com" will be transformed to "john doe example com".
     *
     * @param null|string $words The input string to be transformed for search. If null, the function will return null.
     *
     * @return null|string The transformed string suitable for database search, or null if the input was null.
     */
    public static function searchWords(?string $words): ?string
    {
        if ($words === null) {
            return null;
        }

        $words = static::nameFix($words);

        if ($words !== null) {
            $words = strtolower(
                static::strReplace(['{', '}', '(', ')', '/', '\\', '@', ':', '"', '?', ',', '.'], ' ', $words),
            );
        }

        $words = static::removeAccents(($words ?? ''));
        $words = static::strReplace('_', ' ', $words);

        return trim((preg_replace('# {2,}#', ' ', $words) ?? ''));
    }


    /**
     * Fixes a given last name to conform to specific naming standards.
     *
     * This function performs several transformations on the input last name:
     * - Uses the `utf8Ansi` function, which might convert the string from UTF-8 to ANSI encoding.
     * - Removes any accents or special characters.
     * - Reduces multiple spaces to a single space.
     * - Fixes names starting with 'mc' (without a following space) by adding a space after the prefix.
     * - Similarly, fixes names starting with 'mac' (without a following space).
     * - Capitalizes each part of a hyphenated name.
     * - Corrects common prefixes like 'van', 'von', 'de', etc. to have proper casing.
     *
     * For example, a name like "mcdonald" will be transformed to "McDonald" and
     * "van der waals" will be transformed to "van der Waals".
     *
     * @param null|string $lastName The last name to be fixed. If null, the function will return null.
     *
     * @return null|string The fixed last name according to the standards, or null if the input was null.
     */
    public static function nameFix(?string $lastName): ?string
    {
        if ($lastName === null) {
            return null;
        }

        $lastName = trim(static::utf8Ansi($lastName));
        $lastName = static::removeAccents($lastName);

        $lastName = (preg_replace('# {2,}#', ' ', $lastName) ?? '');

        $mcFix = false;
        $lowerLastName = strtolower($lastName);
        if (preg_match('#mc(?! )#', $lowerLastName) === 1) {
            $mcFix = true;
            $lastName = static::strReplace('mc', 'mc ', $lowerLastName);
        }

        $macFix = false;
        $lowerLastName = strtolower($lastName);
        if (preg_match('#mac(?! )#', $lowerLastName) === 1) {
            $macFix = true;
            $lastName = static::strReplace('mac', 'mac ', $lowerLastName);
        }

        $lastName = implode('-', array_map('ucwords', explode('-', strtolower($lastName))));

        $lastName = preg_replace(
            [
                '#van #i',
                '#von #i',
                '# den #i',
                '# der #i',
                '# des #i',
                '#de #i',
                '#du #i',
                '#la #i',
                '#le #i',
            ],
            [
                'van ',
                'von ',
                ' den ',
                ' der ',
                ' des ',
                'de ',
                'du ',
                'la ',
                'le ',
            ],
            $lastName,
        );

        if ($mcFix) {
            $lastName = static::strReplace('Mc ', 'Mc', ($lastName ?? ''));
        }

        if ($macFix) {
            return static::strReplace('Mac ', 'Mac', ($lastName ?? ''));
        }

        return $lastName;
    }


    /**
     * Converts UTF-8 encoded characters to their ANSI counterparts.
     *
     * @param null|string $valor The input string.
     *
     * @return string The converted string.
     *
     * @psalm-suppress PossiblyUnusedMethod
     * @psalm-suppress UnusedParam
     */
    public static function utf8Ansi(?string $valor = ''): string
    {
        if ($valor === null) {
            return '';
        }

        return strtr($valor, self::UTF8_ANSI2);
    }


    /**
     * Removes accents and special characters from a given string.
     *
     * This function uses predefined constants REMOVE_ACCENTS_FROM and REMOVE_ACCENTS_TO
     * as mapping arrays for character replacement. It replaces each character in the
     * REMOVE_ACCENTS_FROM array with its corresponding character in the REMOVE_ACCENTS_TO array.
     *
     * For example, accented characters like 'À', 'Á', 'Â', etc., will be replaced by 'A',
     * and special characters like '*', '?', '’', etc., will be replaced by spaces or other characters.
     *
     * @param string $str The input string from which accents and special characters need to be removed.
     *
     * @return string The transformed string without accents and special characters.
     *
     * @see REMOVE_ACCENTS_FROM
     * @see REMOVE_ACCENTS_TO
     */
    public static function removeAccents(string $str): string
    {
        return static::strReplace([...self::REMOVE_ACCENTS_FROM, '  '], [...self::REMOVE_ACCENTS_TO, ' '], $str);
    }


    /**
     * Replace all occurrences of the search string(s) with the corresponding replacement string(s) in the subject.
     *
     * @param string|string[] $search The value(s) being searched for in the subject.
     * @param string|string[] $replace The replacement value(s) to replace found search values.
     * @param string $subject The string being searched and replaced on.
     *
     * @psalm-suppress PossiblyUnusedMethod - This method might be used in contexts not detected by Psalm.
     * @psalm-suppress UnusedParam - Parameters are used by the underlying `str_replace` function.
     */
    public static function strReplace(array|string $search, array|string $replace, string $subject): string
    {
        return str_replace($search, $replace, $subject);
    }


    /**
     * Validates a date string against a specified format and checks for logical errors.
     *
     * This function checks if the provided date string matches the given format and if the date is logically valid.
     * It uses the DateTime::createFromFormat() method to create a DateTime object from the given date string and
     * format. If the date string and the format match, it reformats the date into 'Y-m-d' format and extracts the day,
     * month, and year.  It then checks if these constitute a valid date using checkdate(). If they do, it
     * returns true; otherwise, it returns false.
     *
     * @param string $date The date string to validate.
     * @param string $format The expected date format. Default is 'Y-m-d H:i:s'.
     *
     * @return bool Returns true if the date string matches the format and is logically valid, false otherwise.
     *
     * @example
     * isValidDate('2023-09-06 12:30:00');          // true
     * isValidDate('2012-02-28', 'Y-m-d');          // true
     * isValidDate('2012-02-30 12:12:12');          // false
     * isValidDate('2023-09-06', 'Y-m-d');          // true
     * isValidDate('06-09-2023', 'd-m-Y');          // true
     * isValidDate('2023-09-06 12:30:00', 'Y-m-d'); // false
     */
    public static function isValidDate(string $date, string $format = 'Y-m-d H:i:s'): bool
    {
        $dateTime = DateTime::createFromFormat($format, $date);

        if (!$dateTime instanceof DateTime) {
            return false;
        }

        if ($dateTime->format($format) !== $date) {
            return false;
        }

        /**
         * @var array{year: int, month: int, day: int, hour: int, minute: int, second: int} $dateParts
         */
        $dateParts = date_parse($dateTime->format('Y-m-d H:i:s'));

        return self::isValidTimePart($dateParts);
    }


    /**
     * Trims characters from the beginning and end of a string.
     *
     * @param string $string The input string.
     * @param string $characters Optional characters to trim, defaults to space or blank characters.
     *
     * @return string The trimmed string.
     */
    public static function trim(string $string, string $characters = " \t\n\r\0\x0B"): string
    {
        return trim($string, $characters);
    }


    /**
     * Check if the time part of a date is valid.
     *
     * @param array{
     *     year: int,
     *     month: int,
     *     day: int,
     *     hour: int,
     *     minute: int,
     *     second: int
     * } $dateParts An array containing the date and time parts.
     *
     * @return bool Returns true if the time part is valid, false otherwise.
     */
    private static function isValidTimePart(array $dateParts): bool
    {
        if (!self::isValidHour($dateParts['hour'])) {
            return false;
        }

        if (!self::isValidMinute($dateParts['minute'])) {
            return false;
        }

        return self::isValidSecond($dateParts['second']);
    }


    /**
     * Check if the given hour is valid.
     *
     * @return bool Returns true if the hour is valid, false otherwise.
     */
    private static function isValidHour(int $hour): bool
    {
        return $hour >= 0 && $hour <= 23;
    }


    /**
     * Check if the given minute is valid.
     *
     * @return bool Returns true if the minute is valid, false otherwise.
     */
    private static function isValidMinute(int $minute): bool
    {
        return $minute >= 0 && $minute <= 59;
    }


    /**
     * Check if the given second is valid.
     *
     * @return bool Returns true if the second is valid, false otherwise.
     */
    private static function isValidSecond(int $second): bool
    {
        return $second >= 0 && $second <= 59;
    }
}
