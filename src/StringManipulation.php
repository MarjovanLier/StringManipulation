<?php

declare(strict_types=1);

namespace MarjovanLier\StringManipulation;

use DateTime;
use LogicException;

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
final class StringManipulation
{
    use AccentNormalization;
    use UnicodeMappings;

    /**
     * Static property to cache accent replacement mapping for performance optimisation.
     * This is populated lazily in the removeAccents() method and reused across calls.
     * Uses associative array for O(1) character lookup with strtr().
     *
     * @var array<string, string>
     */
    private static array $ACCENTS_REPLACEMENT = [];

    /**
     * Static property to cache combined transformation mapping for searchWords() optimization.
     * Includes accent removal, special character replacement, and case conversion in single pass.
     * Uses associative array for O(1) character lookup with strtr().
     *
     * @var array<string, string>
     */
    private static array $SEARCH_WORDS_MAPPING = [];


    /**
     * Transforms a string into a format suitable for database searching.
     *
     * This function performs several transformations on the input string to make it suitable for
     * searching within a database using a single-pass algorithm for optimal O(n) performance.
     * The transformations include:
     * - Name fixing standards (handles Mc/Mac prefixes and common prefixes)
     * - Converting to lowercase for case-insensitive search
     * - Replacing special characters with spaces (e.g., '{', '}', '(', ')', etc.)
     * - Removing accents from characters for normalized search
     * - Reducing multiple spaces to a single space
     *
     * Optimization: Uses combined character mapping with strtr() for O(1) lookup performance
     * instead of multiple string passes, achieving ~4-5x performance improvement.
     *
     * @param null|string $words The input string to be transformed for search. If null, returns null.
     *
     * @return null|string The transformed string suitable for database search, or null if input was null.
     *
     * @example
     * searchWords('John_Doe@Example.com'); // Returns 'john doe example com'
     * searchWords('McDonald'); // Returns 'mcdonald'
     * searchWords('Café Münchën'); // Returns 'cafe munchen'
     * searchWords(null); // Returns null
     */
    public static function searchWords(?string $words): ?string
    {
        // Early return for null input
        if ($words === null) {
            return null;
        }

        // Build combined transformation mapping on first call
        if (self::$SEARCH_WORDS_MAPPING === []) {
            // Start with accent removal mappings (apply strtolower to ensure all replacements are lowercase)
            $from = [...self::REMOVE_ACCENTS_FROM, '  '];
            $toArray = array_map('strtolower', [...self::REMOVE_ACCENTS_TO, ' ']);

            if (count($from) !== count($toArray)) {
                throw new LogicException('REMOVE_ACCENTS_FROM and REMOVE_ACCENTS_TO arrays must have the same length.');
            }

            $accentMapping = array_combine($from, $toArray);

            // Add special character replacements
            $specialChars = [
                '{' => ' ', '}' => ' ', '(' => ' ', ')' => ' ',
                '/' => ' ', '\\' => ' ', '@' => ' ', ':' => ' ',
                '"' => ' ', '?' => ' ', ',' => ' ', '.' => ' ', '_' => ' ',
            ];

            // Add uppercase to lowercase mappings for common ASCII letters
            $uppercaseMapping = [];
            for ($i = 65; $i <= 90; ++$i) { // A-Z
                $uppercaseMapping[chr($i)] = chr($i + 32); // to a-z
            }

            // Combine all mappings for single-pass transformation
            self::$SEARCH_WORDS_MAPPING = array_merge(
                $accentMapping,
                $specialChars,
                $uppercaseMapping,
            );
        }

        // Apply basic name fixing for Mc/Mac prefixes before character transformation
        $words = self::applyBasicNameFix($words);

        // Single-pass character transformation with strtr() for O(1) lookup
        $words = strtr($words, self::$SEARCH_WORDS_MAPPING);

        // Final cleanup: reduce multiple spaces to single space and trim
        return trim(preg_replace('# {2,}#', ' ', $words) ?? '');
    }


