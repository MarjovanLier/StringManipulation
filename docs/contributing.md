---
layout: default
title: Contributing
nav_order: 6
---

# Contributing
{: .no_toc }

Guidelines for contributing to the StringManipulation library.
{: .fs-6 .fw-300 }

## Table of contents
{: .no_toc .text-delta }

1. TOC
{:toc}

---

## Getting Started

We welcome contributions to the StringManipulation library! Whether you're fixing bugs, adding features, improving documentation, or writing tests, your help is appreciated.

### Prerequisites

- PHP 8.4 or later
- Docker and Docker Compose (recommended for testing)
- Git
- Composer

### Setting Up Development Environment

1. **Fork the repository** on GitHub

2. **Clone your fork**:
   ```bash
   git clone https://github.com/YOUR-USERNAME/StringManipulation.git
   cd StringManipulation
   ```

3. **Install dependencies**:
   ```bash
   composer install
   ```

4. **Verify the setup**:
   ```bash
   docker-compose run --rm test-all
   ```

---

## Development Workflow

### Creating a Branch

Create a feature branch from `main`:

```bash
git checkout main
git pull origin main
git checkout -b feature/your-feature-name
```

Use descriptive branch names:
- `feature/add-new-method`
- `fix/handle-null-input`
- `docs/update-examples`
- `test/improve-coverage`

### Making Changes

1. **Write tests first** - Follow TDD principles
2. **Implement your changes**
3. **Ensure all tests pass**
4. **Update documentation** if needed
5. **Commit with conventional messages**

---

## Code Standards

### PHP Standards

- **Strict typing**: All files must include `declare(strict_types=1);`
- **PHP 8.4+**: Use modern PHP features
- **PSR-4 autoloading**: Follow namespace conventions
- **Final classes**: Prefer final classes with static methods
- **Typed parameters**: Always use explicit type declarations
- **Docblocks**: Comprehensive documentation for public methods

### Style Guidelines

The project uses Laravel Pint with the "per" preset:

```bash
# Check code style
docker-compose run --rm test-code-style

# Fix code style automatically
docker-compose run --rm tests ./vendor/bin/pint
```

### Example Method Structure

```php
/**
 * Brief description of what the method does.
 *
 * Longer description if needed, explaining the algorithm
 * or any important considerations.
 *
 * @param string $input The input parameter description
 *
 * @return string The return value description
 *
 * @example
 * $result = StringManipulation::methodName('example');
 * // Returns: 'processed example'
 */
public static function methodName(string $input): string
{
    // Implementation
}
```

---

## Testing Requirements

### Running Tests

```bash
# Run all tests (recommended)
docker-compose run --rm test-all

# Run Pest tests only
docker-compose run --rm tests ./vendor/bin/pest

# Run specific test file
docker-compose run --rm tests ./vendor/bin/pest tests/Unit/YourTest.php

# Run with coverage
docker-compose run --rm tests ./vendor/bin/pest --coverage
```

### Test Categories

| Test Type | Location | Purpose |
|:----------|:---------|:--------|
| Unit tests | `tests/Unit/` | Test individual methods |
| Benchmark tests | `tests/Benchmark/` | Performance verification |

### Writing Tests

Use Pest PHP syntax:

```php
<?php

declare(strict_types=1);

use MarjovanLier\StringManipulation\StringManipulation;

describe('methodName', function (): void {
    it('handles basic input', function (): void {
        $result = StringManipulation::methodName('input');

        expect($result)->toBe('expected');
    });

    it('handles null input', function (): void {
        $result = StringManipulation::methodName(null);

        expect($result)->toBeNull();
    });

    it('handles edge cases', function (): void {
        expect(StringManipulation::methodName(''))->toBe('');
        expect(StringManipulation::methodName(' '))->toBe(' ');
    });
});
```

### Coverage Requirements

- **100% line coverage** for new methods
- **Edge cases** must be tested
- **Null handling** must be verified
- **Performance tests** for O(n) verification

---

## Static Analysis

The project uses three static analysis tools:

### PHPStan (Level Max)

```bash
docker-compose run --rm test-phpstan
```

Requirements:
- No errors at level max
- Strict rules enabled
- All types must be inferable

### Psalm (Level 1)

```bash
docker-compose run --rm test-psalm
```

Requirements:
- No errors at level 1
- 99.95%+ type coverage

### Phan

```bash
docker-compose run --rm test-phan
```

Requirements:
- Clean analysis results

---

## Commit Guidelines

Use conventional commit messages:

```
type(scope): subject

body (optional)

footer (optional)
```

### Types

| Type | Description |
|:-----|:------------|
| `feat` | New feature |
| `fix` | Bug fix |
| `docs` | Documentation only |
| `style` | Code style changes |
| `refactor` | Code refactoring |
| `perf` | Performance improvement |
| `test` | Adding/updating tests |
| `chore` | Maintenance tasks |

### Examples

```
feat(nameFix): add support for Portuguese prefixes

Add handling for 'da', 'das', 'do', 'dos' prefixes
commonly found in Portuguese surnames.
```

```
fix(isValidDate): handle edge case for February 29

Correctly validate leap year dates by checking
the actual year, not just the format.
```

---

## Pull Request Process

1. **Create your PR** against the `main` branch

2. **Fill out the PR template** with:
   - Description of changes
   - Related issue (if any)
   - Testing performed
   - Checklist completion

3. **Ensure CI passes**:
   - All tests green
   - Code style check passes
   - Static analysis passes
   - Coverage maintained

4. **Address review feedback** promptly

5. **Squash commits** if requested

### PR Checklist

- [ ] Tests added/updated
- [ ] Documentation updated
- [ ] Code style passes
- [ ] Static analysis passes
- [ ] Coverage maintained at 100%
- [ ] Conventional commit message

---

## Reporting Issues

### Bug Reports

Include:
- PHP version
- Library version
- Minimal reproduction code
- Expected vs actual behaviour
- Error messages (if any)

### Feature Requests

Include:
- Use case description
- Proposed API (if applicable)
- Examples of expected behaviour
- Alternatives considered

---

## Questions?

- Open a [GitHub issue](https://github.com/MarjovanLier/StringManipulation/issues) for questions
- Check existing issues and discussions first
- Be respectful and patient

Thank you for contributing!
