#!/usr/bin/env bash
# ═══════════════════════════════════════════════════════════
# First-run bootstrap: install WP core via WP-CLI, aktifkan
# WooCommerce, set permalinks + currency IDR, hardening dasar.
# Idempotent — aman dijalankan ulang.
#
# Prasyarat: docker compose up -d sudah jalan (db healthy).
#
# NOTE: wp-cli dijalankan via `php -d open_basedir=""` karena
# phar /usr/local/bin/wp berada di luar open_basedir web.
# Hardening open_basedir tetap aktif untuk proses FPM (web).
# ═══════════════════════════════════════════════════════════
set -euo pipefail

cd "$(dirname "$0")/.."
set -a; source .env; set +a

WP_URL="${WP_SITEURL:-https://shop-dev.rasakopi.coffee}"
WEB=shop-woo-web
DB=shop-woo-db
DBPASS="$(grep '^WORDPRESS_DB_PASSWORD=' .env | cut -d= -f2)"
# wrapper: wp-cli dengan open_basedir dilepas (CLI saja, bukan FPM)
WP() { docker exec "$WEB" php -d open_basedir= -d disable_functions= /usr/local/bin/wp "$@" --allow-root; }

echo "▸ Menunggu DB healthy..."
for i in $(seq 1 60); do
  if docker exec $DB healthcheck.sh --connect --innodb_initialized >/dev/null 2>&1; then
    break
  fi
  [ "$i" = 60 ] && { echo "✗ DB tidak healthy dalam 120s"; exit 1; }
  sleep 2
done
echo "✓ DB ready."

echo "▸ Memastikan WP-CLI ada di container web..."
docker exec $WEB sh -c '
  if [ ! -s /usr/local/bin/wp ]; then
    curl -sSL -o /usr/local/bin/wp https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
    chmod +x /usr/local/bin/wp
  fi
  php -d open_basedir= /usr/local/bin/wp --allow-root --version
'

if WP core is-installed --quiet 2>/dev/null; then
  echo "✓ WP sudah terinstall, skip core install."
else
  echo "▸ Install WordPress core (latest 7.x, PHP 8.5)..."
  # NOTE: image wordpress:php8.5-fpm sudah pre-populate WP core di /var/www/html
  WP core install \
    --url="$WP_URL" \
    --title="${WP_SITE_TITLE:-Rasa Kopi — Shop Dev}" \
    --admin_user="$WP_ADMIN_USER" \
    --admin_password="$WP_ADMIN_PASSWORD" \
    --admin_email="$WP_ADMIN_EMAIL" \
    --skip-email
  echo "✓ WP core installed."
fi

echo "▸ Permalinks + timezone..."
WP rewrite structure '/%postname%/' --quiet

echo "▸ Install + aktifkan WooCommerce (latest)..."
WP plugin install woocommerce --activate --force
WP plugin activate woocommerce || true

echo "▸ Install Wordfence (security)..."
WP plugin install wordfence --activate || true

echo "▸ WooCommerce settings (IDR)..."
WP option update woocommerce_currency 'IDR'
WP option update woocommerce_currency_pos 'left'
WP option update woocommerce_thousand_sep ','
WP option update woocommerce_decimal_point '.'
WP option update woocommerce_decimal_sep '.'

echo "▸ Update siteurl/home..."
WP option update siteurl "$WP_URL"
WP option update home "$WP_URL"

echo "▸ Verifikasi akhir (cek tabel + options di DB)..."
docker exec $DB mariadb -u"$WORDPRESS_DB_USER" -p"$DBPASS" "$WORDPRESS_DB_NAME" -e \
  "SELECT option_name, LEFT(option_value,50) FROM wp_options WHERE option_name IN ('siteurl','home','blogname');"

echo
echo "═════════════════════════════════════════════════════════"
echo "  ✓ Bootstrap selesai."
echo "  WP:      $WP_URL"
echo "  Admin:   $WP_ADMIN_USER / (password dari .env)"
echo "  Woo:     active, currency IDR"
echo "═════════════════════════════════════════════════════════"
