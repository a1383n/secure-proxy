#!/bin/bash

set -e

# Define the command based on the ROLE environment variable
case "$CONTAINER_ROLE" in
  "OCTANE")
    echo "Starting Laravel Octane..."
    php artisan optimize || true
    php artisan icons:cache || true
    php artisan filament:cache-components || true
    php artisan vendor:publish --tag=laravel-assets
    php artisan migrate --force

    exec php artisan octane:start
  ;;
  "QUEUE")
    echo "Starting Queue Worker..."
    exec php artisan horizon
    ;;
  "SCHEDULER")
    echo "Starting Scheduler..."
    exec php artisan schedule:work
    ;;
  *)
    echo "Unknown role: $ROLE"
    exit 1
    ;;
esac
