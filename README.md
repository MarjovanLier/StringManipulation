# String Manipulation Library for PHP

Welcome to the `StringManipulation` library, a robust and efficient PHP toolkit designed to enhance string handling in
your PHP projects. With its user-friendly interface and performance-oriented design, this library is an essential
addition for developers looking to perform complex string manipulations with ease.

[![Packagist Version](https://img.shields.io/packagist/v/marjovanlier/stringmanipulation)](https://packagist.org/packages/marjovanlier/stringmanipulation)
[![Packagist Downloads](https://img.shields.io/packagist/dt/marjovanlier/stringmanipulation)](https://packagist.org/packages/marjovanlier/stringmanipulation)
[![Packagist License](https://img.shields.io/packagist/l/marjovanlier/stringmanipulation)](https://choosealicense.com/licenses/mit/)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/marjovanlier/stringmanipulation)](https://packagist.org/packages/marjovanlier/stringmanipulation)
[![Latest Stable](https://poser.pugx.org/marjovanlier/stringmanipulation/v/stable)](https://packagist.org/packages/marjovanlier/stringmanipulation)
[![PHPStan Enabled](https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat)](https://phpstan.org/)
[![Phan Enabled](https://img.shields.io/badge/Phan-enabled-brightgreen.svg?style=flat)](https://github.com/phan/phan/)
[![Psalm Enabled](https://img.shields.io/badge/Psalm-enabled-brightgreen.svg?style=flat)](https://psalm.dev/)
[![codecov](https://codecov.io/github/MarjovanLier/StringManipulation/graph/badge.svg?token=lBTpWlSq37)](https://codecov.io/github/MarjovanLier/StringManipulation)

## Features

- **Search Words**: Transform strings into a search-optimized format for database queries, removing unnecessary
  characters and optimizing for search engine algorithms.
- **Name Fix**: Standardize last names by capitalizing the first letter of each part of the name and handling prefixes
  correctly, ensuring consistency across your data.
- **UTF-8 to ANSI**: Convert UTF-8 encoded characters to their ANSI equivalents, facilitating compatibility with systems
  that do not support UTF-8.
- **Remove Accents**: Strip accents and special characters from strings to normalize text, making it easier to search
  and compare.
- **Date Validation**: Ensure date strings conform to specified formats and check for logical consistency, such as
  correct days in a month.
- **Time Part Validation**: Validate the time components within date strings for accuracy, ensuring that hours, minutes,
  and seconds are within valid ranges.

## Installation

Install the package via Composer with the following command:

```bash
composer require marjovanlier/stringmanipulation
```

## Usage

For more detailed examples of each feature, please refer to the corresponding sections below.

```php
use MarjovanLier\StringManipulation\StringManipulation;

$result = StringManipulation::searchWords('Hello_World');
echo $result; // Outputs: 'hello world'
```

## License

This library is licensed under the MIT License. For more information, please refer to the [License File](LICENSE).

## Detailed Examples

### Name Standardization

- **Case Conversion**: Easily convert strings between upper case, lower case, and title case, allowing for flexible text
  formatting and presentation.

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

### Search Words

This feature optimizes strings for database queries by removing unnecessary characters and optimizing for search engine
algorithms.

```php
use MarjovanLier\StringManipulation\StringManipulation;

$result = StringManipulation::searchWords('Hello_World');
echo $result; // Outputs: 'hello world'
```

### Name Fix

Standardize last names by capitalizing the first letter of each part of the name and handling prefixes correctly.

```php
use MarjovanLier\StringManipulation\StringManipulation;

$fixedName = StringManipulation::nameFix('de souza');
echo $fixedName; // Outputs: 'De Souza'
```

### UTF-8 to ANSI Conversion

Convert UTF-8 encoded characters to their ANSI equivalents, facilitating compatibility with systems that do not support
UTF-8.

```php
use MarjovanLier\StringManipulation\StringManipulation;

$ansiString = StringManipulation::utf8Ansi('Über');
echo $ansiString; // Outputs: 'Uber' in ANSI format
```

### Remove Accents

Strip accents and special characters from strings to normalize text, making it easier to search and compare.

```php
use MarjovanLier\StringManipulation\StringManipulation;

$normalizedString = StringManipulation::removeAccents('Crème Brûlée');
echo $normalizedString; // Outputs: 'Creme Brulee'
```

### Date Validation

Ensure date strings conform to specified formats and check for logical consistency, such as correct days in a month.

```php
use MarjovanLier\StringManipulation\StringManipulation;

$isValidDate = StringManipulation::validateDate('2023-02-29');
echo $isValidDate ? 'Valid' : 'Invalid'; // Outputs: 'Invalid'
```

### Time Part Validation

Validate the time components within date strings for accuracy, ensuring that hours, minutes, and seconds are within
valid ranges.

```php
use MarjovanLier\StringManipulation\StringManipulation;

$isValidTime = StringManipulation::validateTime('25:61:00');
echo $isValidTime ? 'Valid' : 'Invalid'; // Outputs: 'Invalid'
```

## Advanced Usage

For more complex string manipulations, you can chain multiple functions together. Here's an example that demonstrates
how to remove accents from a string, convert it to ANSI format, and then capitalize each word for name standardization.

```php
use MarjovanLier\StringManipulation\StringManipulation;

$originalString = 'Crème Brûlée';
$processedString = StringManipulation::nameFix(StringManipulation::utf8Ansi(StringManipulation::removeAccents($originalString)));
echo $processedString; // Outputs: 'Creme Brulee'
```

This approach allows for flexible and powerful string manipulations by combining the library's functions to suit your
specific needs.

## Testing

To run the entire test suite, use the following command:

```bash
./vendor/bin/phpunit
```

To run specific tests or test suites, you can use PHPUnit flags to filter tests. For example, to run tests in a specific
file:

```bash
./vendor/bin/phpunit --filter testFileName
```

And to run tests matching a specific name pattern:

```bash
./vendor/bin/phpunit --filter '/::testNamePattern$/'
```

## System Requirements

- PHP 8.2 or later.

## Support

For support, please open an issue on our [GitHub repository](https://github.com/MarjovanLier/StringManipulation/issues).

