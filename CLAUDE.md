# StringManipulation Development Guide

## Critical Development Rules
- **ALWAYS run all tests before committing**: `docker-compose run --rm test-all`
- **NEVER force push or use --force with git commands**
- **NEVER ignore test failures or errors**
- **ALWAYS use conventional commit messages** (feat:, fix:, chore:, etc.)
- **NEVER overwrite or amend existing commit messages**

## Build & Testing Commands

### Docker (Recommended - PHP 8.3 with AST extension)
**IMPORTANT**: Always use Docker for testing to ensure consistent environment with PHP 8.3 and AST extension.

- Run all tests: `docker-compose run --rm test-all`
- Run PHPUnit tests: `docker-compose run --rm test-phpunit`
- Run single test: `docker-compose run --rm app ./vendor/bin/phpunit --filter testClassName`
- Code style check: `docker-compose run --rm test-code-style`
- Static analysis:
  - PHPStan: `docker-compose run --rm test-phpstan`
  - Psalm: `docker-compose run --rm test-psalm`
  - Phan: `docker-compose run --rm test-phan`
- PHP Mess Detector: `docker-compose run --rm test-phpmd`
- Mutation testing: `docker-compose run --rm test-infection`
- Code refactoring: `docker-compose run --rm test-rector`
- Linting: `docker-compose run --rm test-lint`
- Security check: `docker-compose run --rm test-security`

### Local (Requires PHP 8.3+ with AST extension)
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
