# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

High-performance PHP 8.3+ string manipulation library with zero production dependencies. Single final class (`StringManipulation`) exposing static methods optimised with pre-computed character mappings for O(1) lookups via `strtr()`.

## Build & Test Commands

**Always use Docker** to ensure consistent PHP 8.3 + AST extension environment.

| Task | Docker | Local |
|------|--------|-------|
| Full pipeline | `docker-compose run --rm test-all` | `composer tests` |
| Pest tests | `docker-compose run --rm tests ./vendor/bin/pest` | `./vendor/bin/pest` |
| Single test | `docker-compose run --rm tests ./vendor/bin/pest --filter testName` | `./vendor/bin/pest --filter testName` |
| Code style check | `docker-compose run --rm test-code-style` | `composer test:code-style` |
| Code style fix | `docker-compose run --rm tests ./vendor/bin/pint` | `composer fix:code-style` |
| PHPStan | `docker-compose run --rm test-phpstan` | `composer test:phpstan` |
| Psalm | `docker-compose run --rm test-psalm` | `composer test:psalm` |
| Phan | `docker-compose run --rm test-phan` | `composer test:phan` |
| PHPMD | `docker-compose run --rm test-phpmd` | `composer test:phpmd` |
| Mutation testing | `docker-compose run --rm test-infection` | `composer test:infection` |
| Rector (dry-run) | `docker-compose run --rm test-rector` | `composer test:rector` |
| Linting | `docker-compose run --rm test-lint` | `composer test:lint` |
| Security check | `docker-compose run --rm test-security` | `composer test:vulnerabilities-check` |

**Run the full pipeline before committing**: `docker-compose run --rm test-all`

## Architecture

```
src/
├── StringManipulation.php    # Single final class, all public static methods
├── AccentNormalization.php   # Trait: SEARCH_WORDS_MAPPING + ACCENT_MAPPING constants
└── UnicodeMappings.php       # Trait: UTF8_ANSI2 constant
```

- **StringManipulation** uses two traits purely as constant containers for pre-computed character mapping arrays
- All methods are static; the class is never instantiated
- Performance comes from `strtr()` with pre-computed array constants (hash table O(1) lookups) and single-pass algorithms
- The traits exist to keep the ~600 lines of mapping data separate from the ~400 lines of logic

### Public API (7 methods)

| Method | Purpose |
|--------|---------|
| `searchWords(?string)` | Normalise strings for database search (lowercase, remove accents/special chars) |
| `nameFix(?string)` | Standardise surnames (handles mc/mac prefixes, van/von/de particles, hyphens) |
| `removeAccents(string)` | Strip diacritics preserving case |
| `utf8Ansi(?string)` | Convert UTF-8 to ANSI equivalents |
| `isValidDate(string, string)` | Validate date string against format with logical checks |
| `strReplace(...)` | Optimised replacement (uses `strtr()` for single-char) |
| `trim(string, string)` | Stricter trim |

## Static Analysis Levels

All analysers run at their strictest settings:
- **PHPStan**: Level MAX with strict rules, type-perfect, 95%+ type coverage
- **Psalm**: Level 1, taint analysis enabled, 99.95% type coverage
- **Phan**: All strict checking flags enabled, 11 quality plugins
- **PHPMD**: Max 100 lines/method, max 15 public methods/class

## Code Conventions

- `declare(strict_types=1)` on every file
- Final classes, static methods
- Typed constants: `private const array NAME = []`
- Comprehensive docblocks with `@param`, `@return`, `@example` on public methods
- `#[\SensitiveParameter]` attribute on parameters containing personal data
- Code style: Laravel Pint with PER (PHP Evolving Rules) preset
- South African English in documentation (organisation, colour, centre)

## Test Organisation

Tests live in `tests/Unit/` using Pest v4 syntax (`test('...', fn() => expect(...)->toBe(...))`).

Tests are split by method and concern: `NameFixTest`, `NameFixEdgeCasesTest`, `NameFixNegativeFlowTest`, `NameFixSpecialCharactersTest`, etc. Bug regressions get dedicated test files (e.g., `ArrayCombineValidationBugFixTest`, `UppercaseAccentMappingBugFixTest`).

Mutation testing target MSI: 88%.

## CI

GitHub Actions runs the test matrix against PHP 8.3, 8.4, and 8.5 with vulnerability scanning (osv-scanner + Enlightn).
