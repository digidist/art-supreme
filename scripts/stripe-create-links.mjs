#!/usr/bin/env node
/**
 * Create Stripe Payment Links for each product that has a price and write
 * stripePaymentLinkUrl into site/products/<slug>/info.json.
 *
 * Requires STRIPE_SECRET_KEY in the environment (e.g. sk_test_... or sk_live_...).
 *
 * Run locally:
 *   STRIPE_SECRET_KEY=sk_... node scripts/stripe-create-links.mjs
 *   STRIPE_SUCCESS_URL=...  (optional) STRIPE_CURRENCY=eur (optional, default eur)
 * Per-product: in info.json set "currency": "usd" (or eur, gbp, etc.) to override.
 *
 * Options:
 *   --force   Recreate links even if stripePaymentLinkUrl is already set.
 *   --dry-run Print what would be done without calling Stripe or writing files.
 *
 * To run in CI (e.g. before build): add STRIPE_SECRET_KEY as a repo secret, then in your
 * deploy workflow run this script before scripts/build.mjs so the built site gets fresh links.
 */

import fs from 'fs';
import path from 'path';

const ROOT = process.cwd();
const SITE_PRODUCTS_DIR = path.join(ROOT, 'site', 'products');

const args = process.argv.slice(2);
const force = args.includes('--force');
const dryRun = args.includes('--dry-run');

function getProducts() {
  if (!fs.existsSync(SITE_PRODUCTS_DIR)) return [];
  const dirs = fs.readdirSync(SITE_PRODUCTS_DIR, { withFileTypes: true })
    .filter(d => d.isDirectory())
    .map(d => d.name);
  const products = [];
  for (const slug of dirs) {
    const infoPath = path.join(SITE_PRODUCTS_DIR, slug, 'info.json');
    if (!fs.existsSync(infoPath)) continue;
    let meta = { title: slug.replace(/-/g, ' '), price: 0, stripePaymentLinkUrl: '' };
    try {
      meta = { ...meta, ...JSON.parse(fs.readFileSync(infoPath, 'utf8')) };
    } catch (_) {}
    products.push({ slug, ...meta });
  }
  return products;
}

function getCurrency(product) {
  // Per-product info.json can set "currency" (e.g. "eur", "usd"); else env STRIPE_CURRENCY; else eur
  const c = (product.currency || process.env.STRIPE_CURRENCY || 'eur').toLowerCase();
  return c.length === 3 ? c : 'eur';
}

async function createPaymentLink(secretKey, product) {
  const priceCents = Math.round(Number(product.price) * 100);
  if (!priceCents || priceCents <= 0) return null;

  const currency = getCurrency(product);

  // Stripe API expects form-encoded body
  const params = new URLSearchParams();
  params.set('line_items[0][price_data][currency]', currency);
  params.set('line_items[0][price_data][unit_amount]', String(priceCents));
  params.set('line_items[0][price_data][product_data][name]', product.title || product.slug);
  params.set('line_items[0][quantity]', '1');
  const successBase = process.env.STRIPE_SUCCESS_URL || 'https://YOUR_USERNAME.github.io/Art-Supreme-Github/';
  params.set('after_completion[type]', 'redirect');
  params.set('after_completion[redirect][url]', successBase.replace(/\/?$/, '/'));

  const res = await fetch('https://api.stripe.com/v1/payment_links', {
    method: 'POST',
    headers: {
      Authorization: `Bearer ${secretKey}`,
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: params.toString(),
  });

  if (!res.ok) {
    const err = await res.text();
    throw new Error(`Stripe API ${res.status}: ${err}`);
  }
  const data = await res.json();
  return data.url || null;
}

async function main() {
  const secretKey = process.env.STRIPE_SECRET_KEY;
  if (!secretKey && !dryRun) {
    console.log('STRIPE_SECRET_KEY not set; skipping Stripe link creation.');
    return;
  }
  if (!secretKey && dryRun) return;

  const products = getProducts().filter(p => p.price > 0);
  if (products.length === 0) {
    console.log('No products with price > 0 found in site/products/*/info.json');
    return;
  }

  console.log(`Found ${products.length} product(s) with price. Force=${force} DryRun=${dryRun}\n`);

  for (const product of products) {
    const infoPath = path.join(SITE_PRODUCTS_DIR, product.slug, 'info.json');
    const hasLink = !!product.stripePaymentLinkUrl;

    if (hasLink && !force) {
      console.log(`Skip ${product.slug} (already has link). Use --force to recreate.`);
      continue;
    }

    if (dryRun) {
      console.log(`[dry-run] Would create Payment Link for "${product.title}" (€${product.price}) -> ${infoPath}`);
      continue;
    }

    try {
      const url = await createPaymentLink(secretKey, product);
      if (!url) {
        console.warn(`No URL returned for ${product.slug}`);
        continue;
      }
      const existing = {};
      try {
        Object.assign(existing, JSON.parse(fs.readFileSync(infoPath, 'utf8')));
      } catch (_) {}
      existing.stripePaymentLinkUrl = url;
      fs.writeFileSync(infoPath, JSON.stringify(existing, null, 2) + '\n', 'utf8');
      console.log(`OK ${product.slug} -> ${url}`);
    } catch (e) {
      console.error(`${product.slug}: ${e.message}`);
    }
  }
}

main();
