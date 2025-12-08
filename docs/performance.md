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

The StringManipulation library has undergone extensive performance tuning, resulting in **2-5x speed improvements** through O(n) optimisation algorithms and pre-computed compile-time constants. All core methods are designed with predictable, linear performance scaling.

---

## Benchmarks

| Method            | Operations/Second | Complexity | Optimisation                        |
|:------------------|:------------------|:-----------|:------------------------------------|
| `removeAccents()` | **~750,000**      | O(n)       | Pre-computed constant with strtr()  |
| `searchWords()`   | **~400,000**      | O(n)       | Pre-computed constant with strtr()  |
| `nameFix()`       | **~180,000**      | O(n)       | Consolidated regex operations       |

*Benchmarks measured in Docker with PHP 8.3. Actual performance varies based on hardware, string length, and character complexity.*

---

## Optimisation Techniques

### Pre-Computed Constants

The `removeAccents()` and `searchWords()` methods use PHP's `strtr()` function with pre-computed compile-time constants. This eliminates runtime array construction overhead and provides O(1) lookup time for each character, resulting in overall O(n) complexity.

```php
// Pre-computed at compile time - no runtime overhead
private const array ACCENT_MAPPING = [
    'À' => 'A', 'Á' => 'A', 'Â' => 'A', // ... full mapping
    'à' => 'a', 'á' => 'a', 'â' => 'a', // ... preserves case
];

public static function removeAccents(string $str): string
{
    // Single strtr() call with O(1) lookups per character
    return strtr($str, self::ACCENT_MAPPING);
}
```

### Single-Pass Transformations

The `searchWords()` method performs all transformations in a single pass through the string, avoiding multiple iterations:

1. Character mapping and accent removal
2. Case conversion
3. Space normalisation

This reduces memory allocations and cache misses compared to chaining multiple operations.

### Compile-Time Constants

Character mapping tables are defined as typed constants (`private const array`), computed at compile time by PHP. This provides:

- **Zero first-call overhead**: No lazy initialisation required
- **Guaranteed consistency**: Constants cannot be modified at runtime
- **Optimal memory usage**: PHP optimises constant storage

```php
// Every call uses the same pre-computed constant
$result1 = StringManipulation::removeAccents('Cafe');   // Fast
$result2 = StringManipulation::removeAccents('Munchen'); // Equally fast
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

### No Warm-up Required

Unlike libraries that use lazy initialisation, StringManipulation uses compile-time constants. There is no first-call penalty, so no warm-up is needed:

```php
// First call is just as fast as subsequent calls
$result = StringManipulation::removeAccents($userInput);
```

---

## Comparison with Alternatives

The library outperforms common alternatives:

| Library/Approach | removeAccents equivalent | Notes |
|:-----------------|:-------------------------|:------|
| StringManipulation | ~750,000 ops/sec | Pre-computed constant with strtr() |
| Manual preg_replace | ~150,000 ops/sec | Multiple regex passes |
| iconv transliteration | ~200,000 ops/sec | System-dependent |
| Multiple str_replace | ~100,000 ops/sec | Linear per pattern |

*Approximate comparisons. Actual results depend on input and environment.*
