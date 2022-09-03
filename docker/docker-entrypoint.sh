#!/bin/bash
set -e
composer install
#php artisan migrate --force

exec "$@"
