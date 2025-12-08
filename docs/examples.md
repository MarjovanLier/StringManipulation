---
layout: default
title: Examples
nav_order: 5
---

# Examples
{: .no_toc }

Practical examples and common use cases for the StringManipulation library.
{: .fs-6 .fw-300 }

## Table of contents
{: .no_toc .text-delta }

1. TOC
{:toc}

---

## Search Functionality

### Building a Searchable Index

Store normalised text alongside original values for efficient searching:

```php
<?php

declare(strict_types=1);

use MarjovanLier\StringManipulation\StringManipulation;

class Product
{
    public string $name;
    public string $nameSearchable;
    public string $description;
    public string $descriptionSearchable;

    public function __construct(string $name, string $description)
    {
        $this->name = $name;
        $this->nameSearchable = StringManipulation::searchWords($name) ?? '';

        $this->description = $description;
        $this->descriptionSearchable = StringManipulation::searchWords($description) ?? '';
    }
}

// Usage
$product = new Product(
    'Cafe Creme Brulee',
    'Authentic French dessert with caramelised sugar top'
);

// Search query
$query = StringManipulation::searchWords('creme brulee');

// Database query
$sql = "SELECT * FROM products
        WHERE name_searchable LIKE :query
        OR description_searchable LIKE :query";
```

### Multi-Language Search

Handle international text in search queries:

```php
function search(string $query, PDO $db): array
{
    $normalised = StringManipulation::searchWords($query);

    if ($normalised === null || $normalised === '') {
        return [];
    }

    $terms = explode(' ', $normalised);

    $conditions = array_map(
        fn($i) => "search_index LIKE :term{$i}",
        array_keys($terms)
    );

    $sql = "SELECT * FROM items WHERE " . implode(' AND ', $conditions);
    $stmt = $db->prepare($sql);

    foreach ($terms as $i => $term) {
        $stmt->bindValue(":term{$i}", "%{$term}%");
    }

    $stmt->execute();
    return $stmt->fetchAll();
}

// Searches work regardless of accents
search('cafe', $db);      // Finds 'Cafe'
search('munchen', $db);   // Finds 'Munchen'
search('francais', $db);  // Finds 'francais'
```

---

## User Data Processing

### Registration Form Handling

Standardise user input during registration:

```php
class UserRegistrationService
{
    public function register(array $formData): User
    {
        $user = new User();

        // Standardise names
        $user->firstName = $this->capitaliseName($formData['first_name']);
        $user->lastName = StringManipulation::nameFix($formData['last_name']);

        // Create searchable version for lookups
        $user->fullNameSearch = StringManipulation::searchWords(
            $user->firstName . ' ' . $user->lastName
        );

        // Validate birth date
        if (!StringManipulation::isValidDate($formData['birth_date'], 'Y-m-d')) {
            throw new ValidationException('Invalid birth date');
        }
        $user->birthDate = $formData['birth_date'];

        return $user;
    }

    private function capitaliseName(string $name): string
    {
        return ucwords(strtolower(trim($name)));
    }
}
```

### Address Standardisation

Clean and normalise address data:

```php
class AddressNormaliser
{
    public function normalise(array $address): array
    {
        return [
            'street' => StringManipulation::trim($address['street'] ?? ''),
            'city' => StringManipulation::nameFix($address['city'] ?? ''),
            'country' => StringManipulation::removeAccents($address['country'] ?? ''),
            // Searchable version for geocoding/lookup
            'search_key' => StringManipulation::searchWords(
                implode(' ', [
                    $address['street'] ?? '',
                    $address['city'] ?? '',
                    $address['postal_code'] ?? '',
                ])
            ),
        ];
    }
}

$normaliser = new AddressNormaliser();
$address = $normaliser->normalise([
    'street' => '  Rue de la Cafe  ',
    'city' => 'munchen',
    'postal_code' => '80331',
    'country' => 'Deutschland',
]);

// Result:
// [
//     'street' => 'Rue de la Cafe',
//     'city' => 'Munchen',
//     'country' => 'Deutschland',
//     'search_key' => 'rue de la cafe munchen 80331',
// ]
```

---

## Data Import/Export

### CSV Import with Validation

Process CSV files with data cleaning:

