import { appendFileSync } from 'node:fs';

const productId = 'prod_artSupremeCanvas';
const name = 'Art Supreme Canvas Artwork';
const key = process.env.STRIPE_SECRET_KEY;
if (!/^(?:rk|sk)_live_[a-z0-9_]+$/i.test(key || '')) {
  throw new Error('A live Stripe key is required.');
}

const endpoint = 'https://api.stripe.com/v1/products';
const headers = { Authorization: `Bearer ${key}` };
let response = await fetch(endpoint, {
  method: 'POST',
  headers: { ...headers, 'Idempotency-Key': 'art-supreme-canvas-product-v1' },
  body: new URLSearchParams({ id: productId, name, shippable: 'true', tax_code: 'txcd_99999999' }),
  signal: AbortSignal.timeout(10000),
});
let product = await response.json();
if (!response.ok && product.error?.code === 'resource_already_exists') {
  response = await fetch(`${endpoint}/${productId}`, { headers, signal: AbortSignal.timeout(10000) });
  product = await response.json();
}
if (!response.ok) {
  throw new Error(`Stripe product setup failed (HTTP ${response.status}, ${product.error?.code || product.error?.type || 'request_failed'}). Check that the key has Products write access.`);
}
if (product.id !== productId || product.name !== name || product.livemode !== true || product.active !== true) {
  throw new Error('Stripe did not return the expected active live product.');
}
if (process.env.GITHUB_OUTPUT) appendFileSync(process.env.GITHUB_OUTPUT, `product_id=${productId}\n`);
console.log(`Shared Stripe product verified: ${productId}`);
