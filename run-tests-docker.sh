#!/bin/bash
# Run all tests in Docker container with xdebug enabled

echo "Running all tests in Docker container..."
docker compose run --rm tests
