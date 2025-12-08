---
layout: default
title: Performance
nav_order: 4
---

# Performance
{: .no_toc }

Benchmarks and optimisation details for the StringManipulation library.
{: .fs-6 .fw-300 }

## Table of contents
{: .no_toc .text-delta }

1. TOC
{:toc}

---

## Overview

The StringManipulation library has undergone extensive performance tuning, resulting in **2-5x speed improvements** through O(n) optimisation algorithms. All core methods are designed with predictable, linear performance scaling.

---

## Benchmarks

| Method            | Operations/Second | Complexity | Optimisation                    |
|:------------------|:------------------|:-----------|:--------------------------------|
| `removeAccents()` | **~450,000**      | O(n)       | Hash table lookups with strtr() |
| `searchWords()`   | **~195,000**      | O(n)       | Single-pass combined mapping    |
| `nameFix()`       | **~130,000**      | O(n)       | Consolidated regex operations   |

*Benchmarks measured in Docker with PHP 8.3. Actual performance varies based on hardware, string length, and character complexity.*

---

## Optimisation Techniques

### Hash Table Lookups

The `removeAccents()` method uses PHP's `strtr()` function with a pre-built character mapping array. This provides O(1) lookup time for each character, resulting in overall O(n) complexity.

```php
// Internal implementation concept
private static ?array $accentsReplacement = null;

public static function removeAccents(string $str): string
{
    // Lazy initialisation - build mapping once
    if (self::$accentsReplacement === null) {
        self::$accentsReplacement = array_combine(
            self::REMOVE_ACCENTS_FROM,
            self::REMOVE_ACCENTS_TO
        );
    }

    // O(n) string traversal with O(1) lookups
    return strtr($str, self::$accentsReplacement);
}
```

### Single-Pass Transformations

The `searchWords()` method performs all transformations in a single pass through the string, avoiding multiple iterations:

1. Character mapping and accent removal
2. Case conversion
3. Space normalisation

This reduces memory allocations and cache misses compared to chaining multiple operations.

### Static Caching

Character mapping tables are stored as static properties and initialised lazily. Subsequent calls reuse the cached data:

```php
// First call: builds and caches mapping
$result1 = StringManipulation::removeAccents('Cafe');

// Subsequent calls: uses cached mapping
$result2 = StringManipulation::removeAccents('Munchen');
```

### Consolidated Regex Operations

The `nameFix()` method combines multiple regex operations where possible, reducing the number of pattern compilations and string scans.

---

## Complexity Analysis

### O(n) Guarantee

All core methods maintain O(n) complexity regardless of input characteristics:

| Input Size | `removeAccents()` | `searchWords()` | `nameFix()` |
|:-----------|:------------------|:----------------|:------------|
| 10 chars | 10 ops | 10 ops | 10 ops |
| 100 chars | 100 ops | 100 ops | 100 ops |
| 1,000 chars | 1,000 ops | 1,000 ops | 1,000 ops |
| 10,000 chars | 10,000 ops | 10,000 ops | 10,000 ops |

### No Hidden Costs

Unlike some string libraries, there are no hidden O(n) or O(n log n) operations:

- No internal sorting
- No repeated string scans
- No recursive operations
- No dynamic regex compilation per call

---

## Memory Efficiency

### Minimal Allocations

The library minimises string allocations in critical paths:

- Pre-allocated mapping tables
- In-place character replacement where possible
- No intermediate string copies for simple operations

### Static Memory Footprint

Memory usage is predictable and doesn't grow with usage:

| Component | Memory | Notes |
|:----------|:-------|:------|
| Accent mapping | ~10KB | Loaded once, shared across calls |
| Unicode mapping | ~5KB | Loaded once, shared across calls |
| Method overhead | Minimal | No per-call allocations |

---

## Running Benchmarks

The library includes a comprehensive benchmark suite:

```bash
# Run all benchmarks
docker-compose run --rm tests ./vendor/bin/pest tests/Benchmark/

# Run specific benchmark
docker-compose run --rm tests ./vendor/bin/pest tests/Benchmark/RemoveAccentsBenchmark.php
```

### Available Benchmarks

- `ComprehensiveBenchmark.php` - Full library comparison
- `SearchWordsBenchmark.php` - searchWords() performance
- `NameFixBenchmark.php` - nameFix() performance
- `RemoveAccentsBenchmark.php` - removeAccents() performance
- `RemoveAccentsComplexityBenchmark.php` - Complexity verification

---

## Performance Tips

### Batch Processing

For large datasets, process in batches to maintain memory efficiency:

```php
function processLargeDataset(iterable $records): Generator
{
    foreach ($records as $record) {
        yield [
            'name' => StringManipulation::nameFix($record['name']),
            'search' => StringManipulation::searchWords($record['name']),
        ];
    }
}

// Memory-efficient processing
foreach (processLargeDataset($database->cursor()) as $processed) {
    // Handle each record
}
```

### Avoid Redundant Operations

If you need both name fixing and search words, use `searchWords()` which includes name fixing:

```php
// Less efficient - two passes
$fixed = StringManipulation::nameFix($name);
$search = StringManipulation::searchWords($name);

// More efficient - searchWords includes name fixing
$search = StringManipulation::searchWords($name);
```

### Pre-warm Cache for Critical Paths

If first-call latency matters, pre-warm the caches during application bootstrap:

```php
// In bootstrap.php or service provider
StringManipulation::removeAccents('warmup');
StringManipulation::searchWords('warmup');
```

---

## Comparison with Alternatives

The library outperforms common alternatives:

| Library/Approach | removeAccents equivalent | Notes |
|:-----------------|:-------------------------|:------|
| StringManipulation | ~450,000 ops/sec | Optimised strtr() |
| Manual preg_replace | ~150,000 ops/sec | Multiple regex passes |
| iconv transliteration | ~200,000 ops/sec | System-dependent |
| Multiple str_replace | ~100,000 ops/sec | Linear per pattern |

*Approximate comparisons. Actual results depend on input and environment.*
