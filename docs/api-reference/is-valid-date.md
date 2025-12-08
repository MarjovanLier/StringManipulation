---
layout: default
title: isValidDate()
parent: API Reference
nav_order: 5
---

# isValidDate()
{: .no_toc }

Validates date strings against specified formats with logical consistency checks.
{: .fs-6 .fw-300 }

## Table of contents
{: .no_toc .text-delta }

1. TOC
{:toc}

---

## Signature

```php
public static function isValidDate(string $date, string $format = 'Y-m-d H:i:s'): bool
```

## Parameters

| Parameter | Type | Default | Description |
|:----------|:-----|:--------|:------------|
| `$date` | `string` | - | The date string to validate |
| `$format` | `string` | `'Y-m-d H:i:s'` | The expected date format (PHP date format) |

## Returns

`bool` - `true` if the date is valid, `false` otherwise.

---

## Description

The `isValidDate()` method provides comprehensive date validation beyond simple format checking. It validates:

1. **Format conformance** - Date matches the specified format pattern
2. **Logical date validity** - Day exists in month (e.g., no February 30th)
3. **Time component validity**:
   - Hours: 0-23
   - Minutes: 0-59
   - Seconds: 0-59

This prevents accepting dates like `2023-02-30` or `2023-12-25 25:00:00` that would be accepted by loose parsing.

---

## Examples

### Basic Usage

```php
use MarjovanLier\StringManipulation\StringManipulation;

$isValid = StringManipulation::isValidDate('2023-12-25 12:30:00');
echo $isValid ? 'Valid' : 'Invalid'; // Output: Valid
```

### Date Only

```php
StringManipulation::isValidDate('2023-12-25', 'Y-m-d');
// Result: true

StringManipulation::isValidDate('25-12-2023', 'd-m-Y');
// Result: true

StringManipulation::isValidDate('12/25/2023', 'm/d/Y');
// Result: true
```

### Invalid Dates

```php
// February 30th doesn't exist
StringManipulation::isValidDate('2023-02-30', 'Y-m-d');
// Result: false

// Month 13 doesn't exist
StringManipulation::isValidDate('2023-13-01', 'Y-m-d');
// Result: false

// Day 32 doesn't exist
StringManipulation::isValidDate('2023-01-32', 'Y-m-d');
// Result: false
```

### Invalid Times

```php
// Hour 25 doesn't exist
StringManipulation::isValidDate('2023-12-25 25:00:00');
// Result: false

// Minute 60 doesn't exist
StringManipulation::isValidDate('2023-12-25 12:60:00');
// Result: false

// Second 60 doesn't exist
StringManipulation::isValidDate('2023-12-25 12:30:60');
// Result: false
```

### Leap Year Handling

```php
// 2024 is a leap year
StringManipulation::isValidDate('2024-02-29', 'Y-m-d');
// Result: true

// 2023 is not a leap year
StringManipulation::isValidDate('2023-02-29', 'Y-m-d');
// Result: false
```

---

## Common Format Patterns

| Format | Example | Description |
|:-------|:--------|:------------|
| `Y-m-d H:i:s` | `2023-12-25 12:30:00` | Full datetime (default) |
| `Y-m-d` | `2023-12-25` | Date only |
| `d-m-Y` | `25-12-2023` | European date format |
| `m/d/Y` | `12/25/2023` | US date format |
| `Y-m-d H:i` | `2023-12-25 12:30` | Datetime without seconds |
| `d/m/Y H:i:s` | `25/12/2023 12:30:00` | European datetime |

---

## Use Cases

### Form Validation

```php
class DateValidator
{
    public function validateBirthDate(string $date): bool
    {
        if (!StringManipulation::isValidDate($date, 'Y-m-d')) {
            return false;
        }

        // Additional business rules
        $birthDate = new DateTime($date);
        $now = new DateTime();

        return $birthDate < $now;
    }
}
```

### API Input Validation

```php
function validateEventRequest(array $request): array
{
    $errors = [];

    if (!StringManipulation::isValidDate($request['start_date'] ?? '')) {
        $errors[] = 'Invalid start date format';
    }

    if (!StringManipulation::isValidDate($request['end_date'] ?? '')) {
        $errors[] = 'Invalid end date format';
    }

    return $errors;
}
```

### Data Import Validation

```php
function validateImportRow(array $row, int $lineNumber): array
{
    $errors = [];

    // Try multiple date formats
    $formats = ['Y-m-d', 'd-m-Y', 'm/d/Y'];
    $dateValid = false;

    foreach ($formats as $format) {
        if (StringManipulation::isValidDate($row['date'], $format)) {
            $dateValid = true;
            break;
        }
    }

    if (!$dateValid) {
        $errors[] = "Line {$lineNumber}: Invalid date format";
    }

    return $errors;
}
```

---

## Edge Cases

```php
// Empty string
StringManipulation::isValidDate('', 'Y-m-d');
// Result: false

// Wrong format
StringManipulation::isValidDate('2023-12-25', 'd-m-Y');
// Result: false (format mismatch)

// Partial match
StringManipulation::isValidDate('2023-12', 'Y-m-d');
// Result: false
```

---

## Related Methods

This method is standalone but often used alongside:

- [`searchWords()`]({{ site.baseurl }}/api-reference/search-words/) - For normalising date-containing text
- [`trim()`]({{ site.baseurl }}/api-reference/trim/) - For cleaning input before validation
