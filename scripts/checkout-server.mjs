import { readFile } from 'node:fs/promises';
import { createServer } from 'node:http';
import { checkoutPath, startCheckout } from './checkout.mjs';

const env = process.env;
const port = Number(env.ART_SUPREME_PORT || 3009);
const origin = new URL(env.ART_SUPREME_ORIGIN || 'https://art-supreme.com').origin;
const manifest = env.ART_SUPREME_CHECKOUT_CATALOG || new URL('./.checkout-catalog.json', import.meta.url);

const server = createServer(async (incoming, outgoing) => {
  try {
    const url = new URL(incoming.url, origin);
    let response;
    if (url.origin !== origin) {
      response = new Response('Invalid request.', { status: 400 });
    } else if (url.pathname === '/health' || url.pathname === checkoutPath || url.pathname.startsWith(checkoutPath + '/')) {
      const catalog = JSON.parse(await readFile(manifest, 'utf8'));
      if (url.pathname === '/health') {
        response = new Response('ok\n');
      } else {
        const headers = new Headers();
        for (const name of ['origin', 'sec-fetch-site']) {
          const value = incoming.headers[name];
          if (typeof value === 'string') headers.set(name, value);
        }
        response = await startCheckout(new Request(url, { method: incoming.method, headers }), env, catalog);
      }
    } else {
      response = new Response('Not found.', { status: 404 });
    }
    outgoing.writeHead(response.status, Object.fromEntries(response.headers));
    outgoing.end(incoming.method === 'HEAD' ? undefined : Buffer.from(await response.arrayBuffer()));
  } catch (error) {
    console.error('Checkout server:', error.message);
    outgoing.writeHead(503, { 'Content-Type': 'text/plain; charset=utf-8', 'Cache-Control': 'no-store' });
    outgoing.end('Checkout is temporarily unavailable. Contact info@art-supreme.com about this work.');
  }
});

server.listen(port, '127.0.0.1');
