# String Manipulation Library for PHP

## Table of Contents

- [Introduction](#introduction)
- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
- [Advanced Usage](#advanced-usage)
- [Testing](#testing)
- [System Requirements](#system-requirements)

## Introduction

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
[![Qodana](https://github.com/MarjovanLier/StringManipulation/actions/workflows/qodana_code_quality.yml/badge.svg)](https://github.com/MarjovanLier/StringManipulation/actions/workflows/qodana_code_quality.yml)

## Features

- **Search Words**: Transform strings into a search-optimised format for database queries, removing unnecessary
  characters and optimising for search engine algorithms.
- **Name Fix**: Standardise last names by capitalising the first letter of each part of the name and handling prefixes
  correctly, ensuring consistency across your data.
- **UTF-8 to ANSI**: Convert UTF-8 encoded characters to their ANSI equivalents, facilitating compatibility with systems
  that do not support UTF-8.
- **Remove Accents**: Strip accents and special characters from strings to normalise text, making it easier to search
  and compare.
- **Date Validation**: Ensure date strings conform to specified formats and check for logical consistency, such as
  correct days in a month.

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

### Name Standardisation

- **Case Conversion**: Easily convert strings between upper case, lower case, and title case, allowing for flexible text
  formatting and presentation. For example, converting 'john doe' to 'John Doe' for proper name presentation.

```php
use MarjovanLier\StringManipulation\StringManipulation;

$fixedName = StringManipulation::nameFix('mcdonald');
echo $fixedName; // Outputs: 'McDonald'
```


### Search Words

This feature optimises strings for database queries by removing unnecessary characters and optimising for search engine
algorithms.

```php
use MarjovanLier\StringManipulation\StringManipulation;

$result = StringManipulation::searchWords('Hello_World');
echo $result; // Outputs: 'hello world'
```

### Name Fix

Standardise last names by capitalising the first letter of each part of the name and handling prefixes correctly.

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
echo $ansiString; // Outputs: 'Uber'
```

### Remove Accents

Strip accents and special characters from strings to normalise text, making it easier to search and compare.

```php
use MarjovanLier\StringManipulation\StringManipulation;

$normalisedString = StringManipulation::removeAccents('Crème Brûlée');
echo $normalisedString; // Outputs: 'Creme Brulee'
```

### Date Validation

Ensure date strings conform to specified formats and check for logical consistency, such as correct days in a month.

```php
use MarjovanLier\StringManipulation\StringManipulation;

$isValidDate = StringManipulation::isValidDate('2023-02-29', 'Y-m-d');
echo $isValidDate ? 'Valid' : 'Invalid'; // Outputs: 'Invalid'
```


## Advanced Usage

For more complex string manipulations, consider chaining functions to achieve unique transformations. For instance, you
could first normalise a string, apply a search optimisation, and finally standardise the casing for a comprehensive text
processing example.

```php
use MarjovanLier\StringManipulation\StringManipulation;

$originalString = 'Crème Brûlée';
$processedString = StringManipulation::nameFix(StringManipulation::utf8Ansi(StringManipulation::removeAccents($originalString)));
echo $processedString; // Outputs: 'Creme Brulee'
```

This approach allows for flexible and powerful string manipulations by combining the library's functions to suit your
specific needs.

## Contributing

We welcome contributions to the `StringManipulation` library! If you're interested in helping, please follow these
steps:

1. Fork the repository and create your feature branch.
2. Ensure your changes adhere to our coding standards and include tests if applicable.
3. Submit a pull request with a detailed description of your changes.

Thank you for your interest in improving our library!

## Testing

To ensure the reliability and functionality of your string manipulations, it's recommended to run the entire test suite
with the following command:

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

- PHP 8.3 or later.

## Support

For support, please open an issue on our [GitHub repository](https://github.com/MarjovanLier/StringManipulation/issues).

