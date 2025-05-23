# Contributing to StringManipulation

Thank you for your interest in contributing to StringManipulation! This document provides guidelines and instructions for contributing to the project.

## Development Setup

### Prerequisites

- Docker and Docker Compose
- Git
- PHP 8.3+ (optional, only for local development without Docker)

### Setting Up Pre-commit Hooks

This project uses pre-commit hooks to ensure code quality. All tests run in Docker containers to maintain consistency across different development environments.

#### Quick Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/MarjovanLier/StringManipulation.git
   cd StringManipulation
   ```

2. **Run the setup script**
   ```bash
   ./setup-hooks.sh
   ```

   This script will:
   - Check Docker is installed and running
   - Build the Docker test image
   - Install pre-commit framework
   - Configure git hooks
   - Run initial tests to verify setup

#### Manual Setup

If you prefer to set up manually:

1. **Install pre-commit**
   ```bash
   # Using pip
   pip install pre-commit
   
   # Or using Homebrew (macOS)
   brew install pre-commit
   ```

2. **Build Docker image**
   ```bash
   docker-compose build tests
   ```

3. **Install hooks**
   ```bash
   pre-commit install
   pre-commit install --hook-type commit-msg
   ```

### Running Tests

#### Using the Test Runner Script

The project includes a convenient test runner script:

```bash
# Run all tests
./test-runner.sh all

# Run quick essential tests
./test-runner.sh quick

# Run specific test suite
./test-runner.sh phpunit
./test-runner.sh phpstan
./test-runner.sh psalm
./test-runner.sh style

# Open shell in test container
./test-runner.sh shell
```

#### Using Docker Compose Directly

```bash
# Run all tests
docker-compose run --rm tests

# Run specific test
docker-compose run --rm test-phpunit
docker-compose run --rm test-phpstan
docker-compose run --rm test-code-style
```

#### Using Composer (in container)

```bash
# Run all tests
docker-compose run --rm tests composer tests

# Run specific test
docker-compose run --rm tests composer test:phpunit
docker-compose run --rm tests composer test:phpstan
```

### Available Test Commands

- `test:code-style` - Check code style (Laravel Pint)
- `test:composer-validate` - Validate composer.json
- `test:infection` - Run mutation testing
- `test:lint` - Check for syntax errors
- `test:phan` - Static analysis with Phan
- `test:phpmd` - PHP Mess Detector
- `test:phpstan` - PHPStan static analysis
- `test:phpunit` - Unit tests
- `test:psalm` - Psalm static analysis
- `test:rector` - Code quality checks
- `test:vulnerabilities-check` - Security vulnerability scan

### Pre-commit Hooks

The following checks run automatically before each commit:

1. **composer.json validation** - Ensures composer.json is valid
2. **PHP syntax check** - Verifies PHP files have no syntax errors
3. **Code style** - Enforces consistent code formatting
4. **Static analysis** - PHPStan and Psalm checks
5. **Unit tests** - Runs PHPUnit test suite
6. **Security check** - Scans for known vulnerabilities
7. **File formatting** - Trailing whitespace, EOF, line endings

### Commit Message Convention

This project follows the [Conventional Commits](https://www.conventionalcommits.org/) specification.

#### Format

```
<type>(<scope>): <subject>

<body>

<footer>
```

#### Types

- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation changes
- `style`: Code style changes (formatting, missing semicolons, etc.)
- `refactor`: Code refactoring without changing functionality
- `perf`: Performance improvements
- `test`: Adding or updating tests
- `build`: Build system or dependency changes
- `ci`: CI/CD configuration changes
- `chore`: Other changes that don't modify src or test files
- `revert`: Reverting a previous commit

#### Examples

```bash
feat(auth): add user authentication endpoint
fix(parser): resolve memory leak in tokenizer
docs: update installation instructions
feat!: add breaking change to API
```

All commits must include a `Signed-off-by` line, which is automatically added by the commit-msg hook.

### Skipping Hooks (Emergency Only)

If you need to bypass pre-commit hooks in an emergency:

```bash
git commit --no-verify
```

**Note:** This should only be used in exceptional circumstances. All commits should pass the pre-commit checks.

### Troubleshooting

#### Docker not running
```
ERROR: Docker is not running. Please start Docker and try again.
```
**Solution:** Start Docker Desktop or Docker daemon.

#### Permission denied
```
Permission denied while trying to connect to the Docker daemon socket
```
**Solution:** Add your user to the docker group or use sudo (not recommended).

#### Tests failing in container but not locally
This usually indicates environment differences. Always trust the container results as they match the CI environment.

#### Slow first run
The first run downloads Docker images and installs dependencies. Subsequent runs use cached layers and are much faster.

### Code Style Guidelines

- Follow PSR-12 coding standards
- Use strict typing: `declare(strict_types=1);`
- Document all public methods with comprehensive PHPDoc
- Include examples in documentation where appropriate
- Write self-documenting code with clear variable names

### Submitting Changes

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Make your changes
4. Ensure all tests pass (`./test-runner.sh all`)
5. Commit your changes using conventional commits
6. Push to your fork (`git push origin feature/amazing-feature`)
7. Open a Pull Request

### Getting Help

- Check existing issues and pull requests
- Read the project documentation
- Ask questions in issues (label as 'question')

Thank you for contributing to StringManipulation!