#!/usr/bin/env bash
# One-time migration: move existing Hostinger deploy into public_html/{frontend,backend}.
# Run once on Hostinger SSH if you previously deployed to ~/hussein_jaber_portfolio only.
#
# Usage:
#   chmod +x ~/hussein_jaber_portfolio/scripts/hostinger-migrate-layout.sh
#   ~/hussein_jaber_portfolio/scripts/hostinger-migrate-layout.sh

set -euo pipefail

REPO="${REPO:-$HOME/hussein_jaber_portfolio}"
WEB="${WEB:-$HOME/domains/huseinjaber.com/public_html}"
OLD_API="${OLD_API:-$WEB/api}"

mkdir -p "$WEB/frontend" "$WEB/backend"

echo "→ Copying backend from $REPO/backend to $WEB/backend/"
rsync -a --exclude 'node_modules/' "$REPO/backend/" "$WEB/backend/"

echo "→ Copying frontend dist to $WEB/frontend/"
rsync -a --delete "$REPO/frontend/dist/" "$WEB/frontend/"

# If site files were dumped directly in public_html root, move them into frontend/
for item in index.html assets favicon.ico file.svg globe.svg next.svg vercel.svg window.svg; do
  if [[ -e "$WEB/$item" && ! -e "$WEB/frontend/$item" ]]; then
    mv "$WEB/$item" "$WEB/frontend/" 2>/dev/null || true
  fi
done
if [[ -f "$WEB/.htaccess" && ! -f "$WEB/frontend/.htaccess" ]]; then
  mv "$WEB/.htaccess" "$WEB/frontend/.htaccess"
fi

cd "$WEB/backend"
ln -sfn ../storage/app/public public/storage 2>/dev/null || true
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

echo ""
echo "Migration complete. Now in hPanel set document roots:"
echo "  huseinjaber.com     → $WEB/frontend"
echo "  api.huseinjaber.com → $WEB/backend/public"
echo ""
echo "Optional: remove old api folder if unused: $OLD_API"
echo "Then run: $REPO/scripts/hostinger-sync.sh for future deploys."
