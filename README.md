# Art Supreme

The storefront follows the approved **Arrive in your world** full-page design: a photographic room hero, a compact artwork/room/customize dock, a dark collection, the original origin story, and a second room invitation. It preserves the yellow-and-black identity, original handwritten signature, and headline **What if AI art was actually good?** The visual reference is `output/design-reference.png`; the later artwork callout reference is implemented as a frame-attached label and leader line.

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

No package installation or framework is needed. Node.js 20 or newer builds the site. Inter is served locally with its SIL Open Font License in `scripts/assets/inter-OFL.txt`; the site does not depend on a runtime CDN. Generated CSS and script URLs include a content version so updated mobile styles load after deployment.

## Source and generated pages

- `site/products/<slug>/info.json` is the product metadata source. Images in the same folder are the gallery source.
- `scripts/build.mjs` generates the landing page, gallery, 20 existing canonical product pages, contact page, shipping page, checkout return page, catalog data, sitemap, and robots file.
- `scripts/assets/site.css` and `scripts/assets/site.js` are the production layout and browser behavior sources. The build copies them into `site/css/style.css` and `site/js/site.js`.
- `scripts/assets/art-supreme-signature.png` is the original transparent signature logo. The build copies it into `site/assets/`.
- `scripts/assets/world.js` implements the photographic artwork placement and dock/state. `scripts/assets/rooms/` contains the generated empty-wall room photographs.
- `scripts/assets/artwork-crops.json` records the exact source rectangles for all 20 original artworks. `scripts/assets/artworks/` contains lossless WebP crops; original catalog files are untouched. The build validates that every referenced texture exists.
- `output/design-reference.png` is the approved generated mockup; `output/verification/` contains actual browser captures and the verification report.
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

## Photographic room previews

The user chose the original photographic direction and explicitly retired the Three.js room. The room photograph provides the lighting, furniture and atmosphere. Original lossless catalog artwork is placed separately in the empty wall space; it is never regenerated or color graded with the room.

The glass dock changes the artwork, room and frame preview independently. Larger selected thumbnails, readable titles and visual choice cards make artwork and room selection clear. The controls adapt to the preview container. On phones, a full-height room photograph opens the homepage, with the headline over a lower dark gradient. The photograph sits above the neutral charcoal selector dock, and choices expand below the dock without covering the image. Opening a menu reveals its choices; closing it returns to the previous preview position. The selected room and frame persist through canonical product navigation. Artwork-only product viewing and the original catalog-image zoom remain available. Portraits and landscapes fit within the wall space without distortion. Responsive placement is recalculated when the preview resizes. Frame highlights, contact shadows and cast shadows follow each room’s light direction. A photograph-aligned illumination layer carries the wall’s light and shadow across the artwork; the artwork-only view and original catalog images remain unshaded.

No 3D renderer, model, HDR, walking, orbit or WebGL dependency is loaded or copied into the production site. Earlier 3D experiments are retired; do not restore them without a new explicit request. The photo and canonical artwork links render without JavaScript; the dock enhances them when scripting is available.

The three room backgrounds were generated with the built-in imagegen tool from the approved reference. Prompt intent: recreate the warm olive-sofa hero and vintage burgundy-chair closing room with empty walls, plus a genuinely cluttered rented apartment; omit all art, frames, text and UI so catalog images can be inserted independently. Assets are saved in `scripts/assets/rooms/`.

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

Regression checks cover static product routes, catalog prices and dimensions, Stripe link preservation, sold-item behavior, email fallback, rebuild consistency, and rejected invalid metadata/payment URLs.

For the complete browser check, start the local server, install Playwright in a separate Python environment if needed, and use an installed Chrome:

```sh
python scripts/check-browser.py http://127.0.0.1:8020
```

The checks exercise photographic room controls, the artwork-attached label, room-preserving artwork choices, product navigation, previews, gallery filters, saved works, all canonical product states, widths 360/390/768/1440, and no-JavaScript rendering and operation without WebGL. Results and full-page captures go to `output/verification/`.

## Deployment

The existing GitHub Actions workflow builds and deploys `site/` to GitHub Pages when changes are pushed to `main`. The configured public domain is `https://art-supreme.com/`.

The local `site/actually-good/` and `site/directions/` folders are earlier design studies, separate from the public build source. They are not required for the storefront. Their paths are excluded from crawling in the generated robots file.
