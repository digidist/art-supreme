import test from 'node:test';
import assert from 'node:assert/strict';
import fs from 'node:fs';
import os from 'node:os';
import path from 'node:path';
import {fileURLToPath,pathToFileURL} from 'node:url';
import {spawnSync} from 'node:child_process';
const scripts=fileURLToPath(new URL('./',import.meta.url));
function fixture(t,products){
 const root=fs.mkdtempSync(path.join(os.tmpdir(),'art-supreme-build-'));t.after(()=>fs.rmSync(root,{recursive:true,force:true}));
 fs.mkdirSync(path.join(root,'scripts'),{recursive:true});for(const name of ['build.mjs','stripe-links.mjs'])fs.copyFileSync(path.join(scripts,name),path.join(root,'scripts',name));fs.cpSync(path.join(scripts,'assets'),path.join(root,'scripts/assets'),{recursive:true});
 for(const [slug,info] of Object.entries(products)){const dir=path.join(root,'site/products',slug);fs.mkdirSync(dir,{recursive:true});fs.writeFileSync(path.join(dir,'info.json'),typeof info==='string'?info:JSON.stringify(info));fs.writeFileSync(path.join(dir,'01.png'),Buffer.from('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQIHWP4z8DwHwAFgAI/ScLbtAAAAABJRU5ErkJggg==','base64'));}
 const run=(...args)=>spawnSync(process.execPath,[path.join(root,'scripts/build.mjs'),...args],{cwd:root,encoding:'utf8'});return {root,run,read:file=>fs.readFileSync(path.join(root,'site',file),'utf8')};
}
test('build preserves static product URLs, real prices, dimensions and checkout links',t=>{
 const f=fixture(t,{'a-work':{title:'A & B',price:520,excerpt:'High resolution 90x120 cm format. Frame included.',stripePaymentLinkUrl:'https://buy.stripe.com/liveExample'}});const result=f.run();assert.equal(result.status,0,result.stderr);
 const page=f.read('product/a-work.html');assert.match(page,/<h1>A &amp; B<\/h1>/);assert.match(page,/€520/);assert.match(page,/90 × 120 cm/);assert.match(page,/Frame included/);assert.match(page,/href="https:\/\/buy\.stripe\.com\/liveExample"/);assert.match(f.read('gallery.html'),/href="product\/a-work\.html"/);assert.match(f.read('sitemap.xml'),/https:\/\/art-supreme\.com\/product\/a-work\.html/);
 const first=f.read('index.html');assert.equal(f.run().status,0);assert.equal(f.read('index.html'),first,'Rebuilding the same catalog should preserve the generated page.');
});
test('sold work cannot offer checkout and missing or sandbox links use an email enquiry',t=>{
 const f=fixture(t,{'sold-work':{title:'A work [SOLD]',price:160,stripePaymentLinkUrl:'https://buy.stripe.com/test_sold'},'enquiry-work':{title:'Another work',price:210},'sandbox-work':{title:'Sandbox work',price:200,stripePaymentLinkUrl:'https://buy.stripe.com/test_example'}});const result=f.run();assert.equal(result.status,0,result.stderr);
 const sold=f.read('product/sold-work.html');assert.match(sold,/Sold – explore other works/);assert.doesNotMatch(sold,/class="enquire-link btn-buy"/);assert.doesNotMatch(sold,/href="https:\/\/buy\.stripe\.com\/test_sold"/);
 assert.match(f.read('product/enquiry-work.html'),/class="enquire-link" href="mailto:info@art-supreme\.com\?/);assert.match(f.read('product/sandbox-work.html'),/href="mailto:info@art-supreme\.com\?/);assert.doesNotMatch(f.read('product/sandbox-work.html'),/buy\.stripe\.com/);assert.match(f.read('gallery.html'),/class="sold-mark">Sold/);
});
test('bad catalog input fails before generating a storefront',t=>{
 const f=fixture(t,{'broken':'{invalid json'});const result=f.run();assert.notEqual(result.status,0);assert.match(result.stderr,/Invalid product JSON/);assert.equal(fs.existsSync(path.join(f.root,'site/index.html')),false);
});
test('an invalid payment URL cannot become a purchase link',t=>{
 const f=fixture(t,{'bad-link':{title:'Work',price:100,stripePaymentLinkUrl:'javascript:alert(1)'}});const result=f.run();assert.notEqual(result.status,0);assert.match(result.stderr,/Invalid Stripe Payment Link/);
});

