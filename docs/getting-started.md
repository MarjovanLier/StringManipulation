---
layout: default
title: Getting Started
nav_order: 2
---

# Getting Started
{: .no_toc }

Learn how to install and start using the StringManipulation library in your PHP projects.
{: .fs-6 .fw-300 }

## Table of contents
{: .no_toc .text-delta }

1. TOC
{:toc}

---

## Installation

Install the package via Composer:

```bash
composer require marjovanlier/stringmanipulation
```

### Requirements

- **PHP 8.4+** with strict typing
- **mbstring extension** for multi-byte string operations
- **intl extension** for internationalisation support

---

## Basic Usage

All methods are static and accessible through the `StringManipulation` class:

```php
<?php

declare(strict_types=1);

use MarjovanLier\StringManipulation\StringManipulation;

// Remove accents from text
$normalised = StringManipulation::removeAccents('Cafe Munchen');
echo $normalised; // Output: Cafe Munchen

// Optimise for database search
$searchable = StringManipulation::searchWords('Hello_World');
echo $searchable; // Output: hello world

// Standardise names
$name = StringManipulation::nameFix('mcdonald');
echo $name; // Output: McDonald

// Validate dates
$isValid = StringManipulation::isValidDate('2023-12-25', 'Y-m-d');
echo $isValid ? 'Valid' : 'Invalid'; // Output: Valid

// Convert UTF-8 to ANSI
$ansi = StringManipulation::utf8Ansi('Uber');
echo $ansi; // Output: Uber
```

---

## Common Use Cases

### Search Optimisation

When storing searchable text in a database, normalise it first:

```php
$userInput = "Cafe Creme Brulee - Munchen";
$searchColumn = StringManipulation::searchWords($userInput);
// Result: 'cafe creme brulee munchen'

// Store $searchColumn in your database for efficient searching
```

### Name Formatting

Standardise user-submitted names:

```php
$lastName = StringManipulation::nameFix('van der waals');
// Result: 'van der Waals'

$scottishName = StringManipulation::nameFix('macdonald');
// Result: 'MacDonald'

$irishName = StringManipulation::nameFix("o'brien");
// Result: "O'Brien"
```

### Date Validation

Validate user input before database storage:

```php
$date = $_POST['birth_date'] ?? '';

if (StringManipulation::isValidDate($date, 'Y-m-d')) {
    // Safe to store in database
} else {
    // Show validation error
}

// Also catches logically invalid dates
StringManipulation::isValidDate('2023-02-30', 'Y-m-d'); // false - February doesn't have 30 days
StringManipulation::isValidDate('2023-13-01', 'Y-m-d'); // false - no 13th month
```

### Legacy System Integration

Convert UTF-8 text for systems that only support ANSI:

```php
$modernText = "Uber Technologies - Munchen Office";
$legacyCompatible = StringManipulation::utf8Ansi($modernText);
// Result: 'Uber Technologies - Munchen Office'
```

---

## Method Chaining

Combine methods for complex transformations:

```php
$input = 'Cafe Munchen';

// Chain for comprehensive normalisation
$result = StringManipulation::searchWords(
    StringManipulation::removeAccents($input)
);
// Result: 'cafe munchen'
```

---

## Error Handling

The library handles null values gracefully:

```php
// Null-safe methods return null when given null
$result = StringManipulation::searchWords(null);
// Result: null

$result = StringManipulation::nameFix(null);
// Result: null

// utf8Ansi returns empty string for null
$result = StringManipulation::utf8Ansi(null);
// Result: ''
```

---

## Next Steps

- Explore the [API Reference]({{ site.baseurl }}/api-reference/) for detailed method documentation
- Check the [Performance]({{ site.baseurl }}/performance/) page for benchmarks and optimisation details
- View [Examples]({{ site.baseurl }}/examples/) for more use cases
