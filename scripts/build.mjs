#!/usr/bin/env node
/** Build the Art Supreme storefront from site/products, preserving canonical product URLs. */
import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import { createHash } from 'node:crypto';
import { stripeLinkMode } from './stripe-links.mjs';

const ROOT = fileURLToPath(new URL('../', import.meta.url));
const SITE = path.join(ROOT, 'site');
const SOURCE = path.join(ROOT, 'scripts', 'assets');
const PRODUCT_DIR = path.join(SITE, 'products');
const hostedCheckout = process.argv.includes('--checkout');
const ORIGIN = 'https://art-supreme.com';
const HEADLINE = 'What if AI art was actually good?';
const CONTACT = 'info@art-supreme.com';
const imagePattern = /\.(jpe?g|png|gif|webp)$/i;
const landscapes = new Set(['colmar','seastation','summer-dream','village','vulcanic-snap','perceptive-meadow','shipfluent']);
const figures = new Set(['artes-face','aztlan','brain-affairs','canyon','crusaide','head-asylum','horsegenics']);
const observations = {
  splash: 'Sharp folds of blue and orange gather around a bright center.',
  canyon: 'A face appears between bands of gold, green, and rust.',
  village: 'A green landscape holds together until the shapes begin to slip.',
  colmar: 'A street scene pulled into saturated color and irregular geometry.',
  seastation: 'Yellow and turquoise forms sit somewhere between a harbor and an imagined landscape.',
  horsegenics: 'A familiar animal shape emerges from an arrangement of vivid, unfamiliar forms.',
  'artes-face': 'A portrait assembled from broken color, recognizable features, and unexpected textures.',
  stir: 'Color gathers, stretches, and changes direction across the image.',
  bullion: 'Bright yellow carries a dense arrangement of angular, overlapping shapes.',
  aztlan: 'A figure meets a crowded field of purple, red, and electric blue.'
};
const escapeHtml = value => String(value ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
const safeText = value => String(value ?? '').replace(/—/g, '–');
const jsonScript = value => JSON.stringify(value).replace(/</g, '\\u003c').replace(/\u2028/g, '\\u2028').replace(/\u2029/g, '\\u2029');
function price(product) {
  const wholeAmount=Number.isInteger(product.price)?{maximumFractionDigits:0}:{};
  return product.price > 0 ? new Intl.NumberFormat('en', { style:'currency', currency:product.currency, ...wholeAmount }).format(product.price) : 'Price on request';
}
function getProducts() {
  if (!fs.existsSync(PRODUCT_DIR)) throw new Error('Missing site/products directory.');
  return fs.readdirSync(PRODUCT_DIR, {withFileTypes:true}).filter(d=>d.isDirectory()).map(d=>{
    const infoPath=path.join(PRODUCT_DIR,d.name,'info.json');
    let meta={};
    if(fs.existsSync(infoPath)) {
      try {meta=JSON.parse(fs.readFileSync(infoPath,'utf8'));}
      catch(error) {throw new Error(`Invalid product JSON: ${infoPath}: ${error.message}`);}
    }
    const rawPrice=Number(meta.price ?? 0);
    if(!Number.isFinite(rawPrice)||rawPrice<0)throw new Error(`Invalid price for ${d.name}`);
    const rawTitle=safeText(meta.title||d.name.replace(/-/g,' '));
    const excerpt=safeText(meta.excerpt||'');
    const dimensions=excerpt.match(/(\d+(?:[.,]\d+)?)\s*[x×]\s*(\d+(?:[.,]\d+)?)\s*cm/i);
    const sold=meta.sold===true||meta.status==='sold'||/\[sold\]/i.test(rawTitle);
    const payment=String(meta.stripePaymentLinkUrl||'').trim();
    const paymentMode=stripeLinkMode(payment);
    if(payment&&!paymentMode)throw new Error(`Invalid Stripe Payment Link for ${d.name}`);
    const currency=String(meta.currency||'EUR').toUpperCase();
    try {new Intl.NumberFormat('en',{style:'currency',currency}).format(0);}catch {throw new Error(`Invalid currency for ${d.name}`);}
    return {slug:d.name,title:rawTitle.replace(/\s*\[sold\]/ig,'').trim(),price:rawPrice,currency,priceLabel:'',sold,
      images:fs.readdirSync(path.join(PRODUCT_DIR,d.name),{withFileTypes:true}).filter(f=>f.isFile()&&imagePattern.test(f.name)).map(f=>f.name).sort(),
      size:safeText(meta.dimensions||meta.size||(dimensions?`${dimensions[1]} × ${dimensions[2]} cm`:'Dimensions on request')),
      material:safeText(meta.material||(/frame (?:is )?included/i.test(excerpt)?'Frame included':/canvas/i.test(excerpt)?'Canvas':'Details on request')),
      description:safeText(meta.description||observations[d.name]||'A generative work from the Art Supreme collection.'),
      excerpt,content:safeText(meta.content||''),stripePaymentLinkUrl:!hostedCheckout&&paymentMode==='live'?payment:'',
      category:landscapes.has(d.name)?'landscape':figures.has(d.name)?'figure':'abstract',
      roomImages:!['splash','aztlan','bullion','fifth-element','stir'].includes(d.name)};
  }).sort((a,b)=>a.slug.localeCompare(b.slug)).map(p=>({...p,priceLabel:price(p)}));
}
const crops=JSON.parse(fs.readFileSync(path.join(SOURCE,'artwork-crops.json'),'utf8'));
const products=getProducts().map(p=>({...p,artwork:crops[p.slug]&&p.images.includes(crops[p.slug].source)?{url:'assets/artworks/'+p.slug+'.webp',width:crops[p.slug].width,height:crops[p.slug].height}:null}));
for(const p of products)if(p.artwork&&!fs.existsSync(path.join(SOURCE,p.artwork.url.replace(/^assets\//,''))))throw new Error(`Missing artwork texture for ${p.slug}. Run node scripts/extract-artworks.mjs.`);
const catalog=Object.fromEntries(products.map(p=>[p.slug,p]));
const checkoutCatalog=Object.fromEntries(products.map(({slug,title,price,currency,sold})=>[slug,{title,price,currency,sold}]));
fs.writeFileSync(path.join(ROOT,'scripts','.checkout-catalog.json'),JSON.stringify(checkoutCatalog)+'\n');
const assetHash=createHash('sha256').update(JSON.stringify(catalog));
for(const name of ['site.css','site.js','world.js'])assetHash.update(fs.readFileSync(path.join(SOURCE,name)));
const ASSET_VERSION=assetHash.digest('hex').slice(0,12);
const productUrl=(p,base='')=>`${base}product/${encodeURIComponent(p.slug)}.html`;
const imageUrl=(p,index=0,base='')=>`${base}products/${encodeURIComponent(p.slug)}/${encodeURIComponent(p.images[index])}`;
const signature='<span class="brand-signature" role="img" aria-label="Art Supreme"></span>';
function card(p,base='') {
  const image=p.images.length?`<img src="${p.artwork?base+p.artwork.url:imageUrl(p,0,base)}" alt="${escapeHtml(p.title)} – catalog image" loading="lazy" decoding="async">`:'<span class="no-image">Image on request</span>';
  return `<article class="art-card"><button type="button" class="save-work" data-save="${escapeHtml(p.slug)}" aria-pressed="false" aria-label="Save ${escapeHtml(p.title)}" hidden>☆</button>${p.sold?'<span class="sold-mark">Sold</span>':''}<a class="card-image" href="${productUrl(p,base)}">${image}</a><div class="card-meta"><h3><a href="${productUrl(p,base)}">${escapeHtml(p.title)}</a></h3><span>${escapeHtml(p.priceLabel)}</span></div><p class="card-details">${escapeHtml(p.size==='Dimensions on request'?'Generative artwork':p.size)}${p.sold?' · Sold':''}</p></article>`;
}
function layout({title,body,page='info',active='',base='',slug='',canonical='',description='',noindex=false,product=null}) {
  const nav=(href,text,key)=>`<a href="${base}${href}"${active===key?' aria-current="page"':''}>${text}</a>`;
  const canonicalUrl=ORIGIN+'/'+canonical;
  const socialImage=product?.images.length?ORIGIN+'/'+imageUrl(product):catalog.splash?.images.length?ORIGIN+'/'+imageUrl(catalog.splash):ORIGIN+'/assets/art-supreme-signature.png';
  const structured=product?{'@context':'https://schema.org','@type':'VisualArtwork',name:product.title,url:canonicalUrl,image:product.images.map((_,i)=>ORIGIN+'/'+imageUrl(product,i)),description:product.description,artMedium:'Generative AI'}:null;
  return `<!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="theme-color" content="#10110f"><link rel="icon" href="${base}assets/art-supreme-signature.png"><link rel="preload" href="${base}assets/inter.woff2" as="font" type="font/woff2" crossorigin><title>${escapeHtml(title)} | Art Supreme</title><meta name="description" content="${escapeHtml(description||'Explore generative artworks from Art Supreme. View the collection, inspect each work, and find your own answer.')}" >${noindex?'<meta name="robots" content="noindex">':''}<link rel="canonical" href="${escapeHtml(canonicalUrl)}"><meta property="og:type" content="website"><meta property="og:title" content="${escapeHtml(title)}"><meta property="og:description" content="${escapeHtml(description||HEADLINE)}"><meta property="og:url" content="${escapeHtml(canonicalUrl)}"><meta property="og:image" content="${escapeHtml(socialImage)}"><meta name="twitter:card" content="summary_large_image"><link rel="stylesheet" href="${base}css/style.css?v=${ASSET_VERSION}"><script src="${base}js/catalog.js?v=${ASSET_VERSION}" defer></script><script src="${base}js/site.js?v=${ASSET_VERSION}" defer></script><script type="module" src="${base}js/world.js?v=${ASSET_VERSION}"></script>${structured?`<script type="application/ld+json">${jsonScript(structured)}</script>`:''}</head>
<body class="theme-world page-${page}" data-page="${page}" data-base="${base}"${slug?` data-product="${escapeHtml(slug)}"`:''}><a class="skip-link" href="#main">Skip to content</a><header class="site-header"><a class="brand" href="${base}index.html" aria-label="Art Supreme home">${signature}</a><nav aria-label="Main navigation">${nav('gallery.html','Collection','gallery')}<a href="${base}index.html#world" data-open-worlds>Worlds</a><a href="${base}index.html#origin">The story</a></nav><a href="${base}gallery.html?saved=1" class="saved-nav" aria-label="Saved artworks">♡ <span class="saved-label">Saved</span><span data-saved-count></span></a></header><main id="main">${body}</main><footer class="site-footer"><div class="footer-main"><a href="${base}index.html" aria-label="Art Supreme home">${signature}</a><nav aria-label="Footer navigation"><a href="${base}gallery.html">Collection</a><a href="${base}index.html#origin">The story</a><a href="${base}shipping.html">Shipping & returns</a><a href="${base}contact.html">Contact</a></nav><a href="mailto:${CONTACT}">${CONTACT}</a></div><span>Art Supreme / Generative artworks</span></footer></body></html>\n`;
}
function write(filename,content){const destination=path.join(SITE,filename);fs.mkdirSync(path.dirname(destination),{recursive:true});fs.writeFileSync(destination,content,'utf8');}
const featured=catalog['artes-face']||products.find(p=>p.artwork)||products[0];
const selected=['artes-face','summer-dream','swirl-dance'].map(slug=>catalog[slug]).filter(Boolean);
if(!selected.length)selected.push(...products.slice(0,3));
function world(p,{base='',product=false}={}) {
 if(!p?.artwork)return '';
 return `<section class="world-viewer ${product?'product-world':'arrival-world'}" id="world" data-world data-initial-art="${escapeHtml(p.slug)}" aria-label="Interactive artwork room"><div class="room-stage" data-room-stage role="group" aria-label="Photographic artwork room preview"><div class="room-photo" style="background-image:url('${base}assets/rooms/warm-apartment.webp')"></div><a class="room-art-fallback" href="${productUrl(p,base)}" aria-label="View ${escapeHtml(p.title)}"><img src="${base+p.artwork.url}" alt="${escapeHtml(p.title)} in a room visualization" fetchpriority="high"></a></div><svg class="room-leader" data-room-leader aria-hidden="true"><path data-leader-line fill="none"/></svg><a class="room-hotspot" data-art-hotspot href="${productUrl(p,base)}" aria-label="View ${escapeHtml(p.title)}"><span class="artwork-callout" data-art-label>${escapeHtml(p.title)} ↗</span></a>${product?'<div class="viewer-mode"><button type="button" data-view-mode="room" aria-pressed="true">In a room</button><button type="button" data-view-mode="artwork" aria-pressed="false">Artwork only</button></div>':`<div class="arrival-copy"><p class="overline">ARRIVE IN YOUR WORLD</p><h1>What if AI art<br>was actually good?</h1><p>Make room for the unexpected.</p><a href="${base}gallery.html">Browse the collection <span>↗</span></a></div>`}<div class="world-controls"><div class="world-dock" aria-label="Room and artwork controls"><div class="dock-art"><button type="button" class="dock-select dock-art-select" data-picker="artwork" aria-expanded="false" aria-controls="world-picker"><span class="dock-thumb artwork-thumb"><img src="${base+p.artwork.url}" alt="" data-art-thumb></span><span class="dock-copy"><span class="dock-label">ARTWORK</span><span class="dock-value" data-art-name>${escapeHtml(p.title)}</span><span class="dock-hint">Browse the collection</span></span><span class="chevron" aria-hidden="true">⌄</span></button><div class="art-stepper" aria-label="Browse artworks"><button type="button" data-art-step="-1" aria-label="Previous artwork">←</button><button type="button" data-art-step="1" aria-label="Next artwork">→</button></div></div><div class="dock-room"><button type="button" class="dock-select" data-picker="room" aria-expanded="false" aria-controls="world-picker"><span class="dock-thumb room-thumb"><img src="${base}assets/rooms/warm-apartment.webp" alt="" data-room-thumb></span><span class="dock-copy"><span class="dock-label">ROOM</span><span class="dock-value" data-room-name>Warm apartment</span><span class="dock-hint" data-room-mood>Golden afternoon light</span></span><span class="chevron" aria-hidden="true">⌄</span></button></div><button type="button" class="dock-customize" aria-label="Customize room view" data-picker="customize" aria-expanded="false" aria-controls="world-picker"><svg class="settings-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" aria-hidden="true"><path d="M9 4 8 6 5 7l-1 3 2 2-1 3 2 2 3-1 2 2 3-1 1-3 2-1 1-3-2-2V5l-3-1-2 2Z"/><circle cx="11.5" cy="11" r="3"/></svg><span>Customize</span></button></div><div class="world-caption" aria-live="polite"><span data-world-caption>${escapeHtml(p.title)} · ${escapeHtml(p.priceLabel)}${p.sold?' · Sold':''}</span><a data-world-link href="${productUrl(p,base)}">View artwork ↗</a></div><section id="world-picker" class="world-picker" aria-label="World options" hidden><div class="picker-heading"><h2 data-picker-title>Choose an artwork</h2><button type="button" data-close-picker aria-label="Close options">×</button></div><div data-picker-content></div></section></div><div class="world-edge"><span>Room visualization</span><span class="world-feedback" role="status" data-world-status></span><button type="button" data-new-world>New world <span aria-hidden="true">⟳</span></button></div>${product?'':'<a href="#collection" class="scroll-cue" aria-label="Scroll to the collection">⌄</a>'}</section>`;
}
const collectionCard=p=>`<article class="selection-card"><a href="${productUrl(p)}" class="selection-image"><img src="${p.artwork?p.artwork.url:imageUrl(p)}" alt="${escapeHtml(p.title)}" loading="lazy" width="${p.artwork?.width||800}" height="${p.artwork?.height||600}"></a><h3><a href="${productUrl(p)}">${escapeHtml(p.title)}</a></h3><p>${escapeHtml(p.priceLabel)}${p.sold?' · Sold':''}</p>${p.artwork?`<a href="#world" data-try-art="${p.slug}" class="try-room">Try in this room ↗</a>`:`<a href="${productUrl(p)}">View artwork ↗</a>`}</article>`;
const founderNote=`<section class="founder-note" id="origin" aria-labelledby="founder-title"><div class="founder-heading"><p class="overline">THE ORIGIN</p><h2 id="founder-title">It started<br>in 2017.</h2><svg class="origin-orbit" viewBox="0 0 360 220" fill="none" aria-hidden="true"><ellipse cx="175" cy="110" rx="160" ry="62" transform="rotate(-24 175 110)"/><ellipse cx="175" cy="110" rx="110" ry="47" transform="rotate(-30 175 110)"/><circle cx="141" cy="128" r="13" fill="currentColor"/><circle cx="302" cy="58" r="8" fill="var(--paper)"/><circle cx="302" cy="58" r="3" fill="currentColor"/></svg></div><div class="founder-copy"><p>I started experimenting with AI images in 2017 as a personal project, alongside my work with AI and data modelling. I was curious about what was happening, and had a feeling it could become something much bigger.</p><p>I believe AI art will eventually be recognized as a style of its own. Looking back, this period may turn out to be a major turning point in art history: a change in how people make images, with machines becoming part of the creative process.</p><p>Creative jobs are changing too. I believe there will still be a place for people with taste and judgement. Prompting takes practice, but so does recognizing when an image is worth keeping, or knowing what to change when it falls short.</p><p>For me, the purpose is still to create an experience for another person. That’s what interests me about these experiments: what someone else might see in them, and whether an image stays with them after they’ve looked away.</p></div></section>`;
const closingArt=catalog['summer-dream']||featured;
const landing=`${world(featured)}<section class="landing-selection" id="collection"><div class="section-heading"><div><p class="overline">THE COLLECTION</p><h2>Art worth a closer look.</h2></div><a href="gallery.html">View all works ↗</a></div><div class="landing-works">${selected.map(collectionCard).join('')}</div></section>${founderNote}<section class="next-world"><div class="closing-room"><img class="closing-room-photo" src="assets/rooms/collectors-room.webp" alt="A vintage home with a burgundy armchair and books" loading="lazy">${closingArt?.artwork?`<a class="closing-art" href="${productUrl(closingArt)}"><img src="${closingArt.artwork.url}" alt="${escapeHtml(closingArt.title)}" loading="lazy"></a>`:''}</div><div class="next-world-copy"><h2>Another room.<br>Another possibility.</h2><a href="#world" class="action" data-next-world>Find your next world <span>↗</span></a><p>Room visualization</p></div></section>`;
write('index.html',layout({title:HEADLINE,body:landing,page:'index',canonical:'',description:'What if AI art was actually good? Explore the Art Supreme collection of generative artworks, with images, prices, and details for every work.'}));
const gallery=`<section class="gallery-intro"><p class="overline">ART SUPREME / THE COLLECTION</p><h1>The works<span class="gallery-total">${products.length}</span></h1><p>You have the question.<br>Here are the images.</p></section><section class="gallery-controls" aria-label="Collection filters" hidden><div class="category-controls"><button data-category="all" aria-pressed="true">All works</button><button data-category="abstract" aria-pressed="false">Abstract</button><button data-category="figure" aria-pressed="false">Figures</button><button data-category="landscape" aria-pressed="false">Landscapes</button></div><div class="gallery-tools"><label class="search-label"><span class="sr-only">Search artworks</span><input id="gallery-search" type="search" placeholder="Find a work…"></label><label><span class="sr-only">Sort artworks</span><select id="gallery-sort"><option value="curated">Collection order</option><option value="low">Price: low to high</option><option value="high">Price: high to low</option><option value="name">Title: A to Z</option></select></label><button id="saved-only" aria-pressed="false">Saved only</button></div></section><div class="gallery-status"><span id="gallery-result-count" role="status" aria-live="polite">${products.length} works</span><button id="reset-filters" hidden>Reset filters ↺</button></div><noscript><p class="gallery-fallback">All works are shown below. Open any work for its images and purchase options.</p></noscript><section class="gallery-grid" id="gallery-grid" aria-label="Artworks">${products.map(p=>card(p)).join('')}</section>`;
write('gallery.html',layout({title:'The collection',body:gallery,page:'gallery',active:'gallery',canonical:'gallery.html'}));
for(const p of products){
  const mailSubject=encodeURIComponent('Art Supreme enquiry: '+p.title);
  const mailBody=encodeURIComponent(`Hello,\n\nI’m interested in ${p.title}, listed at ${p.priceLabel}. Could you confirm availability, dimensions, materials, framing, and delivery options?\n\nThank you.`);
  const enquire=`mailto:${CONTACT}?subject=${mailSubject}&body=${mailBody}`;
  const canStartCheckout=hostedCheckout&&p.price>0;
  const checkoutAvailable=canStartCheckout||(!hostedCheckout&&Boolean(p.stripePaymentLinkUrl));
  const checkoutLabel=`Buy – ${escapeHtml(p.priceLabel)} <span>↗</span>`;
  const primary=p.sold?'<a class="enquire-link" href="../gallery.html">Sold – explore other works <span>↗</span></a>':
    canStartCheckout?`<form class="checkout-form" method="post" action="../api/checkout/${encodeURIComponent(p.slug)}"><button class="enquire-link btn-buy" type="submit">${checkoutLabel}</button></form>`:
    checkoutAvailable?`<a class="enquire-link btn-buy" href="${escapeHtml(p.stripePaymentLinkUrl)}" target="_blank" rel="noopener">${checkoutLabel}</a>`:
    `<a class="enquire-link" href="${escapeHtml(enquire)}">Enquire about this work <span>↗</span></a>`;
  const img=p.images.length?`<img id="product-main-image" src="${imageUrl(p,0,'../')}" alt="${escapeHtml(p.title)} – catalog image 1" decoding="async">`:'<span class="no-image">Image on request</span>';
  const related=products.filter(x=>x.slug!==p.slug&&x.category===p.category).concat(products.filter(x=>x.slug!==p.slug&&x.category!==p.category)).slice(0,3);
  const body=`<section class="product-page"><a class="product-back" href="../gallery.html" id="back-to-gallery">← Collection</a><article class="product-layout"><div class="product-media">${world(p,{base:"../",product:true})}<div class="catalog-views${p.artwork?" has-world":""}"><a class="product-image-button" id="open-image" href="${p.images.length?imageUrl(p,0,'../'):'../gallery.html'}" aria-label="${p.images.length?'Enlarge':'Explore the collection'} ${escapeHtml(p.title)}">${img}</a><div class="zoom-label"><span>${p.roomImages?'Room visualization':'Catalog image'}</span><span class="zoom-help" hidden>Click image to enlarge ↗</span></div><div class="product-thumbs" aria-label="Artwork gallery" hidden>${p.images.map((_,i)=>`<button type="button" data-image-index="${i}" aria-label="Show image ${i+1}" aria-pressed="${i===0}"><img src="${imageUrl(p,i,'../')}" alt="" loading="lazy"></button>`).join('')}</div><noscript>${p.images.slice(1).map((_,i)=>`<a href="${imageUrl(p,i+1,'../')}">View image ${i+2} ↗</a>`).join(' · ')}</noscript></div></div><div class="product-info"><p class="overline">ART SUPREME / ${p.sold?'SOLD':'GENERATIVE ARTWORK'}</p><h1>${escapeHtml(p.title)}</h1><p class="product-price">${escapeHtml(p.priceLabel)}${p.sold?' · Sold':''}</p><p class="product-summary">${escapeHtml(p.description)}</p><dl class="product-specs"><dt>Medium</dt><dd>Generative AI</dd><dt>Dimensions</dt><dd>${escapeHtml(p.size)}</dd><dt>Object details</dt><dd>${escapeHtml(p.material)}</dd><dt>Availability</dt><dd>${p.sold?'Sold':checkoutAvailable?'View checkout options':'Enquire to confirm'}</dd></dl><div class="product-actions">${primary}<button class="product-save" data-save="${escapeHtml(p.slug)}" aria-pressed="false" hidden>Save this work <span>☆</span></button></div><p class="enquiry-note">${p.sold?'This work is marked sold in the catalog.':checkoutAvailable?'Checkout opens securely with Stripe.':'An enquiry opens your email app. No payment is taken here.'}</p><div class="product-details"><details><summary>About this work</summary><p class="product-description">${escapeHtml(p.content||p.description)}</p>${p.excerpt?`<p>${escapeHtml(p.excerpt)}</p>`:''}</details><details><summary>Size, framing & delivery</summary><p>${escapeHtml(p.size)}. ${escapeHtml(p.material)}. <a href="../shipping.html">Read about shipping and returns</a>, or <a href="${escapeHtml(enquire)}">ask about the work</a> before ordering.</p></details><details><summary>About the room images</summary><p>Room scenes are visualizations showing how an artwork could sit in a space. Use the confirmed dimensions to judge the size of the physical work.</p></details></div></div></article></section><section class="related-section"><div class="section-heading"><span class="overline">KEEP LOOKING</span><h2>Other possibilities.</h2><a href="../gallery.html">All works ↗</a></div><div class="related-grid">${related.map(x=>card(x,'../')).join('')}</div></section>`;
  write(`product/${p.slug}.html`,layout({title:p.title,body,page:'product',base:'../',slug:p.slug,canonical:`product/${encodeURIComponent(p.slug)}.html`,description:`${p.title} – ${p.priceLabel}. ${p.description}`,product:p}));
}
// Remove generated routes for works deleted from the catalog.
const productPages=path.join(SITE,'product');
const currentPages=new Set(products.map(p=>p.slug+'.html'));
for(const file of fs.readdirSync(productPages,{withFileTypes:true})){
  if(file.isFile()&&file.name.endsWith('.html')&&!currentPages.has(file.name))fs.unlinkSync(path.join(productPages,file.name));
}
const infoHeading=(label,title)=>`<section class="page-heading"><p class="overline">${label}</p><h1>${title}</h1></section>`;
write('contact.html',layout({title:'Contact',active:'contact',canonical:'contact.html',body:infoHeading('ART SUPREME / CONTACT','Let’s talk<br>about the work.')+`<section class="info-layout"><div><h2>Get in touch.</h2><p>Questions about an artwork, its size, framing, or delivery? Write to us.</p><p><a class="contact-address" href="mailto:${CONTACT}">${CONTACT}</a></p><a class="action" href="gallery.html">Explore the collection <span>↗</span></a></div><aside class="info-aside"><p class="overline">YOUR ENQUIRY</p><h2>Start with<br>the picture.</h2><p>Include the work’s title and your delivery country. You can also open a product page and use its enquiry button to start an email with the work already named.</p></aside></section>`}));
write('shipping.html',layout({title:'Shipping & returns',active:'shipping',canonical:'shipping.html',body:infoHeading('ART SUPREME / DELIVERY','Shipping<br>& returns.')+`<section class="info-layout"><div><h2>From the gallery<br>to your wall.</h2><p>We ship worldwide. Flat rate shipping. Contact us for details.</p><p>For the shipping cost, delivery timing, and return arrangements for a particular work, <a href="mailto:${CONTACT}">get in touch</a> before ordering.</p><a class="action" href="contact.html">Ask about delivery <span>↗</span></a></div><aside class="info-aside"><p class="overline">BEFORE YOU ORDER</p><h2>A few useful<br>details.</h2><p>Tell us which artwork you’re considering and where it will be delivered. Confirm the final dimensions, framing, and delivery arrangements for that work.</p></aside></section>`}));
write('success.html',layout({title:'Thank you',canonical:'success.html',noindex:true,body:infoHeading('ART SUPREME','Thank you.')+`<section class="info-layout success-layout"><div><h2>Your next<br>possibility.</h2><p>If you completed checkout, refer to your checkout confirmation for payment details.</p><p>For questions about your artwork or delivery, contact <a href="mailto:${CONTACT}">${CONTACT}</a>.</p><a class="action" href="gallery.html">Back to the gallery <span>↗</span></a></div><aside class="info-aside"><p class="overline">ART SUPREME</p><h2>What if AI art<br>was actually good?</h2><p>There is always another image worth a closer look.</p></aside></section>`}));
fs.mkdirSync(path.join(SITE,'js'),{recursive:true});
write('js/catalog.js',`window.ART_CATALOG=${jsonScript(catalog)};\n`);
for(const [source,destination] of [['site.css','css/style.css'],['site.js','js/site.js'],['world.js','js/world.js'],['art-supreme-signature.png','assets/art-supreme-signature.png'],['inter.woff2','assets/inter.woff2'],['inter-OFL.txt','assets/inter-OFL.txt']]){
 fs.mkdirSync(path.dirname(path.join(SITE,destination)),{recursive:true});fs.copyFileSync(path.join(SOURCE,source),path.join(SITE,destination));
}
for(const name of ['rooms','artworks'])fs.cpSync(path.join(SOURCE,name),path.join(SITE,'assets',name),{recursive:true});
// Remove generated 3D bundles left by the retired room prototype.
for(const retired of ['js/room-scene.js','assets/vendor','assets/room3d'])fs.rmSync(path.join(SITE,retired),{recursive:true,force:true});
const urls=['','gallery.html','contact.html','shipping.html',...products.map(p=>`product/${encodeURIComponent(p.slug)}.html`)];
write('sitemap.xml',`<?xml version="1.0" encoding="UTF-8"?>\n<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">${urls.map(url=>`<url><loc>${ORIGIN}/${escapeHtml(url)}</loc></url>`).join('')}</urlset>\n`);
write('robots.txt',`User-agent: *\nAllow: /\nDisallow: /actually-good/\nDisallow: /directions/\nDisallow: /success.html\nSitemap: ${ORIGIN}/sitemap.xml\n`);
console.log(`Built Art Supreme: ${products.length} products, landing page, gallery, contact, shipping, and checkout return page.`);
