#!/bin/bash
# =============================================================================
# Deploy script — Aria Music (aria-music.be)
# Usage: ./deploy.sh [local|production]
# =============================================================================

set -e

TARGET="${1:-local}"
PROJECT_DIR="/home/cedric/projects/aria-music"
SERVER_DIR="/var/www/aria-music"
REMOTE="production"

echo "============================================"
echo "  🎵 Deploy Aria Music — $TARGET"
echo "============================================"

cd "$PROJECT_DIR"

# 1. Pull latest code
echo ""
echo "📦 Pull latest code..."
if [ "$TARGET" = "production" ]; then
    git pull origin dev
else
    echo "(local dev — skipping git pull)"
fi

# 2. Install dependencies
echo ""
echo "📚 Install/update PHP dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader

# 3. NPM build (Vite)
echo ""
echo "🎨 Build assets..."
npm run build

# 4. Run migrations
echo ""
echo "🗄️  Run migrations..."
php artisan migrate --force

# 5. Seed admin user if needed
echo ""
echo "👤 Seed admin..."
php artisan db:seed --class=AdminUserSeeder --force 2>/dev/null || true

# 6. Clear caches
echo ""
echo "🧹 Clear caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear

# 7. Generate sitemap
echo ""
echo "🗺️  Generate sitemap..."
php artisan sitemap:generate 2>/dev/null || \
  curl -s "https://aria-music.be/sitemap.xml" -o /dev/null || \
  echo "(skip — requires route or service)"

# 8. Storage link
echo ""
echo "🔗 Storage link..."
php artisan storage:link 2>/dev/null || true

# 9. Restart queue/fpm if production
if [ "$TARGET" = "production" ]; then
    echo ""
    echo "🔄 Restart PHP-FPM..."
    sudo systemctl reload php8.2-fpm 2>/dev/null || \
    sudo systemctl reload php8.1-fpm 2>/dev/null || \
    sudo systemctl reload php-fpm 2>/dev/null || \
    echo "(manual fpm restart needed)"
fi

echo ""
echo "============================================"
echo "  ✅ Deploy complete!"
echo "============================================"
