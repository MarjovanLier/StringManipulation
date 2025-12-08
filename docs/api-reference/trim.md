---
layout: default
title: trim()
parent: API Reference
nav_order: 7
---

# trim()
{: .no_toc }

Removes specified characters from the beginning and end of a string.
{: .fs-6 .fw-300 }

## Table of contents
{: .no_toc .text-delta }

1. TOC
{:toc}

---

## Signature

```php
public static function trim(string $string, string $characters = " \t\n\r\0\x0B"): string
```

## Parameters

| Parameter | Type | Default | Description |
|:----------|:-----|:--------|:------------|
| `$string` | `string` | - | The input string to trim |
| `$characters` | `string` | `" \t\n\r\0\x0B"` | Characters to remove from both ends |

## Returns

`string` - The trimmed string.

---

## Description

The `trim()` method removes specified characters from both the beginning and end of a string. By default, it removes common whitespace characters:

| Character | Description |
|:----------|:------------|
| ` ` | Space |
| `\t` | Tab |
| `\n` | Newline (line feed) |
| `\r` | Carriage return |
| `\0` | Null byte |
| `\x0B` | Vertical tab |

This method provides more explicit control over character removal compared to PHP's built-in `trim()`.

---

## Examples

### Basic Usage

```php
use MarjovanLier\StringManipulation\StringManipulation;

$result = StringManipulation::trim('  Hello World  ');
echo $result; // Output: Hello World
```

### Custom Characters

```php
// Remove specific characters
$result = StringManipulation::trim('###Hello###', '#');
echo $result; // Output: Hello

// Remove multiple custom characters
$result = StringManipulation::trim('***Hello***', '*#');
echo $result; // Output: Hello
```

### Whitespace Handling

```php
// Tabs and newlines
$input = "\t\nHello World\n\t";
$result = StringManipulation::trim($input);
echo $result; // Output: Hello World

// Mixed whitespace
$input = "  \t  Hello  \n  ";
$result = StringManipulation::trim($input);
echo $result; // Output: Hello
```

### URL Path Cleaning

```php
$path = '/api/users/';
$result = StringManipulation::trim($path, '/');
echo $result; // Output: api/users
```

---

## Use Cases

### Form Input Cleaning

```php
function cleanFormInput(array $input): array
{
    return array_map(function ($value) {
        if (is_string($value)) {
            return StringManipulation::trim($value);
        }
        return $value;
    }, $input);
}

$input = [
    'name' => '  John Doe  ',
    'email' => '  john@example.com  ',
];

$cleaned = cleanFormInput($input);
// Result: ['name' => 'John Doe', 'email' => 'john@example.com']
```

### CSV Parsing

```php
function parseCSVLine(string $line): array
{
    $fields = explode(',', $line);

    return array_map(function ($field) {
        // Remove quotes and whitespace
        return StringManipulation::trim($field, " \t\n\r\"'");
    }, $fields);
}

$line = '"John" , "Doe" , "john@example.com"';
$fields = parseCSVLine($line);
// Result: ['John', 'Doe', 'john@example.com']
```

### Path Normalisation

```php
function normalisePath(string $path): string
{
    // Remove trailing slashes
    $path = StringManipulation::trim($path, '/\\');

    // Ensure leading slash
    return '/' . $path;
}

$path = normalisePath('/api/users//');
// Result: /api/users
```

### Log Message Cleaning

```php
function cleanLogMessage(string $message): string
{
    // Remove control characters and excessive whitespace
    return StringManipulation::trim($message, " \t\n\r\0\x0B\x1B");
}
```

---

## Comparison with PHP trim()

| Feature | `StringManipulation::trim()` | PHP `trim()` |
|:--------|:-----------------------------|:-------------|
| Type safety | Explicit string parameter | Mixed input |
| Return type | Guaranteed string | String (with type juggling) |
| Default chars | Same whitespace set | Same whitespace set |
| Custom chars | Second parameter | Second parameter |

---

## Related Methods

- [`strReplace()`]({{ site.baseurl }}/api-reference/str-replace/) - For replacing characters within strings
- [`searchWords()`]({{ site.baseurl }}/api-reference/search-words/) - Includes trimming in normalisation
