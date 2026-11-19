const stripeSessions = 'https://api.stripe.com/v1/checkout/sessions';
export const checkoutPath = '/api/checkout';
const contact = 'mailto:info@art-supreme.com';

function checkoutMessage(status, message, returnPath = '/gallery.html', headers = {}) {
  return new Response(`<!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Checkout | Art Supreme</title><link rel="stylesheet" href="/css/style.css"></head>
<body><main><section class="info-layout"><div><h1>Art Supreme checkout</h1><p>${message}</p>
<p><a class="action" href="${returnPath}">Back to the gallery or artwork</a></p>
<p><a href="${contact}">Contact Art Supreme</a></p></div></section></main></body></html>`, {
    status,
    headers: { 'Content-Type': 'text/html; charset=utf-8', 'Cache-Control': 'no-store', ...headers },
  });
}

export async function startCheckout(request, env, catalog) {
  const url = new URL(request.url);
  if (request.method !== 'POST') {
    return checkoutMessage(405, 'Open an artwork page to start checkout.', undefined, { Allow: 'POST' });
  }
  const origin = request.headers.get('origin');
  if ((origin && origin !== url.origin) || request.headers.get('sec-fetch-site') === 'cross-site') {
    return checkoutMessage(403, 'Open checkout from the Art Supreme gallery.');
  }

  let slug;
  try {
    slug = decodeURIComponent(url.pathname.slice(checkoutPath.length + 1));
  } catch {
    return checkoutMessage(404, 'This artwork is no longer listed.');
  }
  if (!Object.hasOwn(catalog, slug)) return checkoutMessage(404, 'This artwork is no longer listed.');
  const artwork = catalog[slug];
  const productPage = `/product/${encodeURIComponent(slug)}.html`;
  if (artwork.sold || artwork.price <= 0) {
    return checkoutMessage(409, 'This artwork is currently unavailable for online purchase.', productPage);
  }

  const isPublicStore = ['art-supreme.com', 'www.art-supreme.com'].includes(url.hostname);
  const testMode = !isPublicStore && env.STRIPE_CHECKOUT_MODE === 'test';
  const keyMode = /^(?:sk|rk)_(live|test)_/.exec(env.STRIPE_CHECKOUT_SECRET_KEY || '')?.[1];
  const productId = env.STRIPE_SHARED_PRODUCT_ID;
  if (keyMode !== (testMode ? 'test' : 'live') || !/^prod_[a-z0-9]+$/i.test(productId || '')) {
    return checkoutMessage(503, 'Online checkout is unavailable. Please contact us about this work.', productPage);
  }

  const currency = artwork.currency;
  const { maximumFractionDigits } = new Intl.NumberFormat('en', { style: 'currency', currency }).resolvedOptions();
  const decimals = ['ISK', 'UGX'].includes(currency) ? 2 : maximumFractionDigits;
  const scaledAmount = artwork.price * (10 ** decimals);
  const amount = Math.round(scaledAmount);
  if (!Number.isSafeInteger(amount) || amount <= 0 || Math.abs(scaledAmount - amount) > 1e-7 ||
      (['ISK', 'UGX'].includes(currency) && amount % 100 !== 0)) {
    return checkoutMessage(503, 'Please contact us to confirm the price for this artwork.', productPage);
  }

  const params = new URLSearchParams({
    mode: 'payment',
    client_reference_id: slug,
    'custom_text[submit][message]': `Artwork – ${artwork.title}`,
    'line_items[0][price_data][product]': productId,
    'line_items[0][price_data][currency]': currency.toLowerCase(),
    'line_items[0][price_data][unit_amount]': String(amount),
    'line_items[0][quantity]': '1',
    success_url: `${url.origin}/success.html`,
    cancel_url: `${url.origin}${productPage}`,
    expires_at: String(Math.floor(Date.now() / 1000) + 31 * 60),
  });

  try {
    const response = await fetch(stripeSessions, {
      method: 'POST',
      headers: {
        Authorization: `Bearer ${env.STRIPE_CHECKOUT_SECRET_KEY}`,
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: params,
      signal: AbortSignal.timeout(10000),
    });
    const session = await response.json();
    if (!response.ok) {
      console.error('Stripe checkout request failed', response.status, session.error?.code || session.error?.type);
    } else if (session.livemode === !testMode && typeof session.id === 'string' &&
        session.id.startsWith(`cs_${keyMode}_`) && typeof session.url === 'string') {
      const checkoutUrl = new URL(session.url);
      // Stripe may return the account's custom Checkout domain.
      if (checkoutUrl.protocol === 'https:' && !checkoutUrl.username && !checkoutUrl.password) {
        return new Response(null, { status: 303, headers: { Location: checkoutUrl.href, 'Cache-Control': 'no-store' } });
      }
      console.error('Stripe returned a non-HTTPS checkout URL.');
    } else {
      console.error('Stripe returned an unexpected Checkout Session:', {
        livemode: session.livemode,
        hasSessionId: typeof session.id === 'string',
        hasCheckoutUrl: typeof session.url === 'string',
      });
    }
  } catch {
    console.error('Stripe checkout request could not be completed.');
  }
  return checkoutMessage(502, 'Checkout could not open. Please return to the artwork and try again, or contact us.', productPage);
}
