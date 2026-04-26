#!/usr/bin/env sh
set -e


# Clear stale caches (safe)
php artisan view:clear
php artisan event:clear
php artisan route:clear
php artisan config:clear

# Now cache using RUNTIME env vars (SAFE)
php artisan optimize

# Start Nightwatch agent (prod only, non-blocking)
if  [ "$NIGHTWATCH_ENABLED" = "true" ]; then
  echo "Starting Nightwatch agent..."
  php artisan nightwatch:agent &
fi

# Hand off to the real command (php-fpm / server)
exec "$@"
