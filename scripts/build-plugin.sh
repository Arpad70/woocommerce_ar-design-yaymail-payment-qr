#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
VERSION="$(tr -d '\r\n' < "$ROOT_DIR/VERSION")"
PLUGIN_SLUG="ar-design-yaymail-payment-qr"
BUILD_DIR="$ROOT_DIR/build"
STAGE_DIR="$BUILD_DIR/$PLUGIN_SLUG"
ZIP_PATH="$BUILD_DIR/$PLUGIN_SLUG-$VERSION.zip"

rm -rf "$STAGE_DIR" "$ZIP_PATH"
mkdir -p "$STAGE_DIR"

RSYNC_EXCLUDES=()
while IFS= read -r line; do
  [[ -z "$line" ]] && continue
  RSYNC_EXCLUDES+=(--exclude="$line")
done < "$ROOT_DIR/.distignore"

rsync -a "$ROOT_DIR/" "$STAGE_DIR/" "${RSYNC_EXCLUDES[@]}"
(
  cd "$BUILD_DIR"
  zip -r "$ZIP_PATH" "$PLUGIN_SLUG" >/dev/null
)

echo "Built $ZIP_PATH"
