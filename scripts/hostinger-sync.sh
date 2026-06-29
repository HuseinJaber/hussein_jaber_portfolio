#!/usr/bin/env bash
# Sync git repo → Hostinger public_html layout (run on server via SSH).
#
# Target layout:
#   ~/domains/huseinjaber.com/public_html/
#     frontend/   ← Vite dist (huseinjaber.com document root)
#     backend/    ← Laravel app (api.huseinjaber.com → backend/public)
#
# Usage (SSH on Hostinger):
#   chmod +x ~/hussein_jaber_portfolio/scripts/hostinger-sync.sh
#   ~/hussein_jaber_portfolio/scripts/hostinger-sync.sh

set -euo pipefail

REPO="${REPO:-$HOME/hussein_jaber_portfolio}"
WEB="${WEB:-$HOME/domains/huseinjaber.com/public_html}"

if [[ ! -d "$REPO/.git" ]]; then
  echo "Repo not found at $REPO — clone first or set REPO=..." >&2
  exit 1
fi

cd "$REPO"
git pull origin main

mkdir -p "$WEB/frontend" "$WEB/backend"

echo "→ Syncing frontend dist → $WEB/frontend/"
rsync -a --delete "$REPO/frontend/dist/" "$WEB/frontend/"

echo "→ Syncing backend → $WEB/backend/"
rsync -a \
  --exclude '.env' \
  --exclude 'node_modules/' \
  --exclude '.git/' \
  "$REPO/backend/" "$WEB/backend/"

# Preserve production .env if it already exists on the server
if [[ -f "$WEB/backend/.env" ]]; then
  echo "→ Keeping existing $WEB/backend/.env"
elif [[ -f "$REPO/backend/.env" ]]; then
  cp "$REPO/backend/.env" "$WEB/backend/.env"
fi

cd "$WEB/backend"

if [[ ! -L public/storage ]]; then
  ln -sfn ../storage/app/public public/storage
fi

chmod -R 775 storage bootstrap/cache 2>/dev/null || true
rm -f public/hot

php artisan route:clear
php artisan view:clear
php artisan config:clear
php artisan config:cache

echo ""
echo "Done. Document roots in hPanel should be:"
echo "  huseinjaber.com     → $WEB/frontend"
echo "  api.huseinjaber.com → $WEB/backend/public"
