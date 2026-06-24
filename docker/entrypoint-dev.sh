#!/bin/sh
set -e

cd /var/www/html

# Align the Apache user (www-data) with the owner of the bind-mounted project.
# This keeps files written to private/temp and private/log readable/writable
# from both the container and the host, avoiding permission clashes.
HOST_UID=$(stat -c %u /var/www/html)
HOST_GID=$(stat -c %g /var/www/html)
if [ "$HOST_UID" != "0" ]; then
	groupmod -o -g "$HOST_GID" www-data 2>/dev/null || true
	usermod -o -u "$HOST_UID" www-data 2>/dev/null || true
fi

# Install PHP dependencies on first run (private/vendor is bind-mounted)
if [ ! -f private/vendor/autoload.php ]; then
	echo "[entrypoint] Installing Composer dependencies..."
	composer install --no-interaction --no-progress
fi

# Apache (www-data) must be able to write caches and logs
mkdir -p private/temp private/log
chown -R www-data:www-data private/temp private/log

exec "$@"
