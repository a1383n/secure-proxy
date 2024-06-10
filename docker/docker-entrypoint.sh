#!/bin/bash

set -e

# Define the command based on the ROLE environment variable
case "$CONTAINER_ROLE" in
  "PHP-FPM")
    echo "Starting PHP-FPM..."
    php /var/www/html/artisan optimize
    exec php-fpm
    ;;
  "QUEUE")
    echo "Starting Queue Worker..."
    exec php /var/www/html/artisan horizon
    ;;
  "SCHEDULER")
    echo "Starting Scheduler..."
    exec php /var/www/html/artisan schedule:work
    ;;
  *)
    echo "Unknown role: $ROLE"
    exit 1
    ;;
esac
