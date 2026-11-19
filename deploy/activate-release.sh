#!/usr/bin/env bash
set -euo pipefail

root=/srv/art-supreme
archive=$(mktemp)
release=$(mktemp -d "$root/releases/release.XXXXXXXX")
trap 'rm -f "$archive"' EXIT
cat > "$archive"
tar -xzf "$archive" -C "$release"
chmod 755 "$release"
chmod 600 "$release/runtime.env"
test -r "$release/site/index.html"
test -r "$release/scripts/.checkout-catalog.json"

previous=$(readlink "$root/current" || true)
point_current_at() {
  ln -sfn "$1" "$root/current.next"
  mv -Tf "$root/current.next" "$root/current"
}

verify_checkout() {
  if [[ -n "$previous" ]] && cmp -s "$previous/runtime.env" "$release/runtime.env"; then
    return 0
  fi
  if ! grep -q '^STRIPE_SHARED_PRODUCT_ID=prod_' "$release/runtime.env"; then
    return 0
  fi
  local slug status
  slug=$(node -e 'const works=require(process.argv[1]); const entry=Object.entries(works).find(([,work])=>!work.sold&&work.price>0); if(entry) process.stdout.write(encodeURIComponent(entry[0]));' "$release/scripts/.checkout-catalog.json")
  if [[ -n "$slug" ]]; then
    status=$(curl --silent --show-error --max-time 15 --output /dev/null --write-out '%{http_code}' -X POST "http://127.0.0.1:3009/api/checkout/$slug")
    if [[ "$status" != 303 ]]; then
      echo "Stripe checkout verification returned HTTP $status. Check the shared product ID and restricted-key permissions." >&2
      return 1
    fi
    echo "Stripe accepted the checkout configuration. No payment was submitted."
  fi
}

point_current_at "$release"
if sudo -n /usr/bin/systemctl restart art-supreme.service &&
   curl --fail --silent --show-error --retry 5 --retry-connrefused --retry-delay 1 http://127.0.0.1:3009/health &&
   verify_checkout; then
  find "$root/releases" -mindepth 1 -maxdepth 1 -type d -name 'release.*' ! -path "$release" ! -path "$previous" -exec rm -rf -- {} +
  echo "Art Supreme release activated."
  exit 0
fi

if [[ -n "$previous" ]]; then
  point_current_at "$previous"
  sudo -n /usr/bin/systemctl restart art-supreme.service
else
  rm -f "$root/current"
  sudo -n /usr/bin/systemctl stop art-supreme.service
fi
rm -rf -- "$release"
echo "Art Supreme release failed its health checks." >&2
exit 1
