---
layout: default
title: API Reference
nav_order: 3
has_children: true
---

# API Reference
{: .no_toc }

Complete documentation for all public methods in the StringManipulation library.
{: .fs-6 .fw-300 }

---

## Overview

The `StringManipulation` class provides static methods for high-performance string operations. All methods are designed with O(n) complexity for predictable, scalable performance.

```php
use MarjovanLier\StringManipulation\StringManipulation;
```

---

## Methods at a Glance

| Method                                                                | Description                                  | Performance      |
|:----------------------------------------------------------------------|:---------------------------------------------|:-----------------|
| [`removeAccents()`]({{ site.baseurl }}/api-reference/remove-accents/) | Strip accents and diacritics from text       | ~450,000 ops/sec |
| [`searchWords()`]({{ site.baseurl }}/api-reference/search-words/)     | Transform text for search optimisation       | ~195,000 ops/sec |
| [`nameFix()`]({{ site.baseurl }}/api-reference/name-fix/)             | Standardise names with proper capitalisation | ~130,000 ops/sec |
| [`utf8Ansi()`]({{ site.baseurl }}/api-reference/utf8-ansi/)           | Convert UTF-8 to ANSI encoding               | -                |
| [`isValidDate()`]({{ site.baseurl }}/api-reference/is-valid-date/)    | Validate date strings with format checking   | -                |
| [`strReplace()`]({{ site.baseurl }}/api-reference/str-replace/)       | Optimised string replacement                 | -                |
| [`trim()`]({{ site.baseurl }}/api-reference/trim/)                    | Remove characters from string ends           | -                |

---

## Null Handling

Methods handle null input consistently:

| Method | Null Input | Returns |
|:-------|:-----------|:--------|
| `searchWords()` | `null` | `null` |
| `nameFix()` | `null` | `null` |
| `utf8Ansi()` | `null` | `''` (empty string) |
| `removeAccents()` | N/A | Requires non-null string |
| `isValidDate()` | N/A | Requires non-null string |

---

## Type Signatures

All methods use strict typing (`declare(strict_types=1)`):

```php
public static function removeAccents(string $str): string;
public static function searchWords(?string $words): ?string;
public static function nameFix(#[\SensitiveParameter] ?string $lastName): ?string;
public static function utf8Ansi(?string $value = ''): string;
public static function isValidDate(string $date, string $format = 'Y-m-d H:i:s'): bool;
public static function strReplace(array|string $search, array|string $replace, string $subject): string;
public static function trim(string $string, string $characters = " \t\n\r\0\x0B"): string;
```
