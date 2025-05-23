# StringManipulation Development Guide

## Build & Testing Commands
- Run all tests: `composer tests`
- Run single test: `./vendor/bin/phpunit --filter testClassName` or `./vendor/bin/phpunit --filter '/::testMethodName$/'`
- Code style check: `composer test:code-style`
- Static analysis: `composer test:phpstan`, `composer test:psalm`, `composer test:phan`
- Linting: `composer test:lint`

## Code Style Guidelines
- PHP version: >=8.3.0
- Strict typing required: `declare(strict_types=1);`
- Namespaces: PSR-4 `MarjovanLier\StringManipulation`
- Classes: Final, static methods preferred
- Docblocks: Comprehensive for public methods with @param, @return and @example
- Parameter typing: Always explicit, including return types
- Type hints: Use PHP 8 attributes like `#[SensitiveParameter]` where appropriate
- Null handling: Explicit checks, optional parameters default to empty string
- Documentation: 100% method coverage with examples
- Standards: PSR guidelines with Laravel Pint (preset "per")
- Testing: PHPUnit with complete coverage
