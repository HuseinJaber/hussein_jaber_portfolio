#!/usr/bin/env bash
# Production frontend build — run from repo root or frontend/.
set -euo pipefail

cd "$(dirname "$0")/../frontend"

export VITE_API_URL="${VITE_API_URL:-https://api.huseinjaber.com/api}"
export VITE_SITE_URL="${VITE_SITE_URL:-https://huseinjaber.com}"

echo "Building with VITE_API_URL=$VITE_API_URL"
npm run build

if rg -q 'localhost:8000' dist/assets/*.js 2>/dev/null; then
  echo "ERROR: dist still contains localhost:8000 — build failed to pick up env." >&2
  exit 1
fi

echo "OK — dist ready to commit or upload."
