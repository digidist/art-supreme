import test from 'node:test';
import assert from 'node:assert/strict';
import fs from 'node:fs';
import os from 'node:os';
import path from 'node:path';
import {fileURLToPath} from 'node:url';
import {spawnSync} from 'node:child_process';
const scripts=fileURLToPath(new URL('./',import.meta.url));
function fixture(t,products){
 const root=fs.mkdtempSync(path.join(os.tmpdir(),'art-supreme-build-'));t.after(()=>fs.rmSync(root,{recursive:true,force:true}));
 fs.mkdirSync(path.join(root,'scripts'),{recursive:true});fs.copyFileSync(path.join(scripts,'build.mjs'),path.join(root,'scripts/build.mjs'));fs.cpSync(path.join(scripts,'assets'),path.join(root,'scripts/assets'),{recursive:true});
 for(const [slug,info] of Object.entries(products)){const dir=path.join(root,'site/products',slug);fs.mkdirSync(dir,{recursive:true});fs.writeFileSync(path.join(dir,'info.json'),typeof info==='string'?info:JSON.stringify(info));fs.writeFileSync(path.join(dir,'01.png'),Buffer.from('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQIHWP4z8DwHwAFgAI/ScLbtAAAAABJRU5ErkJggg==','base64'));}
 const run=()=>spawnSync(process.execPath,[path.join(root,'scripts/build.mjs')],{cwd:root,encoding:'utf8'});return {root,run,read:file=>fs.readFileSync(path.join(root,'site',file),'utf8')};
}
test('build preserves static product URLs, real prices, dimensions and checkout links',t=>{
 const f=fixture(t,{'a-work':{title:'A & B',price:520,excerpt:'High resolution 90x120 cm format. Frame included.',stripePaymentLinkUrl:'https://buy.stripe.com/test_example'}});const result=f.run();assert.equal(result.status,0,result.stderr);
 const page=f.read('product/a-work.html');assert.match(page,/<h1>A &amp; B<\/h1>/);assert.match(page,/€520/);assert.match(page,/90 × 120 cm/);assert.match(page,/Frame included/);assert.match(page,/href="https:\/\/buy\.stripe\.com\/test_example"/);assert.match(f.read('gallery.html'),/href="product\/a-work\.html"/);assert.match(f.read('sitemap.xml'),/https:\/\/art-supreme\.com\/product\/a-work\.html/);
 const first=f.read('index.html');assert.equal(f.run().status,0);assert.equal(f.read('index.html'),first,'Rebuilding the same catalog should preserve the generated page.');
});
test('sold work cannot offer its Stripe checkout and missing links use an email enquiry',t=>{
 const f=fixture(t,{'sold-work':{title:'A work [SOLD]',price:160,stripePaymentLinkUrl:'https://buy.stripe.com/test_sold'},'enquiry-work':{title:'Another work',price:210}});const result=f.run();assert.equal(result.status,0,result.stderr);
 const sold=f.read('product/sold-work.html');assert.match(sold,/Sold – explore other works/);assert.doesNotMatch(sold,/class="enquire-link btn-buy"/);assert.doesNotMatch(sold,/href="https:\/\/buy\.stripe\.com\/test_sold"/);
 assert.match(f.read('product/enquiry-work.html'),/class="enquire-link" href="mailto:info@art-supreme\.com\?/);assert.match(f.read('gallery.html'),/class="sold-mark">Sold/);
});
test('bad catalog input fails before generating a storefront',t=>{
 const f=fixture(t,{'broken':'{invalid json'});const result=f.run();assert.notEqual(result.status,0);assert.match(result.stderr,/Invalid product JSON/);assert.equal(fs.existsSync(path.join(f.root,'site/index.html')),false);
});
test('an invalid payment URL cannot become a purchase link',t=>{
 const f=fixture(t,{'bad-link':{title:'Work',price:100,stripePaymentLinkUrl:'javascript:alert(1)'}});const result=f.run();assert.notEqual(result.status,0);assert.match(result.stderr,/Invalid Stripe Payment Link/);
});
