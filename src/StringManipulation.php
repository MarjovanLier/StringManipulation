<?php

declare(strict_types=1);

namespace MarjovanLier\StringManipulation;

use DateTime;
use SensitiveParameter;

/**
 * Class StringManipulation.
 *
 * This class offers a comprehensive suite of static methods for a variety of string operations,
 * making it an indispensable resource for tasks involving string manipulation or validation. Utilising a blend of
 * advanced algorithms and efficient coding practices.
 * Operations include:
 * - String transformation: Modifying the format or structure of a string.
 * - Accent removal: Eliminating accents from characters within a string.
 * - String replacement: Substituting specific substrings within a string with alternative substrings.
 * - Date validation: Verifying the validity of a given date string against a specified format.
 *
 * Each method within this class is meticulously crafted to execute a distinct operation,
 * allowing for their independent utilisation. This attribute renders the class a versatile
 * tool for any string manipulation or validation endeavours.
 *
 * Note: This class is exempt from the UnusedClass warning by the Psalm static analysis tool,
 * indicating that while it may not be directly instantiated or invoked within the codebase,
 * it is designed for use wherever necessary, thus justifying the exemption.
 *
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
     * - Converting the string to lower case using `strtolower`.
     * - Replacing various special characters with spaces (e.g., '{', '}', '(', ')', etc.).
     * - Replacing underscores with spaces.
     * - Removing accents from characters.
     * - Reducing multiple spaces to a single space.
     *
     * For example, a string like "John_Doe@Example.com" will be transformed to "john doe example com".
     *
     * This function is useful when preparing a string for database search operations, where the string needs to be
     * in a standardized format to ensure accurate search results.
     *
     * @param null|string $words The input string to be transformed for search. If null, the function will return null.
     *
     * @return null|string The transformed string suitable for database search, or null if the input was null.
     *
     * @example
     * searchWords('John_Doe@Example.com'); // Returns 'john doe example com'
     * searchWords(null); // Returns null
     */
    public static function searchWords(?string $words): ?string
    {
        // If the input string is null, return null
        if ($words === null) {
            return null;
        }

        // Apply the name fixing standards to the input string
        $words = static::nameFix($words);

        // If the name fixed string is not null, perform further transformations.
        if ($words !== null) {
            // Replace various special characters with spaces and convert the string to lowercase
            $words = strtolower(
                static::strReplace(['{', '}', '(', ')', '/', '\\', '@', ':', '"', '?', ',', '.'], ' ', $words),
            );
        }

        // Remove accents from characters within the string
        $words = static::removeAccents(($words ?? ''));

        // Replace underscores with spaces
        $words = static::strReplace('_', ' ', $words);

        // Reduce spaces to a single space and return the transformed string.
        return trim((preg_replace('# {2,}#', ' ', $words) ?? ''));
    }


    /**
     * Fixes a given last name to conform to specific naming standards.
     *
     * This function performs several transformations on the input last name:
     * - Converts the string from UTF-8 to ANSI encoding using the `utf8Ansi` function.
     * - Removes any accents or special characters using the `removeAccents` function.
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
     *
     * @example
     * nameFix('mcdonald'); // Returns 'McDonald'
     * nameFix('van der waals'); // Returns 'van der Waals'
     * nameFix(null); // Returns null
     */
    public static function nameFix(#[SensitiveParameter] ?string $lastName): ?string
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
     * This function takes a string as an input, which should be encoded in UTF-8. It then converts each UTF-8 encoded
     * character in the string to its corresponding ANSI encoded character.
     *
     * The function uses the predefined constant UTF8_ANSI2 as a mapping array for character conversion. Each key in
     * this array is a UTF-8 encoded character, and its corresponding value is the ANSI encoded character.
     *
     * For example, the UTF-8 encoded character 'é' will be converted to the ANSI encoded character 'e'.
     *
     * This function is useful when you need to convert a string from UTF-8 encoding to ANSI encoding, especially when
     * processing text for storage in a database or file system that does not support UTF-8 encoding.
     *
     * @param null|string $valor The input string to be converted from UTF-8 to ANSI. If null, the function will return
     *                           an empty string.
     *
     * @return string The converted string in ANSI encoding.
     *
     * @psalm-suppress PossiblyUnusedMethod,UnusedParam
     */
    public static function utf8Ansi(?string $valor = ''): string
    {
        if ($valor === null) {
            return '';
        }

        return strtr($valor, self::UTF8_ANSI2);
    }


    /**
     * Removes accents and special characters from a string.
     *
     * This function uses the predefined constants REMOVE_ACCENTS_FROM and REMOVE_ACCENTS_TO
     * as mapping arrays for character replacement. It replaces each character in the
     * REMOVE_ACCENTS_FROM array with its corresponding character in the REMOVE_ACCENTS_TO array.
     *
     * For example, accented characters like 'À', 'Á', 'Â', etc., will be replaced by 'A',
     * and special characters like '*', '?', '’', etc., will be replaced by spaces or other characters.
     *
     * This function is useful when you need to remove accents and special characters from a string,
     * especially when processing text for comparison or storage in a standardized format.
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
     * Replaces all occurrences of the search string(s) with the corresponding replacement string(s) in the subject.
     *
     * This method acts as a more precise version of the built-in PHP str_replace function. It accepts three
     * parameters:
     * - $search: The value(s) being searched for within the subject. This can be a single string or an array of
     * strings.
     * - $replace: The replacement value(s) for the found search values. This can be a single string or an array of
     * strings.
     * - $subject: The string within which the search and replacement is to be performed.
     *
     * It returns a new string wherein every occurrence of each search value has been substituted with the
     * corresponding replacement value.
     *
     * This method is particularly useful for replacing multiple distinct substrings within a string, or when the same
     * set of replacements needs to be applied across multiple strings.
     *
     * @param string|string[] $search The value(s) being searched for within the subject.
     * @param string|string[] $replace The replacement value(s) for the found search values.
     * @param string $subject The string within which the search and replacement are to be performed.
     *
     * @return string A string where every occurrence of each search value has been substituted with the corresponding
     *     replacement value.
     *
     * @psalm-suppress PossiblyUnusedMethod,UnusedParam
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
     * This function is useful when validating date inputs, where the date needs to be checked for both format and
     * logical validity.
     *
     * @param string $date The date string to validate. This should be a string representing a date.
     * @param string $format The expected date format. Default is 'Y-m-d H:i:s'. This should be a string representing
     *                       the expected format of the date.
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

        if (!$dateTime instanceof DateTime || $dateTime->format($format) !== $date) {
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
     * This function is a stricter version of the built-in PHP trim function. It takes a string and an optional string
     * of characters as inputs. The function trims the specified characters from the beginning and end of the input
     * string. If no characters are specified, it trims whitespace characters by default. The characters that are
     * trimmed by default are: " " (ASCII 32 (0x20)), an ordinary space.
     * "\t" (ASCII 9 (0x09)), a tab.
     * "\n" (ASCII 10 (0x0A)), a new line (line feed).
     * "\r" (ASCII 13 (0x0D)), a carriage return.
     * "\0" (ASCII 0 (0x00)), the NUL-byte.
     * "\x0B" (ASCII 11 (0x0B)), a vertical tab.
     *
     * This function is useful when you need to remove certain characters from the start and end of a string,
     * especially when cleaning up user input or processing text.
     *
     * @param string $string The input string from which characters will be trimmed.
     * @param string $characters Optional. The characters to be trimmed from the string. Defaults to " \t\n\r\0\x0B".
     *
     * @return string Returns the trimmed string.
     */
    public static function trim(string $string, string $characters = " \t\n\r\0\x0B"): string
    {
        return trim($string, $characters);
    }


    /**
     * Check if the time part of a date is valid.
     *
     * This function takes an associative array as an input, which represents the different parts of a time.
     * The array should contain the following keys: 'year', 'month', 'day', 'hour', 'minute', and 'second'.
     * Each key should have an integer value representing the corresponding part of a time.
     *
     * The function checks if the 'hour', 'minute', and 'second' parts of the time are within their valid ranges.
     * If all these parts are within their valid ranges, the function returns true; otherwise, it returns false.
     *
     * This function is useful when validating date and time inputs, where the time part needs to be checked for
     * validity.
     *
     * @param array{
     *     year: int,
     *     month: int,
     *     day: int,
     *     hour: int,
     *     minute: int,
     *     second: int
     * } $dateParts An associative array containing the date and time parts. Each key should have an integer value.
     *
     * @return bool Returns true if the time part is valid (i.e., 'hour' is within the range 0-23, 'minute' is within
     *              the range 0-59, and 'second' is within the range 0-59), false otherwise.
     */
    private static function isValidTimePart(array $dateParts): bool
    {
        if (!self::isValidHour($dateParts['hour']) || !self::isValidMinute($dateParts['minute'])) {
            return false;
        }

        return self::isValidSecond($dateParts['second']);
    }


    /**
     * Check if the given hour is valid.
     *
     * This function takes an integer as an input, which represents the hour part of a time.
     * It checks if the given hour is within the valid range for hours (0-23).
     * If the hour is within this range, the function returns true; otherwise, it returns false.
     *
     * This function is useful when validating time inputs, where the hour part needs to be checked for validity.
     *
     * @param int $hour The hour to be validated. This should be an integer representing the hour part of a time.
     *
     * @return bool Returns true if the hour is valid (i.e., within the range 0-23), false otherwise.
     */
    private static function isValidHour(int $hour): bool
    {
        return $hour >= 0 && $hour <= 23;
    }


    /**
     * Check if the given minute is valid.
     *
     * This function takes an integer as an input, which represents the minutes part of a time.
     * It checks if the given minute is within the valid range for minutes (0-59).
     * If the minute is within this range, the function returns true; otherwise, it returns false.
     *
     * This function is useful when validating time inputs, where the minutes part needs to be checked for validity.
     *
     * @param int $minute The minute to be validated. This should be an integer representing the minutes part of a time.
     *
     * @return bool Returns true if the minute is valid (i.e., within the range 0-59), false otherwise.
     */
    private static function isValidMinute(int $minute): bool
    {
        return $minute >= 0 && $minute <= 59;
    }


    /**
     * Check if the given second is valid.
     *
     * This function takes an integer as an input, which represents the seconds part of a time.
     * It checks if the given second is within the valid range for seconds (0-59).
     * If the second is within this range, the function returns true; otherwise, it returns false.
     *
     * This function is useful when validating time inputs, where the seconds part needs to be checked for validity.
     *
     * @param int $second The second to be validated. This should be an integer representing the seconds part of a time.
     *
     * @return bool Returns true if the second is valid (i.e., within the range 0-59), false otherwise.
     */
    private static function isValidSecond(int $second): bool
    {
        return $second >= 0 && $second <= 59;
    }
}