test('hosted checkout uses the published gallery and one Stripe product without metadata',async t=>{
 const f=fixture(t,{
  'renamed-work':{title:'Old title',price:180,stripePaymentLinkUrl:'https://buy.stripe.com/oldLiveLink'},
  'sold-work':{title:'Sold work [SOLD]',price:210},
  'unpriced-work':{title:'Unpriced work'},
  'removed-work':{title:'Removed work',price:220},
 });
 assert.equal(f.run().status,0);
 fs.writeFileSync(path.join(f.root,'site/products/renamed-work/info.json'),JSON.stringify({title:'New title',price:185.75,stripePaymentLinkUrl:'https://buy.stripe.com/oldLiveLink'}));
 fs.rmSync(path.join(f.root,'site/products/removed-work'),{recursive:true});
 const build=f.run('--checkout');
 assert.equal(build.status,0,build.stderr);
 const page=f.read('product/renamed-work.html');
 assert.match(page,/method="post" action="\.\.\/api\/checkout\/renamed-work"/);
 assert.match(page,/Buy – €185\.75/);
 assert.doesNotMatch(page,/oldLiveLink/);
 assert.doesNotMatch(f.read('product/sold-work.html'),/checkout-form/);
 assert.match(f.read('product/unpriced-work.html'),/mailto:info@art-supreme\.com/);
 assert.equal(fs.existsSync(path.join(f.root,'site/product/removed-work.html')),false);

 const catalog=JSON.parse(fs.readFileSync(path.join(f.root,'scripts/.checkout-catalog.json'),'utf8'));
 const {startCheckout}=await import(pathToFileURL(path.join(scripts,'checkout.mjs')).href);
 const env={STRIPE_CHECKOUT_SECRET_KEY:'rk_live_fixture',STRIPE_SHARED_PRODUCT_ID:'prod_fixture'};
 const stripeRequests=[];
 t.mock.method(globalThis,'fetch',async (url,options)=>{
  stripeRequests.push([url,options]);
  return Response.json({id:'cs_live_fixture',livemode:true,url:'https://checkout.stripe.com/c/pay/cs_live_fixture#checkout'});
 });
 const request=(slug,options={})=>new Request('https://art-supreme.com/api/checkout/'+slug,{method:'POST',...options});
 assert.equal((await startCheckout(request('removed-work'),env,catalog)).status,404);
 assert.equal((await startCheckout(request('sold-work'),env,catalog)).status,409);
 assert.equal((await startCheckout(request('unpriced-work'),env,catalog)).status,409);
 assert.equal((await startCheckout(request('renamed-work',{method:'GET'}),env,catalog)).status,405);
 assert.equal((await startCheckout(request('renamed-work',{headers:{origin:'https://example.org'}}),env,catalog)).status,403);
 assert.equal((await startCheckout(request('renamed-work'),{},catalog)).status,503);
 assert.equal((await startCheckout(request('renamed-work'),{...env,STRIPE_CHECKOUT_MODE:'test',STRIPE_CHECKOUT_SECRET_KEY:'rk_test_fixture'},catalog)).status,503);
 assert.equal(stripeRequests.length,0);

 const response=await startCheckout(request('renamed-work',{body:'amount=1&title=Untrusted'}),env,catalog);
 assert.equal(response.status,303);
 assert.equal(response.headers.get('location'),'https://checkout.stripe.com/c/pay/cs_live_fixture#checkout');
 assert.equal(stripeRequests.length,1);
 const [[endpoint,options]]=stripeRequests;
 assert.equal(endpoint,'https://api.stripe.com/v1/checkout/sessions');
 const params=options.body;
 assert.equal(params.get('line_items[0][price_data][product]'),'prod_fixture');
 assert.equal(params.get('line_items[0][price_data][unit_amount]'),'18575');
 assert.equal(params.get('line_items[0][price_data][currency]'),'eur');
 assert.equal(params.get('custom_text[submit][message]'),'Artwork – New title');
 assert.equal(params.get('client_reference_id'),'renamed-work');
 assert.equal(params.get('success_url'),'https://art-supreme.com/success.html');
 assert.equal(params.get('cancel_url'),'https://art-supreme.com/product/renamed-work.html');
 assert.ok([...params.keys()].every(key=>!key.includes('metadata')&&!key.includes('product_data')));
});
