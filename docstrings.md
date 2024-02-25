# StringManipulation Documentation

This file provides documentation for the functions in the `StringManipulation` class.

## Class: StringManipulation

### `searchWords(string $words): string`

Transforms a string into a format suitable for database searching.

This function performs several transformations on the input string to make it suitable for searching within a database. The transformations include:

- Applying the name fixing standards via the `nameFix` function.
- Converting the string to lowercase.
- Replacing various special characters with spaces.
- Replacing underscores with spaces.
- Removing accents from characters.
- Reducing multiple spaces to a single space.

For example, a string like "John_Doe@Example.com" will be transformed to "john doe example com".

Parameters:
- `$words` (string): The input string to be transformed for search.

Returns:
- The transformed string suitable for database search.

### `nameFix(string $lastName): string`

Fixes a given last name to conform to specific naming standards.

This function performs several transformations on the input last name:

- Converts the string from UTF-8 to ANSI encoding.
- Removes any accents or special characters.
- Reduces multiple spaces to a single space.
- Fixes names starting with 'mc' (without a following space) by adding a space after the prefix.
- Similarly, fixes names starting with 'mac' (without a following space).
- Capitalizes each part of a hyphenated name.
- Corrects common prefixes like 'van', 'von', 'de', etc. to have proper casing.

For example, a name like "mcdonald" will be transformed to "McDonald" and "van der waals" will be transformed to "van der Waals".

Parameters:
- `$lastName` (string): The last name to be fixed.

Returns:
- The fixed last name according to the standards.

### `utf8Ansi(string $valor = ''): string`

Converts UTF-8 encoded characters to their ANSI counterparts.

This function takes a string as an input, which should be encoded in UTF-8. It then converts each UTF-8 encoded character in the string to its corresponding ANSI encoded character.

Parameters:
- `$valor` (string): The input string to be converted from UTF-8 to ANSI.

Returns:
- The converted string in ANSI encoding.

### `removeAccents(string $str): string`

Removes accents and special characters from a string.

This function replaces accented characters and special characters with their non-accented equivalents or spaces. It normalizes the text, making it easier to search and compare.

Parameters:
- `$str` (string): The input string from which accents and special characters need to be removed.

Returns:
- The transformed string without accents and special characters.

### `strReplace(array|string $search, array|string $replace, string $subject): string`

Replace all occurrences of the search string(s) with the corresponding replacement string(s) in the subject.

This function is a stricter version of the built-in PHP `str_replace` function. It takes three parameters:
- `$search`: The value(s) being searched for in the subject.
- `$replace`: The replacement value(s) to replace found search values.
- `$subject`: The string being searched and replaced on.

The function returns a new string where all occurrences of each search value have been replaced by the corresponding replacement value.

Parameters:
- `$search` (array|string): The value(s) being searched for in the subject.
- `$replace` (array|string): The replacement value(s) to replace found search values.
- `$subject` (string): The string being searched and replaced on.

Returns:
- The string with replaced values.

### `isValidDate(string $date, string $format = 'Y-m-d H:i:s'): bool`

Validates a date string against a specified format and checks for logical errors.

This function checks if the provided date string matches the given format and if the date is logically valid. It uses the `DateTime::createFromFormat()` method to create a DateTime object from the given date string and format. If the date string and the format match, it reformats the date into 'Y-m-d' format and extracts the day, month, and year. It then checks if these constitute a valid date using `checkdate()`. If they do, it returns true; otherwise, it returns false.

Parameters:
- `$date` (string): The date string to validate.
- `$format` (string): The expected date format. Default is 'Y-m-d H:i:s'.

Returns:
- True if the date string matches the format and is logically valid, false otherwise.

### `trim(string $string, string $characters = " \t\n\r\0\x0B"): string`

Trims characters from the beginning and end of a string.

This function is a stricter version of the built-in PHP `trim` function. It trims the specified characters from the beginning and end of the input string. If no characters are specified, it trims whitespace characters by default.

Parameters:
- `$string` (string): The input string from which characters will be trimmed.
- `$characters` (string): Optional. The characters to be trimmed from the string. Defaults to " \t\n\r\0\x0B".

Returns:
- The trimmed string.

### `isValidTime(array $dateParts): bool`

Check if the time part of a date is valid.

This function takes an associative array as an input, which represents the different parts of a time. The array should contain the following keys: 'year', 'month', 'day', 'hour', 'minute', and 'second'. Each key should have an integer value representing the corresponding part of a time.

The function checks if the 'hour', 'minute', and 'second' parts of the time are within their valid ranges. If all these parts are within their valid ranges, the function returns true; otherwise, it returns false.

Parameters:
- `$dateParts` (array): An associative array containing the date and time parts.

Returns:
- True if the time part is valid, false otherwise.

### `isValidHour(int $hour): bool`

Check if the given hour is valid.

This function takes an integer as an input, which represents the hour part of a time. It checks if the given hour is within the valid range for hours (0-23). If the hour is within this range, the function returns true; otherwise, it returns false.

Parameters:
- `$hour` (int): The hour to be validated.

Returns:
- True if the hour is valid, false otherwise.

### `isValidMinute(int $minute): bool`

Check if the given minute is valid.

This function takes an integer as an input, which represents the minutes part of a time. It checks if the given minute is within the valid range for minutes (0-59). If the minute is within this range, the function returns true; otherwise, it returns false.

Parameters:
- `$minute` (int): The minute to be validated.

Returns:
- True if the minute is valid, false otherwise.

### `isValidSecond(int $second): bool`

Check if the given second is valid.

This function takes an integer as an input, which represents the seconds part of a time. It checks if the given second is within the valid range for seconds (0-59). If the second is within this range, the function returns true; otherwise, it returns false.

Parameters:
- `$second` (int): The second to be validated.

Returns:
- True if the second is valid, false otherwise.
