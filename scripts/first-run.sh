#!/usr/bin/env bash
# ═══════════════════════════════════════════════════════════
# First-run bootstrap: install WP core via WP-CLI, aktifkan
# WooCommerce, set permalinks + currency IDR, hardening dasar.
# Idempotent — aman dijalankan ulang.
#
# Prasyarat: docker compose up -d sudah jalan (db healthy).
# ═══════════════════════════════════════════════════════════
set -euo pipefail

cd "$(dirname "$0")/.."
set -a; source .env; set +a

WP_URL="${WP_SITEURL:-https://shop-dev.rasakopi.coffee}"
WEB=shop-woo-web
DB=shop-woo-db

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
  if ! command -v wp >/dev/null 2>&1; then
    curl -sSL -o /usr/local/bin/wp https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
    chmod +x /usr/local/bin/wp
  fi
'

if docker exec $WEB wp core is-installed --allow-root --quiet 2>/dev/null; then
  echo "✓ WP sudah terinstall, skip core install."
else
  echo "▸ Install WordPress core (latest 7.x, PHP 8.5)..."
  docker exec $WEB wp core download --allow-root
  docker exec $WEB wp core install \
    --url="$WP_URL" \
    --title="${WP_SITE_TITLE:-Rasa Kopi — Shop Dev}" \
    --admin_user="$WP_ADMIN_USER" \
    --admin_password="$WP_ADMIN_PASSWORD" \
    --admin_email="$WP_ADMIN_EMAIL" \
    --skip-email --allow-root
  echo "✓ WP core installed."
fi

echo "▸ Permalinks + timezone + currency..."
docker exec $WEB wp rewrite structure '/%postname%/' --allow-root --quiet || true
docker exec $WEB wp option update timezone_string 'Asia/Jakarta' --allow-root
docker exec $WEB wp option update woocommerce_currency 'IDR' --allow-root || true

echo "▸ Install + aktifkan WooCommerce (latest)..."
docker exec $WEB wp plugin install woocommerce --activate --force --allow-root
docker exec $WEB wp plugin activate woocommerce --allow-root || true

echo "▸ Install Wordfence (security)..."
docker exec $WEB wp plugin install wordfence --activate --allow-root || true

echo "▸ Update siteurl/home..."
docker exec $WEB wp option update siteurl "$WP_URL" --allow-root
docker exec $WEB wp option update home "$WP_URL" --allow-root

echo
echo "═════════════════════════════════════════════════════════"
echo "  ✓ Bootstrap selesai."
echo "  WP:      $WP_URL"
echo "  Admin:   $WP_ADMIN_USER / (password dari .env)"
echo "  Woo:     active, currency IDR"
echo "═════════════════════════════════════════════════════════"
