---
layout: default
title: utf8Ansi()
parent: API Reference
nav_order: 4
---

# utf8Ansi()
{: .no_toc }

Converts UTF-8 encoded characters to their ANSI equivalents.
{: .fs-6 .fw-300 }

## Table of contents
{: .no_toc .text-delta }

1. TOC
{:toc}

---

## Signature

```php
public static function utf8Ansi(?string $value = ''): string
```

## Parameters

| Parameter | Type | Default | Description |
|:----------|:-----|:--------|:------------|
| `$value` | `?string` | `''` | The UTF-8 encoded string to convert |

## Returns

`string` - The ANSI-compatible string. Returns empty string for null input.

---

## Description

The `utf8Ansi()` method converts UTF-8 encoded characters to their ANSI (Windows-1252) equivalents. This is essential for:

- **Legacy system integration** - Older systems that don't support UTF-8
- **File exports** - Creating files for systems with limited encoding support
- **Database compatibility** - Working with older database configurations

### Character Mapping

The method uses a comprehensive mapping array covering 60+ common character conversions, including:

- Accented vowels (a, e, i, o, u)
- Special consonants (c, n, ss)
- Currency and typographic symbols
- Extended Latin characters

---

## Examples

### Basic Usage

```php
use MarjovanLier\StringManipulation\StringManipulation;

$result = StringManipulation::utf8Ansi('Uber');
echo $result; // Output: Uber
```

### German Characters

```php
StringManipulation::utf8Ansi('Munchen');
// Output: Munchen

StringManipulation::utf8Ansi('Strasse');
// Output: Strasse (ss is converted)
```

### French Characters

```php
StringManipulation::utf8Ansi('francais');
// Output: francais

StringManipulation::utf8Ansi('cafe');
// Output: cafe
```

### Null Handling

```php
$result = StringManipulation::utf8Ansi(null);
// Result: '' (empty string)

$result = StringManipulation::utf8Ansi();
// Result: '' (empty string)
```

---

## Use Cases

### Legacy File Export

```php
function exportToLegacyFormat(array $records, string $filename): void
{
    $handle = fopen($filename, 'w');

    foreach ($records as $record) {
        $line = StringManipulation::utf8Ansi($record['name']);
        fwrite($handle, $line . "\n");
    }

    fclose($handle);
}
```

### Database Migration

```php
function migrateToLegacyDatabase(PDO $legacy, array $users): void
{
    $stmt = $legacy->prepare('INSERT INTO users (name) VALUES (?)');

    foreach ($users as $user) {
        $ansiName = StringManipulation::utf8Ansi($user['name']);
        $stmt->execute([$ansiName]);
    }
}
```

### API Response Formatting

```php
function formatForLegacyApi(array $data): array
{
    return array_map(function ($item) {
        return [
            'id' => $item['id'],
            'name' => StringManipulation::utf8Ansi($item['name']),
            'description' => StringManipulation::utf8Ansi($item['description']),
        ];
    }, $data);
}
```

---

## Character Conversion Examples

| UTF-8 Input | ANSI Output |
|:------------|:------------|
| `a` | `a` |
| `e` | `e` |
| `u` | `u` |
| `o` | `o` |
| `c` | `c` |
| `n` | `n` |
| `ss` | `ss` |

---

## Related Methods

- [`removeAccents()`]({{ site.baseurl }}/api-reference/remove-accents/) - Removes accents without encoding conversion
- [`nameFix()`]({{ site.baseurl }}/api-reference/name-fix/) - Uses utf8Ansi internally for name processing
