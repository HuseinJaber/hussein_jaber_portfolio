#!/usr/bin/env bash
# Build admin assets locally and package for Hostinger upload (no npm on shared hosting).
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
BACKEND="$ROOT/backend"
OUT="$ROOT/backend-build-upload.zip"

cd "$BACKEND"
npm run build
cd public
zip -r "$OUT" build
echo "Upload $OUT to Hostinger, then in SSH:"
echo "  cd ~/hussein_jaber_portfolio/backend/public && unzip -o ~/backend-build-upload.zip"
