# digitalCommerce — Rasa Kopi WooCommerce (shop-dev)

Stack **WordPress + WooCommerce** untuk `shop-dev.rasakopi.coffee` (dev/staging).
Live Shopify (`shop.rasakopi.coffee`) tidak disentuh — migrasi dilakukan di domain dev ini dulu.

## Stack

| Komponen | Image                          | Catatan                                   |
|----------|--------------------------------|-------------------------------------------|
| WordPress| `wordpress:7.1.4-php8.5-fpm`   | WP 7.1.4 + **PHP 8.5** (latest stable)    |
| Nginx    | `nginx:alpine`                 | internal, trafik via `rasa-shared-proxy`  |
| DB       | `mariadb:12.3`                 | MariaDB 12.3 LTS, binlog retention 30 hari|

PHP 8.5 memakai image patched (family CVE RCE/upload termasuk pola
*php2shell* sudah di-patch upstream). Tambahan hardening di `php/override.ini`:
`disable_functions` (exec/system/eval/dll), `open_basedir`, session hardening,
resource limits. Nginx men-block PHP di `wp-content/uploads/` (anti webshell).

## Struktur

```
├── docker-compose.yml          # stack (network isolated: shop-woo-net)
├── .env.example                # template secrets → copy ke .env (git-ignored)
├── php/
│   ├── override.ini            # PHP 8.5 security hardening
│   └── db.cnf                  # MariaDB tuning (buffer pool 256M, binlog 30d)
├── conf/nginx/shop-woo.conf    # nginx internal (security headers, cache, anti-webshell)
├── scripts/first-run.sh        # bootstrap idempotent (WP-CLI: core, Woo, Wordfence)
├── theme/rasa-kopi/            # custom theme (port Shopify "Rasa" → Woo, markup Dawn)
└── .gitignore
```

## Deployment (RASA Dev — 165.22.79.139)

Path: `/var/www/RASA/wordpress-woo`

```bash
cd /var/www/RASA/wordpress-woo

# 1. Secrets (WAJIB ganti password di .env)
cp .env.example .env
nano .env

# 2. Start stack
docker compose up -d

# 3. Bootstrap: install WP + WooCommerce + Wordfence + hardening
./scripts/first-run.sh
```

Trafik masuk: `rasa-shared-proxy` (0.0.0.0:80/443) → `shop-woo-nginx:80`
(conf proxy: `/var/www/RASA/INFRA/conf.d/shop-dev.conf`).

## Limit resource (server ~2GB RAM, 11 container lain jalan)

| Container     | mem_limit |
|---------------|-----------|
| shop-woo-web  | 512m      |
| shop-woo-nginx| 128m      |
| shop-woo-db   | 512m      |

## Migrasi dari Shopify

1. Shopify Admin → Settings → Channels → Export → **All products** (CSV).
2. WP admin → WooCommerce → Products → **Import** → upload CSV.
3. Custom fields Shopify (metafields) → pakai plugin *Woo Importer* + column mapping,
   atau export via API Shopify (GraphQL) → mapping ke WooCommerce product meta.
4. Setelah verified di dev → cutover DNS `shop.rasakopi.coffee` ke RASA Dev.

## Catatan

- Network stack **isolated** (`shop-woo-net`) — tidak ikut `rasa-shared-net`,
  tidak mengganggu 11 container lain.
- DB binlog retention 30 hari, slow query log aktif untuk audit.
- SSL untuk `shop-dev.rasakopi.coffee` di-issues via ACME webroot
  (`/var/www/RASA/INFRA/certbot/www`) oleh proxy utama.


## Custom theme — rasa-kopi

Port theme Shopify **"Rasa"** (live: `shop.rasakopi.coffee`) ke WordPress/WooCommerce dengan markup pixel-faithful (konvensi class Dawn). Source of truth di repo: `theme/rasa-kopi/`.

Di server, theme aktif tinggal di Docker volume:
`/var/lib/docker/volumes/wordpress-woo_wp_content/_data/wp-content/themes/rasa-kopi`

### Deploy update theme (dari checkout repo)

```bash
# 1. pull repo
cd /var/www/RASA/wordpress-woo && git pull --ff-only origin main

# 2. sync ke volume (JANGAN docker cp — bikin root:root 600, web user tak bisa baca)
VOL=/var/lib/docker/volumes/wordpress-woo_wp_content/_data/wp-content/themes/rasa-kopi
rsync -a --delete theme/rasa-kopi/ $VOL/

# 3. fix ownership/permissions
docker exec shop-woo-web chown -R www-data:www-data $VOL
docker exec shop-woo-web find $VOL -type f -exec chmod 644 {} \;
docker exec shop-woo-web find $VOL -type d -exec chmod 755 {} \;

# 4. validasi
docker exec shop-woo-web php -l $VOL/functions.php
curl -s -o /dev/null -w "%{http_code}\n" https://shop-dev.rasakopi.coffee/
```

### Pitfall penting
- **`docker cp` = permission root:root 600** → PHP fatal/blank page. Selalu rsync + chown/chmod, atau `docker exec chown` setelah docker cp.
- **Cache busting**: bump `RASA_VERSION` di `theme/rasa-kopi/functions.php` kalau ubah CSS/JS, atau hard-reload browser.
- Header (logo 190x45, nav inline-flex, marquee 3 pesan uppercase, language selector `<localization-form>` driven `assets/js/localization-form.js`) sudah A/B verified vs live.
- TODO homepage: section "Neuheiten" & "Bald im Shop" masih dummy — belum query produk real di DB Woo. Single product page masih markup core Woo, belum port layout Dawn.
