# String Manipulation Library for PHP

Welcome to the `StringManipulation` library, a robust and efficient PHP toolkit designed to enhance string handling in your PHP projects. With its user-friendly interface and performance-oriented design, this library is an essential addition for developers looking to perform complex string manipulations with ease.

[![Packagist Version](https://img.shields.io/packagist/v/marjovanlier/stringmanipulation)](https://packagist.org/packages/marjovanlier/stringmanipulation)
[![Packagist Downloads](https://img.shields.io/packagist/dt/marjovanlier/stringmanipulation)](https://packagist.org/packages/marjovanlier/stringmanipulation)
[![Packagist License](https://img.shields.io/packagist/l/marjovanlier/stringmanipulation)](https://choosealicense.com/licenses/mit/)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/marjovanlier/stringmanipulation)](https://packagist.org/packages/marjovanlier/stringmanipulation)
[![Latest Stable](https://poser.pugx.org/marjovanlier/stringmanipulation/v/stable)](https://packagist.org/packages/marjovanlier/stringmanipulation)
[![PHPStan Enabled](https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat)](https://phpstan.org/)
[![Phan Enabled](https://img.shields.io/badge/Phan-enabled-brightgreen.svg?style=flat)](https://github.com/phan/phan/)
[![Psalm Enabled](https://img.shields.io/badge/Psalm-enabled-brightgreen.svg?style=flat)](https://psalm.dev/)

## Features

- **Search Words**: Transform strings into a search-optimized format for database queries.
- **Name Fix**: Standardize last names to adhere to conventional naming formats.
- **UTF-8 to ANSI**: Convert UTF-8 encoded characters to their ANSI equivalents.
- **Remove Accents**: Strip accents and special characters from strings to normalize text.
- **Date Validation**: Ensure date strings conform to specified formats and check for logical consistency.
- **Time Part Validation**: Validate the time components within date strings for accuracy.

## Installation

Install the package via Composer with the following command:

```bash
composer require marjovanlier/stringmanipulation
```

## Usage

To use the `searchWords` function and format a string for database searching:

```php
use MarjovanLier\StringManipulation\StringManipulation;

$result = StringManipulation::searchWords('Hello_World');
echo $result; // Outputs: 'hello world'
```

## License

This library is licensed under the MIT License. For more information, please refer to the [License File](LICENSE).

## Detailed Examples

### Name Standardization

```php
use MarjovanLier\StringManipulation\StringManipulation;

$fixedName = StringManipulation::nameFix('mcdonald');
echo $fixedName; // Outputs: 'McDonald'
```

### UTF-8 to ANSI Conversion

```php
use MarjovanLier\StringManipulation\StringManipulation;

$ansiString = StringManipulation::utf8Ansi('Äpfel');
echo $ansiString; // Outputs: 'Äpfel' in ANSI format
```

## Testing

Run the tests with:

```bash
composer tests
```

## System Requirements

- PHP 8.2 or later.

## Support

For support, please open an issue on our [GitHub repository](https://github.com/MarjovanLier/StringManipulation/issues).

