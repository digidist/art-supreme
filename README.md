# Art Supreme

The public storefront uses the approved yellow-and-black **The Objection** design, the original handwritten Art Supreme signature, and the headline **What if AI art was actually good?**

## Run locally

The existing zsh shortcuts are:

```zsh
ar       # enter the repository
ar s     # rebuild and serve site/ at http://localhost:8020
```

Or run from the repository:

```sh
node scripts/build.mjs
python3 -m http.server 8020 --bind 127.0.0.1 --directory site
```

No package installation or framework is needed. Node.js 20 or newer builds the site.

## Source and generated pages

- `site/products/<slug>/info.json` is the product metadata source. Images in the same folder are the gallery source.
- `scripts/build.mjs` generates the landing page, gallery, 20 existing canonical product pages, contact page, shipping page, checkout return page, catalog data, sitemap, and robots file.
- `scripts/assets/site.css` and `scripts/assets/site.js` are the production layout and browser behavior sources. The build copies them into `site/css/style.css` and `site/js/site.js`.
- `scripts/assets/art-supreme-signature.png` is the original transparent signature logo. The build copies it into `site/assets/`.
- Deploy `site/`. Generated HTML, CSS, and JavaScript are committed so the site can also be served directly. Rebuild after changing source files or product metadata; edits to generated files will be overwritten.

Public routes:

| Page | Route |
| --- | --- |
| Landing | `/` |
| Full collection | `/gallery.html` |
| Product | `/product/<slug>.html` |
| Shipping & returns | `/shipping.html` |
| Contact | `/contact.html` |
| Checkout return | `/success.html` |

Existing `/product/<slug>.html` URLs are preserved. The catalog remains available without JavaScript. JavaScript adds title search, subject filters, price/name sorting, saved works, remembered gallery filters, product thumbnails, and image enlargement. Saved works stay in the current browser.

## Product metadata

Example:

```json
{
  "title": "My New Work",
  "price": 200,
  "currency": "eur",
  "excerpt": "90x60 cm. Frame included.",
  "content": "About this work.",
  "stripePaymentLinkUrl": ""
}
```

Add images to `site/products/my-new-work/`, save its `info.json`, then rebuild. Optional `dimensions`, `material`, `description`, and `sold` fields can supply explicit details. Otherwise the build uses the existing catalog notes where available, with unknown details shown as on request. `[SOLD]` in an existing title is respected. Subject groupings and short visual descriptions are curated in the build source.

Prices, images, existing descriptions, and payment links come from the product folders. The build never edits product metadata or creates payment links. It fails clearly for malformed JSON, invalid prices, or invalid Stripe URLs.

## Purchase and enquiry behavior

- An unsold work with a valid `stripePaymentLinkUrl` offers a Stripe purchase link.
- An unsold work without a payment link offers an email enquiry to `info@art-supreme.com`.
- A sold work offers a link back to the collection and never displays a purchase link.
- Enquiry links open the visitor’s email application. No email is sent automatically, and the site does not process payments itself.
- The checkout return page gives follow-up information; it does not independently verify payment.

The existing `scripts/stripe-create-links.mjs` and GitHub Actions workflow remain available. With `STRIPE_SECRET_KEY` configured in GitHub, the existing workflow creates missing Stripe links before building and deploying. Running `node scripts/build.mjs` locally does not call Stripe.

## Verify

```sh
node --test scripts/build.test.mjs
node scripts/build.mjs
```

Regression checks cover static product routes, catalog prices and dimensions, Stripe link preservation, sold-item behavior, email fallback, rebuild consistency, and rejected invalid metadata/payment URLs. Browser checks should exercise the landing-to-gallery-to-product journey at desktop and mobile sizes.

## Deployment

The existing GitHub Actions workflow builds and deploys `site/` to GitHub Pages when changes are pushed to `main`. The configured public domain is `https://art-supreme.com/`.

The local `site/actually-good/` and `site/directions/` folders are earlier design studies, separate from the public build source. They are not required for the storefront. Their paths are excluded from crawling in the generated robots file.
