export function stripeLinkMode(link) {
  try {
    const url = new URL(link);
    if (url.protocol !== 'https:' || url.username || url.password || url.port) return null;
    if (url.hostname === 'buy.stripe.com') {
      if (/^\/test_[\w]+\/?$/.test(url.pathname)) return 'test';
      if (/^\/[a-z0-9]+\/?$/i.test(url.pathname)) return 'live';
    }
    if (url.hostname === 'checkout.stripe.com') {
      const session = url.pathname.match(/^\/c\/pay\/cs_(test|live)_[\w]+\/?$/);
      return session?.[1] ?? null;
    }
  } catch {}
  return null;
}
