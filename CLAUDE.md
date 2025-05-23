# StringManipulation Development Guide

## Critical Development Rules
- **ALWAYS run all tests before committing**: `docker-compose run --rm test-all`
- **NEVER force push or use --force with git commands**
- **NEVER ignore test failures or errors**
- **ALWAYS use conventional commit messages** (feat:, fix:, chore:, etc.)
- **NEVER overwrite or amend existing commit messages**

## Docker Testing Commands
- Run all tests in Docker: `docker-compose run --rm test-all`
- Run specific test: `docker-compose run --rm test-phpunit`
- Available test services: `test-code-style`, `test-phpunit`, `test-phpstan`, `test-psalm`, `test-phan`, `test-phpmd`, `test-infection`, `test-rector`, `test-lint`, `test-security`

## Build & Testing Commands
- Run all tests: `composer tests`
- Run single test: `./vendor/bin/phpunit --filter testClassName` or `./vendor/bin/phpunit --filter '/::testMethodName$/'`
- Code style check: `composer test:code-style`
- Static analysis: `composer test:phpstan`, `composer test:psalm`, `composer test:phan`
- Linting: `composer test:lint`
- Mess detection: `composer test:phpmd`

## Code Style Guidelines
- PHP version: >=8.3.0
- Strict typing required: `declare(strict_types=1);`
- Namespaces: PSR-4 `MarjovanLier\StringManipulation`
- Classes: Final, static methods preferred
- Class constants: Use typed constants (e.g., `private const array FOO = []`)
- Docblocks: Comprehensive for public methods with @param, @return and @example
- Parameter typing: Always explicit, including return types
- Type hints: Use PHP 8 attributes like `#[SensitiveParameter]` where appropriate
- Null handling: Explicit checks, optional parameters default to empty string
- Documentation: 100% method coverage with examples
- Standards: PSR guidelines with Laravel Pint (preset "per")
- Testing: PHPUnit with complete coverage
- PHPMD: Methods must not exceed 100 lines
