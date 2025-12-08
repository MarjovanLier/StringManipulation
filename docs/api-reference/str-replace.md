---
layout: default
title: strReplace()
parent: API Reference
nav_order: 6
---

# strReplace()
{: .no_toc }

Optimised string replacement utility with performance enhancements.
{: .fs-6 .fw-300 }

## Table of contents
{: .no_toc .text-delta }

1. TOC
{:toc}

---

## Signature

```php
public static function strReplace(
    array|string $search,
    array|string $replace,
    string $subject
): string
```

## Parameters

| Parameter | Type | Description |
|:----------|:-----|:------------|
| `$search` | `array\|string` | The value(s) to search for |
| `$replace` | `array\|string` | The replacement value(s) |
| `$subject` | `string` | The string to perform replacements on |

## Returns

`string` - The string with replacements applied. Returns empty string if subject is empty.

---

## Description

The `strReplace()` method is an optimised wrapper around PHP's native `str_replace()` with additional performance enhancements:

- **Single-character optimisation** - Uses `strtr()` for single-character replacements (faster)
- **Early return** - Returns immediately for empty subjects
- **Type safety** - Enforces string return type

---

## Examples

### Basic Usage

```php
use MarjovanLier\StringManipulation\StringManipulation;

$result = StringManipulation::strReplace('world', 'PHP', 'Hello world');
echo $result; // Output: Hello PHP
```

### Multiple Replacements

```php
$search = ['one', 'two', 'three'];
$replace = ['1', '2', '3'];
$subject = 'one two three';

$result = StringManipulation::strReplace($search, $replace, $subject);
echo $result; // Output: 1 2 3
```

### Character Replacement

```php
// Single character replacement (uses optimised strtr)
$result = StringManipulation::strReplace('_', ' ', 'hello_world');
echo $result; // Output: hello world
```

### Array Search with Single Replace

```php
$search = ['a', 'e', 'i', 'o', 'u'];
$replace = '*';
$subject = 'Hello World';

$result = StringManipulation::strReplace($search, $replace, $subject);
echo $result; // Output: H*ll* W*rld
```

### Empty Subject

```php
$result = StringManipulation::strReplace('foo', 'bar', '');
echo $result; // Output: '' (empty string)
```

---

## Use Cases

### Template Processing

```php
function processTemplate(string $template, array $variables): string
{
    $search = array_map(fn($key) => '{{' . $key . '}}', array_keys($variables));
    $replace = array_values($variables);

    return StringManipulation::strReplace($search, $replace, $template);
}

$template = 'Hello {{name}}, welcome to {{site}}!';
$result = processTemplate($template, [
    'name' => 'John',
    'site' => 'Example.com',
]);
// Result: Hello John, welcome to Example.com!
```

### Sanitisation

```php
function sanitiseFilename(string $filename): string
{
    $unsafe = ['/', '\\', ':', '*', '?', '"', '<', '>', '|'];
    $safe = '_';

    return StringManipulation::strReplace($unsafe, $safe, $filename);
}

$filename = sanitiseFilename('report:2023/12.pdf');
// Result: report_2023_12.pdf
```

### Text Normalisation

```php
function normaliseWhitespace(string $text): string
{
    $whitespace = ["\r\n", "\r", "\t"];
    $space = ' ';

    return StringManipulation::strReplace($whitespace, $space, $text);
}
```

---

## Performance Notes

- For single-character replacements, `strtr()` is used internally for better performance
- For multiple replacements, standard `str_replace()` is used
- Empty subject strings return immediately without processing

---

## Related Methods

- [`removeAccents()`]({{ site.baseurl }}/api-reference/remove-accents/) - Uses strReplace internally
- [`searchWords()`]({{ site.baseurl }}/api-reference/search-words/) - Uses strReplace for character normalisation
