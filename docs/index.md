---
layout: default
title: Home
nav_order: 1
description: "High-performance PHP 8.3+ string manipulation library featuring O(n) algorithms with up to 5x speed improvements"
permalink: /
---

# StringManipulation
{: .fs-9 }

High-performance PHP 8.3+ string manipulation library featuring O(n) algorithms with up to 5x speed improvements.
{: .fs-6 .fw-300 }

[Get Started]({{ site.baseurl }}/getting-started){: .btn .btn-primary .fs-5 .mb-4 .mb-md-0 .mr-2 }
[View on GitHub](https://github.com/MarjovanLier/StringManipulation){: .btn .fs-5 .mb-4 .mb-md-0 }

---

## Overview

The **StringManipulation** library is a toolkit designed for complex and efficient string handling in PHP 8.3+. Following a recent suite of O(n) optimisations, the library delivers **2-5x faster performance**, making it one of the most powerful and reliable solutions for developers who require speed and precision.

### Key Features

- **Unicode/UTF-8 Support** - Built from the ground up to handle international characters with optimised mappings
- **Data Normalisation** - Efficiently strip accents and diacritics for text comparison and searching
- **Search Optimisation** - Transform strings for database queries with intelligent character handling
- **Name Standardisation** - Capitalise names correctly with proper prefix handling (Mc, Mac, van, de, etc.)
- **Date Validation** - Comprehensive validation with format conformance and logical consistency checks
- **Encoding Conversion** - Convert UTF-8 to ANSI for legacy system compatibility

### Performance at a Glance

| Method | Performance | Optimisation |
|:-------|:------------|:-------------|
| `removeAccents()` | **~450,000 ops/sec** | Hash table lookups with strtr() |
| `searchWords()` | **~195,000 ops/sec** | Single-pass combined mapping |
| `nameFix()` | **~130,000 ops/sec** | Consolidated regex operations |

---

## Quick Start

### Installation

```bash
composer require marjovanlier/stringmanipulation
```

### Basic Usage

```php
use MarjovanLier\StringManipulation\StringManipulation;

// Remove accents from text
$normalised = StringManipulation::removeAccents('Creme Brulee');
// Output: 'Creme Brulee'

// Optimise text for search
$searchable = StringManipulation::searchWords('John_Doe@Example.com');
// Output: 'john doe example com'

// Standardise names
$name = StringManipulation::nameFix('mcdonald');
// Output: 'McDonald'
```

---

## Why StringManipulation?

### O(n) Complexity

All core methods use O(n) algorithms, ensuring performance scales linearly with input size. No hidden quadratic or exponential costs.

### Static Caching

Character mapping tables are cached statically, eliminating redundant computations across multiple calls.

### Single-Pass Transformations

Methods like `searchWords()` perform all transformations in a single pass, minimising string allocations and memory pressure.

### Battle-Tested

- **166+ comprehensive tests** with 100% code coverage
- **88% Mutation Score** with Infection mutation testing
- **Triple static analysis** with PHPStan, Psalm, and Phan

---

## System Requirements

- PHP 8.3 or later (strict typing enabled)
- `mbstring` extension for multi-byte string operations
- `intl` extension for internationalisation and advanced Unicode support

---

## License

This project is licensed under the [MIT License](https://github.com/MarjovanLier/StringManipulation/blob/main/LICENSE).