    /**
     * Fixes a given last name to conform to specific naming standards.
     *
     * This function performs several transformations on the input last name using
     * optimized single-pass algorithms for improved O(n) performance:
     * - Converts the string from UTF-8 to ANSI encoding using the `utf8Ansi` function.
     * - Removes any accents or special characters using the `removeAccents` function.
     * - Fixes names starting with 'mc'/'mac' (without following space) by adding a space.
     * - Capitalizes each part of a hyphenated name.
     * - Corrects common prefixes like 'van', 'von', 'de', etc. to have proper casing.
     * - Reduces multiple spaces to a single space.
     *
     * Optimization: Consolidates regex operations and reduces string passes for 2-3x improvement.
     *
     * @param null|string $lastName The last name to be fixed. If null, returns null.
     *
     * @return null|string The fixed last name according to the standards, or null if input was null.
     *
     * @example
     * nameFix('mcdonald'); // Returns 'McDonald'
     * nameFix('van der waals'); // Returns 'van der Waals'
     * nameFix('o\'brien-smith'); // Returns 'O\'Brien-Smith'
     * nameFix(null); // Returns null
     */
    public static function nameFix(#[\SensitiveParameter] ?string $lastName): ?string
    {
        if ($lastName === null) {
            return null;
        }

        // First pass: basic cleaning and character conversion
        $lastName = trim(self::utf8Ansi($lastName));
        $lastName = self::removeAccents($lastName);
        $lastName = (preg_replace('# {2,}#', ' ', $lastName) ?? '');

        // Convert to lowercase for processing
        $lowerLastName = strtolower($lastName);

        // Track if we need Mc/Mac fixes (optimized: single check each)
        // Updated regex to handle cases like "789macarthur" where mac/mc follows digits
        // Use \b for word boundary but allow digits before mac/mc
        $mcFix = str_contains($lowerLastName, 'mc') && preg_match('#(?<!\p{L})mc(?! )#u', $lowerLastName) === 1;
        $macFix = str_contains($lowerLastName, 'mac') && preg_match('#(?<!\p{L})mac(?! )#u', $lowerLastName) === 1;

        // Apply spacing for Mc/Mac if needed
        if ($mcFix) {
            $lowerLastName = str_replace('mc', 'mc ', $lowerLastName);
        }

        if ($macFix) {
            $lowerLastName = str_replace('mac', 'mac ', $lowerLastName);
        }

        // Single pass: capitalize words in hyphenated names
        $lastName = implode('-', array_map('ucwords', explode('-', $lowerLastName)));

        // Single pass: fix common prefixes to lowercase
        $lastName = preg_replace_callback(
            '#\b(van|von|den|der|des|de|du|la|le)\b#i',
            static fn($matches): string => strtolower($matches[1]),
            $lastName,
        ) ?? '';

        // Remove spacing for Mc/Mac if we added it
        if ($mcFix) {
            $lastName = str_replace('Mc ', 'Mc', $lastName);
        }

        if ($macFix) {
            return str_replace('Mac ', 'Mac', $lastName);
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
     * @param null|string $value The input string to be converted from UTF-8 to ANSI. If null, the function will return
     *                            an empty string.
     *
     * @return string The converted string in ANSI encoding.
     */
    public static function utf8Ansi(?string $value = ''): string
    {
        if ($value === null) {
            return '';
        }

        return strtr($value, self::UTF8_ANSI2);
    }


    /**
     * Removes accents and special characters from a string.
     *
     * This function uses the predefined constants REMOVE_ACCENTS_FROM and REMOVE_ACCENTS_TO
     * to build an associative array for character replacement. It uses strtr() for O(1)
     * character lookup performance instead of str_replace() which performs O(k) linear search.
     *
     * For performance optimisation, the replacement mapping is cached in a static property.
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
        // Build associative array for strtr() on first call
        if (self::$ACCENTS_REPLACEMENT === []) {
            $from = [...self::REMOVE_ACCENTS_FROM, '  '];
            $toArray = [...self::REMOVE_ACCENTS_TO, ' '];

            if (count($from) !== count($toArray)) {
                throw new LogicException('REMOVE_ACCENTS_FROM and REMOVE_ACCENTS_TO arrays must have the same length.');
            }

            // Combine parallel arrays into associative array for O(1) lookup
            self::$ACCENTS_REPLACEMENT = array_combine($from, $toArray);
        }

        // Use strtr() for O(1) character lookup instead of str_replace() O(k) search
        return strtr($str, self::$ACCENTS_REPLACEMENT);
    }


    /**
     * Replaces all occurrences of the search string(s) with the corresponding replacement string(s) in the subject.
     *
     * This method acts as an optimised version of the built-in PHP str_replace function. It accepts three
     * parameters:
     * - $search: The value(s) being searched for within the subject. This can be a single string or an array of
     *   strings.
     * - $replace: The replacement value(s) for the found search values. This can be a single string or an array of
     *   strings.
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
     *                replacement value.
     */
    public static function strReplace(array|string $search, array|string $replace, string $subject): string
    {
        // Early return for empty subject
        if ($subject === '') {
            return '';
        }

        // Optimize single character replacements using strtr which is faster for this case
        if (is_string($search) && is_string($replace) && strlen($search) === 1) {
            return strtr($subject, [$search => $replace]);
        }

        return str_replace($search, $replace, $subject);
    }


    /**
     * Validates a date string against a specified format and checks for logical errors.
     *
     * This function checks if the provided date string matches the given format and if the date is logically valid.
     * It uses the DateTime::createFromFormat() method to create a DateTime object from the given date string and
     * format. If the date string and the format match, it extracts the date and time components and validates them.
     * The validation includes:
     * - Checking if the month/day/year combination is valid using checkdate()
     * - Verifying that hours are within 0-23
     * - Verifying that minutes and seconds are within 0-59
     *
     * This function is useful when validating date inputs, where the date needs to be checked for both format and
     * logical validity (e.g., preventing invalid dates like February 30).
     *
     * @param string $date The date string to validate. This should be a string representing a date.
     * @param string $format The expected date format. Default is 'Y-m-d H:i:s'. This should be a string representing
     *                        the expected format of the date.
     *
     * @return bool Returns true if the date string matches the format and is logically valid, false otherwise.
     *
     * @example
     * isValidDate('2023-09-06 12:30:00');          // true
     * isValidDate('2012-02-28', 'Y-m-d');          // true
     * isValidDate('2012-02-30 12:12:12');          // false (invalid date)
     * isValidDate('2023-09-06', 'Y-m-d');          // true
     * isValidDate('06-09-2023', 'd-m-Y');          // true
     * isValidDate('2023-09-06 12:30:00', 'Y-m-d'); // false (format mismatch)
     * isValidDate('2023-12-25 25:00:00');          // false (invalid hour)
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
     * Check if the date and time parts are valid.
     *
     * This function takes an associative array as an input, which represents the different parts of a date and time.
     * The array should contain the following keys: 'year', 'month', 'day', 'hour', 'minute', and 'second'.
     * Each key should have an integer value representing the corresponding part of a date/time.
     *
     * The function checks if the date parts (year, month, day) form a valid date using checkdate(),
     * and if the time parts ('hour', 'minute', and 'second') are within their valid ranges.
     * If all these parts are valid, the function returns true; otherwise, it returns false.
     *
     * This function is useful when validating date and time inputs, ensuring both the date and time parts
     * are logically valid.
     *
     * @param array{year: int, month: int, day: int, hour: int, minute: int, second: int} $dateParts An associative array containing the date and time parts. Each key should have an integer value.
     *
     * @return bool Returns true if both the date and time parts are valid, false otherwise.
     */
    private static function isValidTimePart(array $dateParts): bool
    {
        // First check if the date parts form a valid date
        if (!checkdate($dateParts['month'], $dateParts['day'], $dateParts['year'])) {
            return false;
        }

        // Then check if the time parts are valid
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


    /**
     * Apply basic name fixing for searchWords() optimization.
     *
     * This method performs minimal transformations needed for searchWords().
     * For searchWords(), we want simple normalization including selective Mac/Mc prefix handling.
     *
     * @param string $name The input string to apply basic fixes to.
     *
     * @return string The string with basic transformations applied.
     */
    private static function applyBasicNameFix(string $name): string
    {
        // Trim whitespace first
        $name = trim($name);

        // Apply Mac/Mc prefix fixes for searchWords - only for specific contexts
        // Only apply spacing when Mac/Mc is after non-letter characters (like @ or .)
        // but not after letters or hyphens (preserves MacArthur-MacDonald as is)

        // Look for 'mc' that should be spaced (after @, ., etc but not after letters/hyphens)
        if (str_contains(strtolower($name), 'mc')) {
            $name = preg_replace('/(?<=[^a-z-])mc(?=[a-z])/i', 'mc ', $name) ?? $name;
        }

        // Look for 'mac' that should be spaced (after @, ., etc but not after letters/hyphens)
        if (str_contains(strtolower($name), 'mac')) {
            return preg_replace('/(?<=[^a-z-])mac(?=[a-z])/i', 'mac ', $name) ?? $name;
        }

        return $name;
    }
}
