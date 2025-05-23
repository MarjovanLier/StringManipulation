#!/usr/bin/env bash

# Docker wrapper for commitlint
# This script runs commitlint inside a Docker container when npm/npx is not available locally

set -e

# Get the commit message file path
COMMIT_MSG_FILE="$1"

if [ -z "$COMMIT_MSG_FILE" ]; then
    echo "Error: No commit message file provided"
    exit 1
fi

# Get the absolute path of the commit message file
COMMIT_MSG_FILE_ABS=$(realpath "$COMMIT_MSG_FILE")

# Run commitlint in Docker
docker run --rm \
    -v "$(pwd)":/app \
    -v "$COMMIT_MSG_FILE_ABS":/tmp/commit-msg \
    -w /app \
    --entrypoint npx \
    stringmanipulation-tests:latest \
    --no -- commitlint --edit /tmp/commit-msg

exit $?