```php
class CsvImporter
{
    public function import(string $filepath): array
    {
        $handle = fopen($filepath, 'r');
        $headers = fgetcsv($handle);
        $records = [];
        $errors = [];
        $line = 1;

        while (($row = fgetcsv($handle)) !== false) {
            $line++;
            $data = array_combine($headers, $row);

            try {
                $records[] = $this->processRow($data, $line);
            } catch (ValidationException $e) {
                $errors[] = "Line {$line}: {$e->getMessage()}";
            }
        }

        fclose($handle);

        return ['records' => $records, 'errors' => $errors];
    }

    private function processRow(array $data, int $line): array
    {
        // Validate date
        if (!StringManipulation::isValidDate($data['date'], 'Y-m-d')) {
            throw new ValidationException("Invalid date format");
        }

        // Clean and normalise
        return [
            'name' => StringManipulation::nameFix($data['name']),
            'name_search' => StringManipulation::searchWords($data['name']),
            'date' => $data['date'],
            'description' => StringManipulation::removeAccents(
                StringManipulation::trim($data['description'])
            ),
        ];
    }
}
```

### Legacy System Export

Convert data for systems that don't support UTF-8:

```php
class LegacyExporter
{
    public function export(array $records, string $filepath): void
    {
        $handle = fopen($filepath, 'w');

        // Write header
        fwrite($handle, "NAME|DESCRIPTION|DATE\n");

        foreach ($records as $record) {
            $line = implode('|', [
                StringManipulation::utf8Ansi($record['name']),
                StringManipulation::utf8Ansi($record['description']),
                $record['date'],
            ]);
            fwrite($handle, $line . "\n");
        }

        fclose($handle);
    }
}
```

---

## API Development

### Request Validation Middleware

Validate and clean incoming API requests:

```php
class ValidationMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $data = $request->all();

        // Clean string inputs
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $data[$key] = StringManipulation::trim($value);
            }
        }

        // Validate dates
        if (isset($data['start_date'])) {
            if (!StringManipulation::isValidDate($data['start_date'], 'Y-m-d')) {
                return response()->json([
                    'error' => 'Invalid start_date format. Use Y-m-d.',
                ], 422);
            }
        }

        $request->merge($data);

        return $next($request);
    }
}
```

### Search API Endpoint

Build a search API with normalised queries:

```php
class SearchController
{
    public function search(Request $request): JsonResponse
    {
        $query = $request->input('q', '');

        // Normalise search query
        $normalised = StringManipulation::searchWords($query);

        if ($normalised === null || strlen($normalised) < 2) {
            return response()->json([
                'error' => 'Search query too short',
            ], 400);
        }

        $results = $this->repository->search($normalised);

        return response()->json([
            'query' => $query,
            'normalised_query' => $normalised,
            'results' => $results,
            'count' => count($results),
        ]);
    }
}
```

---

## URL and Slug Generation

### SEO-Friendly URLs

Create clean URL slugs from titles:

```php
class SlugGenerator
{
    public function generate(string $title): string
    {
        // Remove accents
        $slug = StringManipulation::removeAccents($title);

        // Convert to lowercase
        $slug = strtolower($slug);

        // Replace non-alphanumeric with hyphens
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);

        // Trim hyphens
        return StringManipulation::trim($slug, '-');
    }
}

$generator = new SlugGenerator();

echo $generator->generate('Cafe Creme Brulee');
// Output: cafe-creme-brulee

echo $generator->generate('Munchen Guide 2024!');
// Output: munchen-guide-2024
```

---

## E-commerce

### Product Catalogue Processing

Standardise product data for a catalogue:

```php
class ProductProcessor
{
    public function process(array $rawProduct): array
    {
        $name = StringManipulation::nameFix($rawProduct['name']);

        return [
            'name' => $name,
            'slug' => $this->generateSlug($rawProduct['name']),
            'search_terms' => StringManipulation::searchWords(
                $rawProduct['name'] . ' ' . ($rawProduct['category'] ?? '')
            ),
            'description' => StringManipulation::trim($rawProduct['description'] ?? ''),
            'sku' => strtoupper(StringManipulation::trim($rawProduct['sku'] ?? '')),
        ];
    }

    private function generateSlug(string $name): string
    {
        $slug = StringManipulation::removeAccents($name);
        $slug = strtolower($slug);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        return trim($slug, '-');
    }
}
```

---

## Logging and Monitoring

### Clean Log Messages

Sanitise data before logging:

```php
class SecureLogger
{
    public function log(string $level, string $message, array $context = []): void
    {
        // Clean control characters from message
        $cleanMessage = StringManipulation::trim(
            $message,
            " \t\n\r\0\x0B\x1B" // Include escape character
        );

        // Sanitise context values
        $cleanContext = array_map(function ($value) {
            if (is_string($value)) {
                return StringManipulation::utf8Ansi($value);
            }
            return $value;
        }, $context);

        $this->logger->log($level, $cleanMessage, $cleanContext);
    }
}
```
