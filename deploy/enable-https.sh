#!/usr/bin/env bash
set -euo pipefail

site=/etc/nginx/sites-available/art-supreme.com
previous=$(mktemp)
trap 'rm -f "$previous"' EXIT
cp "$site" "$previous"
certbot certonly --webroot -w /var/www/html --cert-name art-supreme.com \
  -d art-supreme.com -d www.art-supreme.com --non-interactive
install -m 0644 /etc/art-supreme/nginx-https.conf "$site"
if nginx -t && systemctl reload nginx; then
  exit 0
fi
install -m 0644 "$previous" "$site"
nginx -t
systemctl reload nginx
exit 1
