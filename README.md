# String Manipulation Library for PHP

## Table of Contents

- [Introduction](#introduction)
- [Features](#features)
- [Performance Benchmarks](#performance-benchmarks)
- [Installation](#installation)
- [Usage](#usage)
- [Advanced Usage](#advanced-usage)
- [Testing & Quality Assurance](#testing--quality-assurance)
- [System Requirements](#system-requirements)
- [Contributing](#contributing)
- [Support](#support)

## Introduction

Welcome to the `StringManipulation` library, a high-performance PHP 8.3+ toolkit designed for complex and efficient
string handling. Following a recent suite of O(n) optimisations, the library is now **2-5x faster**, making it one of
the most powerful and reliable solutions for developers who require speed and precision in their PHP applications.

This library specialises in Unicode handling, data normalisation, encoding conversion, and validation with comprehensive
testing and quality assurance.

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

- **`removeAccents()`**: Efficiently strips accents and diacritics to normalise text. Powered by O(n) optimisations
  using hash table lookups, this high-performance feature makes text comparison and searching faster than ever (981,436+
  ops/sec).
- **`searchWords()`**: Transforms strings into a search-optimised format ideal for database queries. This
  high-performance function intelligently removes irrelevant characters and applies single-pass algorithms to improve
  search accuracy (387,231+ ops/sec).
- **`nameFix()`**: Standardises names by capitalising letters and correctly handling complex prefixes. Its
  performance-oriented design with consolidated regex operations ensures consistent data formatting at scale (246,197+
  ops/sec).
- **`utf8Ansi()`**: Convert UTF-8 encoded characters to their ANSI equivalents with comprehensive Unicode mappings,
  facilitating compatibility with legacy systems.
- **`isValidDate()`**: Comprehensive date validation utility that ensures date strings conform to specified formats and
  validates logical consistency.
- **Comprehensive Unicode/UTF-8 Support**: Built from the ground up to handle a wide range of international characters
  with optimised character mappings, ensuring your application is ready for a global audience.

## Performance Benchmarks

The library has undergone extensive performance tuning, resulting in **2-5x speed improvements** through O(n)
optimisation algorithms. Our benchmarks demonstrate the library's capability to handle high-volume data processing
efficiently:

| Method            | Performance          | Optimisation Technique          |
|-------------------|----------------------|---------------------------------|
| `removeAccents()` | **981,436+ ops/sec** | Hash table lookups with strtr() |
| `searchWords()`   | **387,231+ ops/sec** | Single-pass combined mapping    |
| `nameFix()`       | **246,197+ ops/sec** | Consolidated regex operations   |

*Benchmarks measured on standard development environments. Actual performance may vary based on hardware, string length,
and complexity.*

**Key Optimisation Features:**

- O(n) complexity algorithms for all core methods
- Static caching for character mapping tables
- Single-pass string transformations
- Minimal memory allocation in critical paths

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

## Testing & Quality Assurance

We are committed to delivering reliable, high-quality code. Our library is rigorously tested using a comprehensive suite
of tools to ensure stability and correctness.

### Docker-Based Testing (Recommended)

For a consistent and reliable testing environment, we recommend using Docker. Our Docker setup includes PHP 8.3 with all
required extensions:

```bash
# Run complete test suite
docker-compose run --rm test-all

# Run individual test suites
docker-compose run --rm test-phpunit      # PHPUnit tests
docker-compose run --rm test-phpstan      # Static analysis
docker-compose run --rm test-code-style   # Code style
docker-compose run --rm test-infection    # Mutation testing
```

### Local Testing

If you have a local PHP 8.3+ environment configured:

```bash
# Complete test suite
composer tests

# Individual tests
./vendor/bin/phpunit --filter testClassName
./vendor/bin/phpunit --filter '/::testMethodName$/'
```

### Our Quality Suite Includes:

- **PHPUnit**: 166 comprehensive tests with 100% code coverage ensuring functional correctness
- **Mutation Testing**: 88% Mutation Score Indicator (MSI) with Infection, guaranteeing our tests are robust and
  meaningful
- **Static Analysis**: Proactive bug detection using:
    - PHPStan (level max, strict rules)
    - Psalm (level 1, 99.95% type coverage)
    - Phan (clean analysis results)
    - PHPMD (mess detection)
- **Code Style**: Automated formatting with Laravel Pint (PSR compliance)
- **Performance Benchmarks**: Continuous performance monitoring with comprehensive benchmarking suite

## System Requirements

- **PHP 8.3 or later** (strict typing enabled)
- **`mbstring` extension** for multi-byte string operations
- **`intl` extension** for internationalisation and advanced Unicode support
- **Enabled `declare(strict_types=1);`** for robust type safety
- **Composer** for package management

## Support

For support, please open an issue on our [GitHub repository](https://github.com/MarjovanLier/StringManipulation/issues).
