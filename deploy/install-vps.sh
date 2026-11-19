#!/usr/bin/env bash
set -euo pipefail

root=/srv/art-supreme
here=$(cd "$(dirname "$0")" && pwd)
nginx_site=/etc/nginx/sites-available/art-supreme.com

if ! id art-supreme >/dev/null 2>&1; then
  useradd --system --user-group --home-dir "$root" --shell /bin/bash art-supreme
fi
install -d -m 0755 -o art-supreme -g art-supreme "$root" "$root/releases"
install -d -m 0700 -o art-supreme -g art-supreme "$root/.ssh"
install -d -m 0755 /var/www/html/.well-known/acme-challenge
install -m 0644 "$here/art-supreme.service" /etc/systemd/system/art-supreme.service
printf '%s\n' 'art-supreme ALL=(root) NOPASSWD: /usr/bin/systemctl restart art-supreme.service, /usr/bin/systemctl stop art-supreme.service' > /etc/sudoers.d/art-supreme
chmod 0440 /etc/sudoers.d/art-supreme
visudo -c -f /etc/sudoers.d/art-supreme
systemctl daemon-reload
systemctl enable art-supreme.service

install -d -m 0755 /etc/art-supreme
install -m 0644 "$here/nginx-https.conf" /etc/art-supreme/nginx-https.conf
install -m 0755 "$here/enable-https.sh" /usr/local/sbin/art-supreme-enable-https
install -d -m 0755 /etc/letsencrypt/renewal-hooks/deploy
install -m 0755 "$here/reload-after-renewal.sh" /etc/letsencrypt/renewal-hooks/deploy/90-art-supreme-nginx
if [[ ! -f "$nginx_site" ]]; then
  install -m 0644 "$here/nginx-http.conf" "$nginx_site"
  ln -sfn "$nginx_site" /etc/nginx/sites-enabled/art-supreme.com
  if ! nginx -t; then
    rm -f /etc/nginx/sites-enabled/art-supreme.com
    exit 1
  fi
  systemctl reload nginx
fi
