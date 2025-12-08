---
layout: default
title: removeAccents()
parent: API Reference
nav_order: 1
---

# removeAccents()
{: .no_toc }

Strips accents and special characters from strings to normalise text.
{: .fs-6 .fw-300 }

## Table of contents
{: .no_toc .text-delta }

1. TOC
{:toc}

---

## Signature

```php
public static function removeAccents(string $str): string
```

## Parameters

| Parameter | Type     | Description                                     |
|:----------|:---------|:------------------------------------------------|
| `$str`    | `string` | The input string containing accented characters |

## Returns

`string` - The input string with all accents and diacritics removed.

---

## Description

The `removeAccents()` method efficiently strips accents and diacritical marks from strings, making text easier to search, compare, and index. This is essential for:

- **Search functionality** - Match "cafe" when users search for "cafe"
- **URL slugs** - Generate clean URLs from titles
- **Data normalisation** - Standardise text for comparison

### Performance

- **~450,000 operations per second**
- Uses hash table lookups with `strtr()` for O(1) character replacement
- Static caching of character mapping tables

### Character Coverage

Supports 266+ accented and special characters including:
- Latin characters (a, e, i, o, u with various diacritics)
- Extended Latin (Ae, Oe, ss, etc.)
- Greek characters
- Currency symbols
- Special typographic characters

---

## Examples

### Basic Usage

```php
use MarjovanLier\StringManipulation\StringManipulation;

$result = StringManipulation::removeAccents('Creme Brulee');
echo $result; // Output: Creme Brulee
```

### International Text

```php
// French
StringManipulation::removeAccents('francais');
// Output: francais

// German
StringManipulation::removeAccents('Munchen Ubung');
// Output: Munchen Ubung

// Spanish
StringManipulation::removeAccents('Espanol manana');
// Output: Espanol manana

// Portuguese
StringManipulation::removeAccents('Sao Pauloacao');
// Output: Sao Paulo acao
```

### Special Characters

```php
// Ligatures
StringManipulation::removeAccents('AEsop OEuvre');
// Output: AEsop OEuvre

// Nordic characters
StringManipulation::removeAccents('Malmo Oslo');
// Output: Malmo Oslo

// Eastern European
StringManipulation::removeAccents('Praha Lodz');
// Output: Praha Lodz
```

---

## Use Cases

### Search Index Generation

```php
function generateSearchIndex(string $text): string
{
    return strtolower(
        StringManipulation::removeAccents($text)
    );
}

$title = "Cafe Creme - Munchen";
$searchIndex = generateSearchIndex($title);
// Result: cafe creme - munchen
```

### URL Slug Creation

```php
function createSlug(string $title): string
{
    $normalised = StringManipulation::removeAccents($title);
    $lowercase = strtolower($normalised);
    $slug = preg_replace('/[^a-z0-9]+/', '-', $lowercase);
    return trim($slug, '-');
}

$slug = createSlug('Cafe Creme Brulee');
// Result: cafe-creme-brulee
```

---

## Related Methods

- [`searchWords()`]({{ site.baseurl }}/api-reference/search-words/) - Combines accent removal with search optimisation
- [`utf8Ansi()`]({{ site.baseurl }}/api-reference/utf8-ansi/) - Converts UTF-8 to ANSI encoding
