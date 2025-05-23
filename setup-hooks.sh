#!/usr/bin/env bash

# Setup script for pre-commit hooks
# This script installs and configures pre-commit hooks for the project

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
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
        "header")
            echo -e "\n${BLUE}=== $message ===${NC}\n"
            ;;
    esac
}

# Check if pre-commit is installed
check_precommit() {
    if command -v pre-commit &> /dev/null; then
        return 0
    else
        return 1
    fi
}

# Install pre-commit
install_precommit() {
    print_status "header" "Installing pre-commit"

    # Try different installation methods
    if command -v pip3 &> /dev/null; then
        print_status "info" "Installing pre-commit using pip3..."
        pip3 install --user pre-commit
    elif command -v pip &> /dev/null; then
        print_status "info" "Installing pre-commit using pip..."
        pip install --user pre-commit
    elif command -v brew &> /dev/null; then
        print_status "info" "Installing pre-commit using Homebrew..."
        brew install pre-commit
    else
        print_status "error" "Could not find pip or brew. Please install pre-commit manually:"
        echo "  - Using pip: pip install pre-commit"
        echo "  - Using Homebrew: brew install pre-commit"
        echo "  - See: https://pre-commit.com/#install"
        exit 1
    fi
}

# Main setup function
main() {
    print_status "header" "StringManipulation Pre-commit Hooks Setup"

    # Check if Docker is running
    if ! docker info > /dev/null 2>&1; then
        print_status "error" "Docker is not running. Please start Docker and try again."
        print_status "info" "Pre-commit hooks require Docker to run tests in containers."
        exit 1
    fi

    # Check if docker-compose is available
    if ! command -v docker-compose &> /dev/null; then
        print_status "error" "docker-compose is not installed. Please install it and try again."
        exit 1
    fi

    # Install npm dependencies for commitlint
    if [ -f "package.json" ]; then
        print_status "info" "Installing npm dependencies for commitlint..."
        if command -v npm &> /dev/null; then
            if npm install > /dev/null 2>&1; then
                print_status "success" "npm dependencies installed successfully"
            else
                print_status "info" "Failed to install npm dependencies locally (will use Docker)"
            fi
        else
            print_status "info" "npm not found locally (will use Docker for commitlint)"
        fi
    fi

    # Build Docker image
    print_status "info" "Building Docker test image..."
    if docker-compose build tests > /dev/null 2>&1; then
        print_status "success" "Docker image built successfully"
    else
        print_status "error" "Failed to build Docker image"
        exit 1
    fi

    # Check and install pre-commit
    if ! check_precommit; then
        install_precommit

        # Verify installation
        if ! check_precommit; then
            print_status "error" "Failed to install pre-commit"
            exit 1
        fi
    else
        print_status "success" "pre-commit is already installed"
    fi

    # Install pre-commit hooks
    print_status "info" "Installing git hooks..."
    if pre-commit install; then
        print_status "success" "Git hooks installed successfully"
    else
        print_status "error" "Failed to install git hooks"
        exit 1
    fi

    # Install commit-msg hook for conventional commits
    print_status "info" "Installing commit-msg hook..."
    if pre-commit install --hook-type commit-msg; then
        print_status "success" "Commit-msg hook installed successfully"
    else
        print_status "info" "Commit-msg hook installation skipped"
    fi

    # Run pre-commit on all files to verify setup
    print_status "info" "Verifying setup by running pre-commit on all files..."
    if pre-commit run --all-files; then
        print_status "success" "All pre-commit checks passed!"
    else
        print_status "info" "Some checks failed. This is normal for initial setup."
        print_status "info" "Run './test-runner.sh all' to see detailed test results."
    fi

    print_status "header" "Setup Complete!"
    echo ""
    echo "Pre-commit hooks are now installed and will run automatically before each commit."
    echo ""
    echo "Available commands:"
    echo "  - Run all tests manually:     ./test-runner.sh all"
    echo "  - Run quick tests:           ./test-runner.sh quick"
    echo "  - Run specific test:         ./test-runner.sh [style|phpunit|phpstan|psalm]"
    echo "  - Skip hooks (emergency):    git commit --no-verify"
    echo "  - Update hooks:             pre-commit autoupdate"
    echo ""
    print_status "info" "Note: First run may be slow as Docker downloads images."
}

# Run main function
main "$@"
