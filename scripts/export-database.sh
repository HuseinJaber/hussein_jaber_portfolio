#!/usr/bin/env bash
# Export local portfolio MySQL database for Hostinger import.
# Usage: ./scripts/export-database.sh [output.sql]

set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
OUTPUT="${1:-$ROOT/portfolio_export.sql}"

DB_HOST="${DB_HOST:-127.0.0.1}"
DB_PORT="${DB_PORT:-3306}"
DB_DATABASE="${DB_DATABASE:-portfolio}"
DB_USERNAME="${DB_USERNAME:-portfolio}"
DB_PASSWORD="${DB_PASSWORD:-portfolio_secret}"

echo "Exporting ${DB_DATABASE} to ${OUTPUT} ..."

mysqldump -u "$DB_USERNAME" -p"$DB_PASSWORD" -h "$DB_HOST" -P "$DB_PORT" "$DB_DATABASE" \
  --single-transaction --routines --triggers \
  > "$OUTPUT"

echo "Done. Import this file in Hostinger phpMyAdmin (do not commit to git)."
