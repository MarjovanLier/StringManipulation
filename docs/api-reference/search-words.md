---
layout: default
title: searchWords()
parent: API Reference
nav_order: 2
---

# searchWords()
{: .no_toc }

Transforms strings into a search-optimised format ideal for database queries.
{: .fs-6 .fw-300 }

## Table of contents
{: .no_toc .text-delta }

1. TOC
{:toc}

---

## Signature

```php
public static function searchWords(?string $words): ?string
```

## Parameters

| Parameter | Type      | Description                                           |
|:----------|:----------|:------------------------------------------------------|
| `$words`  | `?string` | The input string to optimise for search. Can be null. |

## Returns

`?string` - The search-optimised string, or `null` if input was null.

---

## Description

The `searchWords()` method transforms text into a format optimised for database searching. It performs multiple transformations in a single pass:

1. **Name fixing** - Applies naming conventions (Mc/Mac prefixes)
2. **Lowercase conversion** - For case-insensitive matching
3. **Special character replacement** - Converts punctuation and symbols to spaces
4. **Accent removal** - Strips diacritics for normalised matching
5. **Space normalisation** - Reduces multiple spaces to single space

### Performance

- **~195,000 operations per second**
- O(n) single-pass algorithm
- Combined character mapping for efficiency

---

## Examples

### Basic Usage

```php
use MarjovanLier\StringManipulation\StringManipulation;

$result = StringManipulation::searchWords('Hello_World');
echo $result; // Output: hello world
```

### Email Addresses

```php
StringManipulation::searchWords('John_Doe@Example.com');
// Output: john doe example com
```

### Accented Text

```php
StringManipulation::searchWords('Cafe Munchen');
// Output: cafe munchen
```

### Names with Prefixes

```php
StringManipulation::searchWords('McDonald van der Berg');
// Output: mcdonald van der berg
```

### Null Handling

```php
$result = StringManipulation::searchWords(null);
// Result: null
```

---

## Use Cases

### Database Search Column

Store a search-optimised version alongside the original:

```php
class User
{
    public string $lastName;
    public string $lastNameSearch;

    public function setLastName(string $name): void
    {
        $this->lastName = $name;
        $this->lastNameSearch = StringManipulation::searchWords($name);
    }
}

// Usage
$user->setLastName("O'Brien-McDonald");
// $user->lastName = "O'Brien-McDonald"
// $user->lastNameSearch = "o brien mcdonald"

// Query: WHERE last_name_search LIKE '%brien%'
```

### Full-Text Search Preparation

```php
function prepareForSearch(string $query): string
{
    $optimised = StringManipulation::searchWords($query);

    // Split into individual terms
    $terms = explode(' ', $optimised);

    // Filter empty terms
    return implode(' ', array_filter($terms));
}

$searchQuery = prepareForSearch("Cafe & Restaurant - Munchen");
// Result: cafe restaurant munchen
```

### Data Import Normalisation

```php
function normaliseImportedData(array $records): array
{
    return array_map(function ($record) {
        return [
            'name' => $record['name'],
            'name_searchable' => StringManipulation::searchWords($record['name']),
            'address' => $record['address'],
            'address_searchable' => StringManipulation::searchWords($record['address']),
        ];
    }, $records);
}
```

---

## Transformations Applied

| Input | Output | Transformation |
|:------|:-------|:---------------|
| `Hello_World` | `hello world` | Underscore to space, lowercase |
| `test@example.com` | `test example com` | Special chars to space |
| `Cafe` | `cafe` | Accent removal, lowercase |
| `McDonald` | `mcdonald` | Name fixing, lowercase |
| `"Hello, World!"` | `hello world` | Punctuation removal |

---

## Related Methods

- [`nameFix()`]({{ site.baseurl }}/api-reference/name-fix/) - Name standardisation only
- [`removeAccents()`]({{ site.baseurl }}/api-reference/remove-accents/) - Accent removal only
