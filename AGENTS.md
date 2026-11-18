# Art Supreme

- The approved public design is the yellow-and-black **The Objection** direction with the headline **What if AI art was actually good?** Preserve it unless the user requests another direction.
- Use the original handwritten signature from `scripts/assets/art-supreme-signature.png`. Keep its lettering intact rather than substituting the current theme’s heading font.
- Edit the generator in `scripts/build.mjs` and production assets in `scripts/assets/`, then rebuild. `site/` contains generated output, and `site/products/` contains the product source metadata and images.
- Preserve canonical product URLs at `/product/<slug>.html` and the existing catalog’s images, prices, descriptions, sold state, and Stripe links.
- Offer Stripe checkout only for unsold works with a valid saved payment link. Otherwise use the existing `info@art-supreme.com` enquiry address. Do not infer successful payment from loading the return page.
- Use en dashes (–) rather than em dashes in public copy and metadata. Do not invent edition sizes, availability, provenance claims, production methods, or testimonials. Room scenes are visualizations.
- The folders `site/actually-good/` and `site/directions/` are local design studies. Keep production source independent from them and keep commits focused on the requested work.
- Run the relevant build regression checks with `node --test scripts/build.test.mjs`. Inspect the affected user journeys and responsive layouts for visual changes.
