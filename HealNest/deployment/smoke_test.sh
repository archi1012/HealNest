#!/usr/bin/env bash
# Simple smoke test for HealNest
# Usage: ./smoke_test.sh https://example.com

URL=${1:-http://127.0.0.1:80}
set -euo pipefail

echo "Checking $URL"
status=$(curl -sS -o /dev/null -w "%{http_code}" "$URL" || true)
if [ "$status" != "200" ]; then
  echo "FAIL: HTTP $status"
  exit 1
fi
echo "OK: HTTP 200"

# Check that frontend build manifest exists
if curl -sSf "$URL/build/manifest.json" > /dev/null; then
  echo "Assets: OK"
else
  echo "Assets: missing or not served (check public/build)"
fi
