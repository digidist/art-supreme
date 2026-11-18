"""Browser regression checks. Requires Playwright and an installed Chrome.
Run: python scripts/check-browser.py http://127.0.0.1:8020
"""
import asyncio,json,sys
from pathlib import Path
from playwright.async_api import async_playwright
URL=(sys.argv[1] if len(sys.argv)>1 else 'http://127.0.0.1:8020').rstrip('/')
OUT=Path(__file__).resolve().parents[1]/'output/verification'
OUT.mkdir(parents=True,exist_ok=True)
async def main():
 async with async_playwright() as p:
  browser=await p.chromium.launch(channel='chrome',headless=True,args=['--use-angle=swiftshader','--use-gl=angle'])
  context=await browser.new_context(viewport={'width':1440,'height':1000},device_scale_factor=1)
  page=await context.new_page();errors=[];missing=[];checks=[]
  runtime_requests=[]
  page.on('request',lambda r:runtime_requests.append(r.url) if any(x in r.url for x in ('/room3d/','/vendor/','room-scene.js')) else None)
  page.on('pageerror',lambda e:errors.append(str(e)))
  page.on('response',lambda r:missing.append(r.url) if r.status>=400 and r.url.startswith(URL) else None)
  async def goto(route=''):
   response=await page.goto(URL+'/'+route,wait_until='networkidle');assert response.ok,(route,response.status)
  async def settled():
   await page.wait_for_function("document.querySelector('[data-world]')?.dataset.renderer === 'photograph' && document.querySelector('[data-world]').getAttribute('aria-busy') === 'false'")
   await page.wait_for_timeout(180)
  async def capture(name):
   await page.evaluate("Promise.all(Array.from(document.images).map(i=>{i.loading='eager';return i.decode().catch(()=>{})}))")
   await page.screenshot(path=str(OUT/name),full_page=True)
  async def no_overflow():assert not await page.evaluate('document.documentElement.scrollWidth>innerWidth'),(page.url,await page.evaluate('innerWidth'))
  async def no_mobile_photo_overlap():
   boxes=await page.evaluate('''()=>{const box=s=>{const r=document.querySelector(s).getBoundingClientRect();return {top:r.top,bottom:r.bottom}};return {photo:box('[data-room-stage]'),dock:box('.world-dock'),panel:document.querySelector('#world-picker').hidden?null:box('#world-picker')}}''')
   assert boxes['dock']['top']>=boxes['photo']['bottom']-1,boxes
   if boxes['panel']:assert boxes['panel']['top']>=boxes['dock']['bottom']-1,boxes
  await goto();await settled();assert await page.locator('h1').inner_text()=='What if AI art\nwas actually good?'
  assert await page.locator('.brand-signature').count()==2
  await capture('home-desktop.png')
  await page.locator('[data-picker="artwork"]').click();assert await page.locator('.picker-art').count()==20
  await page.locator('[data-select-art="summer-dream"]').click();await settled()
  assert await page.locator('[data-world]').get_attribute('data-room')=='warm-apartment'
  await page.locator('[data-picker="room"]').click();await page.locator('[data-select-room="beautiful-mess"]').click();await settled()
  assert await page.locator('[data-world]').get_attribute('data-art')=='summer-dream'
  await page.locator('[data-picker="customize"]').click();await page.locator('button[data-frame="oak"]').click();await settled()
  assert await page.locator('[data-world]').get_attribute('data-frame')=='oak'
  await page.keyboard.press('Escape');assert not await page.locator('#world-picker').is_visible()
  assert await page.locator('[data-room-stage] canvas').count()==0
  assert await page.locator('[data-enter-room]').count()==0
  await page.locator('[data-world-link]').click();await page.wait_for_url('**/product/summer-dream.html');await settled()
  assert await page.locator('[data-world]').get_attribute('data-room')=='beautiful-mess'
  assert await page.locator('.product-info h1').inner_text()=='Summer Dream'
  assert await page.locator('.product-price').inner_text()=='€180'
  assert (await page.locator('.btn-buy').get_attribute('href')).startswith('https://buy.stripe.com/')
  await page.locator('[data-view-mode="artwork"]').click();await settled();assert await page.locator('[data-world]').evaluate("e=>e.classList.contains('artwork-only')")
  await page.locator('[data-view-mode="room"]').click();await settled()
  await capture('product-desktop.png')
  await page.locator('[data-image-index]').last.click();assert (await page.locator('#product-main-image').get_attribute('src')).endswith('04.jpg')
  await page.locator('#open-image').click();assert await page.locator('.zoom-dialog').is_visible();await page.keyboard.press('Escape')
  await page.locator('.product-save').click();assert await page.locator('.product-save').get_attribute('aria-pressed')=='true'
  await page.locator('[data-art-step="1"]').click();await page.wait_for_url('**/product/swirl-dance.html');await settled()
  assert await page.locator('.product-info h1').inner_text()=='Swirl Dance'
  assert await page.locator('[data-world]').get_attribute('data-room')=='beautiful-mess'
  checks.extend(['photographic room with no 3D renderer','20 artwork choices','independent room/art changes','frame preview','simple frame controls','room persists across product navigation','product art navigation keeps details coherent','original catalog views and zoom','saved work'])
  print('Room and product journeys passed',flush=True)
  await goto('gallery.html');assert await page.locator('.gallery-grid .art-card').count()==20
  await page.locator('#gallery-search').fill('canyon');assert await page.locator('.gallery-grid .art-card').count()==1
  await page.locator('.card-image').click();await page.wait_for_url('**/product/canyon.html');await settled()
  await page.locator('#back-to-gallery').click();assert 'q=canyon' in page.url
  await page.locator('#reset-filters').click();await page.locator('#gallery-sort').select_option('low');assert '€140' in await page.locator('.gallery-grid .art-card').first.inner_text()
  await page.locator('[data-category="landscape"]').click();assert await page.locator('.gallery-grid .art-card').count()==7
  await page.locator('#reset-filters').click();await page.locator('#saved-only').click();assert await page.locator('.gallery-grid .art-card').count()==1
  checks.extend(['search/filter/sort','gallery state restored','saved gallery'])
  catalog=await page.evaluate('window.ART_CATALOG');routes=[]
  for slug,work in catalog.items():
   await goto('product/'+slug+'.html')
   assert await page.locator('.product-info h1').inner_text()==work['title']
   assert work['priceLabel'] in await page.locator('.product-price').inner_text()
   link=await page.locator('.enquire-link').get_attribute('href')
   if work['sold']:assert not link.startswith('https://buy.stripe.com')
   elif work['stripePaymentLinkUrl']:assert link==work['stripePaymentLinkUrl']
   else:assert link.startswith('mailto:info@art-supreme.com?')
   assert await page.locator('[data-world]').count()==1
   await no_overflow();routes.append(slug)
  print('All 20 canonical products passed',flush=True)
  # Restore approved arrival composition for screenshots and responsive checks.
  await page.evaluate("localStorage.removeItem('art-supreme-world-v1')")
  responsive=[]
  for width in [360,390,768,1440]:
   await page.set_viewport_size({'width':width,'height':844})
   for route in ['', 'gallery.html','product/artes-face.html','contact.html','shipping.html','success.html']:
    await goto(route);await no_overflow()
    if route in ['', 'product/artes-face.html']:await settled()
    if width<760 and route in ['', 'product/artes-face.html']:await no_mobile_photo_overlap()
    if width==390 and route in ['', 'product/artes-face.html','gallery.html']:
     name={'':'home','product/artes-face.html':'product','gallery.html':'gallery'}[route]
     await capture(name+'-mobile.png')
     if route in ['', 'product/artes-face.html']:
      for kind in ['artwork','room']:
       await page.locator(f'[data-picker="{kind}"]').click();await no_mobile_photo_overlap()
       await capture(name+'-'+kind+'-selector-mobile.png');await page.keyboard.press('Escape')
    responsive.append({'width':width,'route':route or '/'})
   print('Responsive width',width,'passed',flush=True)
  await page.set_viewport_size({'width':390,'height':844});await goto();await settled()
  stage_box=await page.locator('[data-room-stage]').bounding_box();hotspot_box=await page.locator('[data-art-hotspot]').bounding_box();assert stage_box['y']+stage_box['height']*.15<hotspot_box['y']<stage_box['y']+stage_box['height']*.5
  await page.locator('[data-picker="room"]').click();assert await page.locator('#world-picker').is_visible();await page.locator('[data-select-room="collectors-room"]').click();await settled();await no_overflow()
  await page.locator('[data-picker="customize"]').click();assert await page.locator('button[data-frame="oak"]').is_visible();await page.keyboard.press('Escape')
  await page.locator('[data-picker="artwork"]').click();await page.locator('[data-select-art="aztlan"]').click();await settled();await no_overflow()
  checks.extend(['mobile room/art/frame controls','mobile dock and both selectors stay below the room photograph'])
  # The photographic experience, catalog and checkout do not require WebGL.
  nojs=await browser.new_page(viewport={'width':390,'height':844},java_script_enabled=False)
  await nojs.goto(URL+'/');assert await nojs.locator('.room-art-fallback').is_visible();assert not await nojs.locator('.world-dock').is_visible()
  await nojs.goto(URL+'/gallery.html');assert await nojs.locator('.art-card').count()==20
  await nojs.goto(URL+'/product/artes-face.html');assert await nojs.locator('.btn-buy').is_visible()
  fallback=await context.new_page();await fallback.add_init_script("const get=HTMLCanvasElement.prototype.getContext;HTMLCanvasElement.prototype.getContext=function(type,...args){return type.includes('webgl')?null:get.call(this,type,...args)}")
  await fallback.goto(URL+'/',wait_until='networkidle');await fallback.locator('[data-picker="room"]').click();await fallback.locator('[data-select-room="beautiful-mess"]').click();assert await fallback.locator('[data-world]').get_attribute('data-room')=='beautiful-mess';assert await fallback.locator('.room-art-fallback').is_visible()
  checks.extend(['no-JavaScript catalog and checkout','photographic controls without WebGL'])
  assert not runtime_requests,runtime_requests
  assert not errors,errors;assert not missing,missing
  result={'checks':checks,'canonicalProducts':routes,'responsive':responsive,'javascriptErrors':errors,'failedRequests':missing,'threeDimensionalRuntimeRequests':runtime_requests}
  (OUT/'browser-results.json').write_text(json.dumps(result,indent=2));print(json.dumps(result,indent=2),flush=True)
  await browser.close()
asyncio.run(main())
