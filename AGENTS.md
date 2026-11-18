# Art Supreme

- The approved public design is now **Arrive in your world**, matching `output/design-reference.png`: photographic room hero, compact artwork/room/customize dock, dark collection, original origin note, second room invitation, and footer. Preserve the yellow-and-black identity and headline **What if AI art was actually good?** The user explicitly approved this replacement for The Objection.
- Use the original handwritten signature from `scripts/assets/art-supreme-signature.png`. Keep its lettering intact rather than substituting the current theme’s heading font.
- Edit the generator in `scripts/build.mjs` and production assets in `scripts/assets/`, then rebuild. `site/` contains generated output, and `site/products/` contains the product source metadata and images.
- Preserve canonical product URLs at `/product/<slug>.html` and the existing catalog’s images, prices, descriptions, sold state, and Stripe links.
- Offer Stripe checkout only for unsold works with a valid saved payment link. Otherwise use the existing `info@art-supreme.com` enquiry address. Do not infer successful payment from loading the return page.
- Use en dashes (–) rather than em dashes in public copy and metadata. Do not invent edition sizes, availability, provenance claims, production methods, or testimonials. Room scenes are visualizations.
- The folders `site/actually-good/` and `site/directions/` are local design studies. Keep production source independent from them and keep commits focused on the requested work.
- Run the relevant build regression checks with `node --test scripts/build.test.mjs`. Inspect the affected user journeys and responsive layouts for visual changes.

- The homepage origin note uses the author’s approved wording headed “It started in 2017.” Keep the history as a personal AI experiment alongside AI/data-modelling work; present future historical significance as a belief. Do not add discussion of selling the early images or income goals.

- Room sources live in `scripts/assets/rooms/`; original image crop metadata and lossless textures live in `scripts/assets/artwork-crops.json` and `scripts/assets/artworks/`. Regenerate with `node scripts/extract-artworks.mjs` (ImageMagick) after crop changes, then rebuild. Never repaint the catalog art.
- The user explicitly retired Three.js and chose the original photographic direction. Use `scripts/assets/rooms/` photographs with separately placed original artwork and the glass artwork/room/frame controls in `world.js`. Do not restore 3D geometry, walking, orbit, fake depth or WebGL unless requested again. Keep the artwork-attached name and leader callout, canonical product navigation, and the original catalog image viewer.
- Run `scripts/check-browser.py` for responsive photographic previews, room/art/frame selection, product navigation, catalog/checkout behavior and no-JavaScript rendering. The production build must not copy or load Three.js, room3d models or HDR assets. Earlier studies in `output/photoreal-study/` are retired and do not define the public experience.
- Judge desktop and mobile screenshots against the approved photographic reference. Preserve its warm, natural lighting and the brighter satin glass controls; do not call a visibly synthetic replacement equivalent.

- Photographic frame lighting uses room-specific CSS profiles and a wall-light overlay aligned to the room photograph’s exact cover crop in `world.js`. Keep shadows and bevel highlights consistent with each room’s key light. Preserve original artwork proportions, source files and the unshaded artwork-only view. Apply the same treatment to the closing room invitation. Resolve CSS light-image URLs against document.baseURI before assigning them to custom properties; relative URL tokens otherwise resolve against the external stylesheet.

- Artwork and room selection uses the larger thumbnail-led glass dock and visual preview cards. Keep selected thumbnails synchronized with state. Use container-based compact layouts for product previews, stack artwork and room controls on mobile, and keep choice panels scrollable within the available room area.
