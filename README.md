# Art Supreme – Static Site

Static HTML/CSS site. No database; all product data lives inside **`site/products/`**.

## What you need: only `site/`

- **`site/`** is the whole site. Deploy this folder.
- **`site/products/<slug>/`** – One folder per product. Each folder contains:
  - **Images** (e.g. `01.jpg`, `02.jpg`, …) – shown as the product gallery.
  - **`info.json`** (optional) – title, excerpt, content, price, Stripe link. If missing, the folder name is used as the title.

So the only products folder is **`site/products/`**.

## Build (when you change something)

From the **project root** (the folder that contains `site/` and `scripts/`):

```bash
node scripts/build.mjs
```

This reads **`site/products/`** (each subfolder’s images + `info.json`) and regenerates:

- `site/index.html` (gallery)
- `site/product/<slug>.html` (each product page)
- `site/css/style.css`
- `site/shipping.html`, `site/contact.html`

Images stay where they are in `site/products/<slug>/`; the build does not copy them.

## Adding a product

1. Create a folder: **`site/products/my-new-work/`**
2. Put one or more images in it (e.g. `01.jpg`, `02.jpg`).
3. Optionally add **`site/products/my-new-work/info.json`**:
   ```json
   {
     "title": "My New Work",
     "excerpt": "Short description.",
     "content": "Longer description.",
     "price": 200,
     "stripePaymentLinkUrl": "https://buy.stripe.com/..."
   }
   ```
   If you omit `info.json`, the build uses the folder name as the title (e.g. “my new work”).
4. Run **`node scripts/build.mjs`** and deploy **`site/`**.

## Stripe

Each product’s **`info.json`** can include **`stripePaymentLinkUrl`**. If set, the product page shows a “Buy” button that goes to that Stripe Payment Link.

**Option A – automatic in CI (recommended)**  
1. In the repo: **Settings → Secrets and variables → Actions**, add a secret **`STRIPE_SECRET_KEY`** (your Stripe secret key, e.g. `sk_test_...` or `sk_live_...`).  
2. For new products, add a folder under **`site/products/<slug>/`** with images and **`info.json`** containing at least **`title`** and **`price`**. Leave **`stripePaymentLinkUrl`** empty or omit it.  
3. Push to **`main`**. The deploy workflow will create Stripe Payment Links for any product that has a price but no link, write the URLs into **`info.json`**, commit and push those changes, then build and deploy. So you only add the product folder and `info.json`; the pipeline fills in the Stripe link.

**Option B – manual**  
Run locally (with [Stripe secret key](https://dashboard.stripe.com/apikeys) in the env):  
`STRIPE_SECRET_KEY=sk_... node scripts/stripe-create-links.mjs`  
Then commit the updated **`info.json`** files and push. Or create Payment Links in the [Stripe Dashboard](https://dashboard.stripe.com/payment-links) and paste each URL into the product’s **`info.json`** as **`stripePaymentLinkUrl`**.

## Other folders

- **`scripts/`** – Contains **`build.mjs`** only. Run it when you add or edit products.

## GitHub Pages

A GitHub Action (`.github/workflows/deploy-pages.yml`) builds and deploys the site on every push to `main`.

1. Push this repo to GitHub.
2. In the repo: **Settings → Pages → Build and deployment → Source** = **GitHub Actions**.
3. On the next push to `main`, the workflow runs: it runs `node scripts/build.mjs` and deploys the `site/` folder to GitHub Pages.

If your default branch is not `main`, edit the workflow file and change `branches: [main]` to your branch name.

## Summary

| What | Where |
|------|--------|
| Only products folder you need | **`site/products/`** |
| Product data (title, price, etc.) | **`site/products/<slug>/info.json`** (optional) |
| Build command | **`node scripts/build.mjs`** (from project root) |
| Deploy | Push to GitHub (Action deploys `site/` to Pages), or upload **`site/`** anywhere |
