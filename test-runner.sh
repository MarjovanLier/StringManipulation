#!/usr/bin/env bash

# Test runner script for StringManipulation project
# This script runs tests in Docker containers to ensure consistent environment

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    local status=$1
    local message=$2
    case $status in
        "success")
            echo -e "${GREEN}✓${NC} $message"
            ;;
        "error")
            echo -e "${RED}✗${NC} $message"
            ;;
        "info")
            echo -e "${YELLOW}→${NC} $message"
            ;;
    esac
}

# Function to run a test
run_test() {
    local test_name=$1
    local service=$2

    print_status "info" "Running $test_name..."
    if docker-compose run --rm "$service" > /dev/null 2>&1; then
        print_status "success" "$test_name passed"
        return 0
    else
        print_status "error" "$test_name failed"
        return 1
    fi
}

# Main script
main() {
    local command=${1:-"all"}

    case $command in
        "all")
            print_status "info" "Building Docker image..."
            docker-compose build tests

            print_status "info" "Running all tests..."

            # Track failures
            local failed=0

            # Run each test
            # Special handling for composer validation
            print_status "info" "Running Composer Validation..."
            if docker-compose run --rm tests composer validate --strict > /dev/null 2>&1; then
                print_status "success" "Composer Validation passed"
            else
                print_status "error" "Composer Validation failed"
                ((failed++))
            fi
            run_test "PHP Lint" "test-lint" || ((failed++))
            run_test "Code Style (Pint)" "test-code-style" || ((failed++))
            run_test "PHPStan" "test-phpstan" || ((failed++))
            run_test "Psalm" "test-psalm" || ((failed++))
            run_test "PHPUnit" "test-phpunit" || ((failed++))
            run_test "PHPMD" "test-phpmd" || ((failed++))
            run_test "Rector" "test-rector" || ((failed++))
            run_test "Security Check" "test-security" || ((failed++))

            # Try to run optional tests
            print_status "info" "Running optional tests (may fail)..."
            run_test "Phan" "test-phan" || print_status "info" "Phan failed (optional)"
            run_test "Infection" "test-infection" || print_status "info" "Infection failed (optional)"

            if [ $failed -eq 0 ]; then
                print_status "success" "All required tests passed!"
                exit 0
            else
                print_status "error" "$failed required tests failed"
                exit 1
            fi
            ;;
        "quick")
            print_status "info" "Running quick tests..."
            docker-compose run --rm tests bash -c "
                composer validate --strict &&
                composer test:lint &&
                composer test:code-style &&
                composer test:phpstan &&
                composer test:phpunit
            "
            ;;
        "style")
            docker-compose run --rm test-code-style
            ;;
        "phpunit")
            docker-compose run --rm test-phpunit
            ;;
        "phpstan")
            docker-compose run --rm test-phpstan
            ;;
        "psalm")
            docker-compose run --rm test-psalm
            ;;
        "build")
            print_status "info" "Building Docker image..."
            docker-compose build tests
            ;;
        "shell")
            print_status "info" "Starting shell in test container..."
            docker-compose run --rm tests bash
            ;;
        *)
            echo "Usage: $0 [all|quick|style|phpunit|phpstan|psalm|build|shell]"
            echo ""
            echo "Commands:"
            echo "  all      - Run all tests (default)"
            echo "  quick    - Run quick essential tests"
            echo "  style    - Run code style checks"
            echo "  phpunit  - Run PHPUnit tests"
            echo "  phpstan  - Run PHPStan analysis"
            echo "  psalm    - Run Psalm analysis"
            echo "  build    - Build Docker image"
            echo "  shell    - Open shell in test container"
            exit 1
            ;;
    esac
}

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    print_status "error" "Docker is not running. Please start Docker and try again."
    exit 1
fi

# Check if docker-compose is available
if ! command -v docker-compose &> /dev/null; then
    print_status "error" "docker-compose is not installed. Please install it and try again."
    exit 1
fi

main "$@"
