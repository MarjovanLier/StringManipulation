---
layout: default
title: nameFix()
parent: API Reference
nav_order: 3
---

# nameFix()
{: .no_toc }

Standardises names to conform to specific naming conventions with proper capitalisation.
{: .fs-6 .fw-300 }

## Table of contents
{: .no_toc .text-delta }

1. TOC
{:toc}

---

## Signature

```php
public static function nameFix(#[\SensitiveParameter] ?string $lastName): ?string
```

## Parameters

| Parameter   | Type      | Description                                             |
|:------------|:----------|:--------------------------------------------------------|
| `$lastName` | `?string` | The name to standardise. Marked as sensitive parameter. |

## Returns

`?string` - The standardised name, or `null` if input was null.

---

## Description

The `nameFix()` method standardises names by applying consistent capitalisation rules and handling various naming conventions correctly. It performs:

1. **UTF-8 to ANSI conversion** - For consistent character handling
2. **Accent removal** - Normalises characters
3. **Mc/Mac prefix handling** - Adds proper spacing and capitalisation
4. **Hyphenated name handling** - Capitalises each part
5. **Common prefix correction** - Handles van, von, de, du, la, le, etc.
6. **Space normalisation** - Reduces multiple spaces

### Performance

- **~130,000 operations per second**
- Consolidated regex operations
- Optimised prefix handling

### Security

The parameter is marked with `#[\SensitiveParameter]` to prevent name data from appearing in stack traces and error logs.

---

## Examples

### Basic Usage

```php
use MarjovanLier\StringManipulation\StringManipulation;

$result = StringManipulation::nameFix('mcdonald');
echo $result; // Output: McDonald
```

### Scottish/Irish Names

```php
// Mc prefix
StringManipulation::nameFix('mcdonald');
// Output: McDonald

// Mac prefix
StringManipulation::nameFix('macdonald');
// Output: MacDonald

// O' prefix
StringManipulation::nameFix("o'brien");
// Output: O'Brien
```

### Dutch/German Prefixes

```php
// van
StringManipulation::nameFix('van der waals');
// Output: van der Waals

// von
StringManipulation::nameFix('von neumann');
// Output: von Neumann

// de
StringManipulation::nameFix('de souza');
// Output: de Souza
```

### Hyphenated Names

```php
StringManipulation::nameFix('smith-jones');
// Output: Smith-Jones

StringManipulation::nameFix("o'brien-smith");
// Output: O'Brien-Smith
```

### Null Handling

```php
$result = StringManipulation::nameFix(null);
// Result: null
```

---

## Prefix Handling

The method handles these common prefixes by keeping them lowercase:

| Prefix | Example Input | Output |
|:-------|:--------------|:-------|
| van | `van berg` | `van Berg` |
| von | `von stein` | `von Stein` |
| de | `de silva` | `de Silva` |
| du | `du pont` | `du Pont` |
| la | `la rue` | `la Rue` |
| le | `le blanc` | `le Blanc` |
| der | `van der berg` | `van der Berg` |
| den | `van den berg` | `van den Berg` |

---

## Use Cases

### User Registration

```php
class UserRegistration
{
    public function normaliseUserName(string $lastName): string
    {
        $normalised = StringManipulation::nameFix($lastName);

        if ($normalised === null) {
            throw new InvalidArgumentException('Last name is required');
        }

        return $normalised;
    }
}

$registration = new UserRegistration();
$name = $registration->normaliseUserName('VAN DER BERG');
// Result: van der Berg
```

### Data Import Standardisation

```php
function standardiseNames(array $users): array
{
    return array_map(function ($user) {
        return [
            ...$user,
            'last_name' => StringManipulation::nameFix($user['last_name']),
        ];
    }, $users);
}
```

### Display Formatting

```php
function formatDisplayName(string $firstName, string $lastName): string
{
    $standardisedLast = StringManipulation::nameFix($lastName);

    return sprintf('%s %s', ucfirst($firstName), $standardisedLast);
}

echo formatDisplayName('john', 'mcdonald');
// Output: John McDonald
```

---

## Related Methods

- [`searchWords()`]({{ site.baseurl }}/api-reference/search-words/) - Includes name fixing with search optimisation
- [`removeAccents()`]({{ site.baseurl }}/api-reference/remove-accents/) - Accent removal only
- [`utf8Ansi()`]({{ site.baseurl }}/api-reference/utf8-ansi/) - Encoding conversion
