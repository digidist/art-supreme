/* Photographic room previews with independently selectable original artworks. */
const root=document.querySelector('[data-world]');
if(root)initializeWorld(root);
function initializeWorld(root){
 const catalog=window.ART_CATALOG||{};
 const base=document.body.dataset.base||'';
 const works=Object.values(catalog).filter(p=>p.artwork);
 if(!works.length)return;
 const rooms=[
  {id:'warm-apartment',name:'Warm apartment',mood:'Golden afternoon light',aspect:1586/992,x:.685,y:.275,width:.39},
  {id:'collectors-room',name:"Collector’s room",mood:'A warm evening glow',aspect:2,x:.69,y:.28,width:.33},
  {id:'beautiful-mess',name:'Beautiful mess',mood:'Soft daylight, lived-in charm',aspect:1586/992,x:.685,y:.28,width:.38}
 ];
 const $=selector=>root.querySelector(selector);
 const stage=$('[data-room-stage]'),status=$('[data-world-status]');
 const isProduct=document.body.dataset.page==='product';
 const reduced=matchMedia('(prefers-reduced-motion: reduce)').matches;
 const storageKey='art-supreme-world-v1';
 const productUrl=p=>`${base}product/${encodeURIComponent(p.slug)}.html`;
 const roomImage=id=>new URL(`${base}assets/rooms/${id}.webp`,document.baseURI).href;
 const escape=value=>String(value).replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
 let stored={};try{stored=JSON.parse(localStorage.getItem(storageKey)||'{}')||{};}catch{}
 let art=catalog[root.dataset.initialArt]||works[0];
 let requestedArt=art;
 let artworkRequest=0;
 const preparedArtwork=new Map();
 let room=rooms.find(r=>r.id===stored.room)||rooms[0];
 let frame=['black','oak','none'].includes(stored.frame)?stored.frame:'black';
 let view='room',picker=null,pickerReturnY=null;
 const sceneUpdate=()=>layoutArtwork();
 let feedbackTimer;
 root.dataset.renderer='photograph';root.dataset.scene='photograph';root.setAttribute('aria-busy','false');
 const persist=(work=art)=>{try{localStorage.setItem(storageKey,JSON.stringify({art:work.slug,room:room.id,frame}));}catch{}};
 function feedback(message,timeout=5000){clearTimeout(feedbackTimer);status.textContent=message;if(timeout)feedbackTimer=setTimeout(()=>status.textContent='',timeout);}
 function sync(){
  root.dataset.art=art.slug;root.dataset.room=room.id;root.dataset.frame=frame;
  $('[data-art-name]').textContent=art.title;
  $('[data-art-thumb]').src=base+art.artwork.url;
  $('[data-room-name]').textContent=room.name;
  $('[data-room-mood]').textContent=room.mood;
  $('[data-room-thumb]').src=`${base}assets/rooms/${room.id}.webp`;
  $('[data-world-caption]').textContent=`${art.title} · ${art.priceLabel}${art.sold?' · Sold':''}`;
  $('[data-world-link]').href=productUrl(art);
  const hotspot=$('[data-art-hotspot]');if(hotspot){hotspot.href=productUrl(art);hotspot.setAttribute('aria-label',`View ${art.title}`);const label=hotspot.querySelector('[data-art-label]');if(label)label.textContent=art.title+' ↗';}
  const photo=$('.room-photo');photo.style.backgroundImage=`url("${base}assets/rooms/${room.id}.webp")`;
  root.style.setProperty('--room-light-image',`url("${roomImage(room.id)}")`);
  const fallback=$('.room-art-fallback');fallback.href=productUrl(art);fallback.setAttribute('aria-label',`View ${art.title}`);
  fallback.style.aspectRatio=String(art.artwork.width/art.artwork.height);
  fallback.querySelector('img').src=base+art.artwork.url;
  fallback.querySelector('img').alt=`${art.title} in ${room.name} – room visualization`;
  root.classList.toggle('artwork-only',view==='artwork');
  requestAnimationFrame(layoutArtwork);
  root.querySelectorAll('[data-view-mode]').forEach(b=>b.setAttribute('aria-pressed',String(b.dataset.viewMode===view)));
 }
 function prepareArtwork(work){
  if(!preparedArtwork.has(work.slug)){
   const src=base+work.artwork.url;
   const image=new Image();
   const thumbnail=new Image();
   thumbnail.dataset.artThumb='';
   thumbnail.alt='';
   image.src=thumbnail.src=src;
   const preparation=Promise.all([image.decode(),thumbnail.decode()]).then(()=>({image,thumbnail}));
   preparedArtwork.set(work.slug,preparation);
   preparation.catch(()=>{
    if(preparedArtwork.get(work.slug)===preparation)preparedArtwork.delete(work.slug);
   });
  }
  return preparedArtwork.get(work.slug);
 }
 function warmNearbyArtwork(){
  if(isProduct)return;
  const index=works.indexOf(art);
  const nearby=[0,1,2,-1].map(offset=>works[(index+offset+works.length)%works.length]);
  for(const slug of preparedArtwork.keys()){
   if(!nearby.some(work=>work.slug===slug))preparedArtwork.delete(slug);
  }
  for(const work of nearby.slice(1))prepareArtwork(work);
 }
 async function selectArt(slug,{navigate=true}={}){
  const p=catalog[slug];if(!p?.artwork)return;
  requestedArt=p;
  const request=++artworkRequest;
  if(isProduct&&navigate&&p.slug!==root.dataset.initialArt){persist(p);location.assign(productUrl(p));return;}
  feedback('');
  root.setAttribute('aria-busy',String(p!==art));
  if(p===art){persist();warmNearbyArtwork();return;}
  try{
   const {image,thumbnail}=await prepareArtwork(p);
   // A slower request must never overwrite a more recent choice.
   if(request!==artworkRequest)return;
   $('.room-art-fallback img').replaceWith(image);
   $('[data-art-thumb]').replaceWith(thumbnail);
   art=p;
   persist();
   sync();
   layoutArtwork();
   if(!reduced){
    image.getAnimations().forEach(animation=>animation.cancel());
    image.animate([{opacity:.78},{opacity:1}],{duration:170,easing:'ease-out'});
   }
   if(picker==='artwork')renderPicker();
   warmNearbyArtwork();
  }catch{
   if(request!==artworkRequest)return;
   requestedArt=art;
   feedback('Couldn’t load that artwork. Try again.');
   for(const slug of preparedArtwork.keys()){
    if(slug!==art.slug)preparedArtwork.delete(slug);
   }
  }finally{
   if(request===artworkRequest)root.setAttribute('aria-busy','false');
  }
 }
 function selectRoom(id){const next=rooms.find(r=>r.id===id);if(!next)return;room=next;persist();sync();sceneUpdate();closePicker();}
 function nextWorld(){
  const options=rooms.filter(r=>r.id!==room.id);room=options[Math.floor(Math.random()*options.length)];
  persist();view='room';sync();closePicker();
  if(!isProduct){
   const other=works.filter(p=>p!==requestedArt);
   if(other.length)selectArt(other[Math.floor(Math.random()*other.length)].slug);
  }
 }
 function renderPicker(){
  $('[data-picker-title]').textContent={artwork:'Find your next work',room:'Choose your world',customize:'Make yourself at home'}[picker];
  const content=$('[data-picker-content]');
  if(picker==='artwork')content.innerHTML=`<div class="art-picker-grid">${works.map(p=>`<button type="button" class="picker-art" data-select-art="${p.slug}" aria-pressed="${p.slug===art.slug}"><span class="picker-image"><img src="${base+p.artwork.url}" alt="" loading="lazy"></span><span class="picker-details"><span>${escape(p.title)}</span><small>${escape(p.priceLabel)}${p.sold?' · Sold':''}</small></span><span class="picker-check" aria-hidden="true">✓</span></button>`).join('')}</div>`;
  else if(picker==='room')content.innerHTML=`<div class="room-picker-grid">${rooms.map(r=>`<button type="button" class="picker-room" data-select-room="${r.id}" aria-pressed="${room.id===r.id}"><span class="room-preview"><img src="${base}assets/rooms/${r.id}.webp" alt="" loading="lazy"></span><span class="room-option-info"><strong>${escape(r.name)}</strong><small>${escape(r.mood)}</small></span><span class="picker-check" aria-hidden="true">✓</span></button>`).join('')}</div>`;
  else content.innerHTML=`<div class="customize-row"><span>PREVIEW FRAME</span>${['black','oak','none'].map(f=>`<button type="button" class="frame-choice" data-frame="${f}" aria-pressed="${f===frame}"><i class="frame-swatch ${f}" aria-hidden="true"></i>${{black:'Black',oak:'Oak',none:'None'}[f]}</button>`).join('')}</div><p class="customize-note">A visual preview. Confirm framing and dimensions in the artwork details.</p><a class="customize-art-link" href="${productUrl(art)}">View artwork details ↗</a>`;
 }
 function openPicker(kind){
  if(picker===kind){closePicker();return;}
  if(!picker&&matchMedia('(max-width:760px)').matches)pickerReturnY=window.scrollY;
  picker=kind;renderPicker();$('#world-picker').hidden=false;
  root.querySelectorAll('[data-picker]').forEach(b=>b.setAttribute('aria-expanded',String(b.dataset.picker===kind)));
  $('#world-picker').querySelector('button')?.focus({preventScroll:true});
  if(matchMedia('(max-width:760px)').matches)requestAnimationFrame(()=>{
   // The choices have their own space; return to the preview when they close.
   $('#world-picker').scrollIntoView({block:'start',behavior:reduced?'instant':'smooth'});
  });
 }
 function closePicker({restore=false,restoreScroll=true}={}){
  const previous=picker,returnY=pickerReturnY;picker=null;pickerReturnY=null;
  $('#world-picker').hidden=true;root.querySelectorAll('[data-picker]').forEach(b=>b.setAttribute('aria-expanded','false'));
  if(restore&&previous)$(`[data-picker="${previous}"]`)?.focus({preventScroll:true});
  if(previous&&restoreScroll&&returnY!==null&&matchMedia('(max-width:760px)').matches)requestAnimationFrame(()=>window.scrollTo({top:returnY,behavior:reduced?'instant':'smooth'}));
 }
 root.addEventListener('click',e=>{
  const b=e.target.closest('button');if(!b)return;
  if(b.dataset.picker){openPicker(b.dataset.picker);return;}
  if(b.hasAttribute('data-close-picker')){closePicker({restore:true});return;}
  if(b.dataset.artStep){const i=works.indexOf(requestedArt);selectArt(works[(i+Number(b.dataset.artStep)+works.length)%works.length].slug);return;}
  if(b.dataset.selectArt){selectArt(b.dataset.selectArt);closePicker();return;}
  if(b.dataset.selectRoom){selectRoom(b.dataset.selectRoom);return;}
  if(b.dataset.frame){frame=b.dataset.frame;persist();sync();sceneUpdate();renderPicker();return;}
  if(b.hasAttribute('data-new-world')){nextWorld();return;}
  if(b.dataset.viewMode){view=b.dataset.viewMode;sync();}
 });
 document.addEventListener('keydown',e=>{if(e.key==='Escape'&&picker){e.preventDefault();closePicker({restore:true});}});
 document.addEventListener('pointerdown',e=>{if(picker&&!e.target.closest('.world-controls'))closePicker({restoreScroll:false});});
 document.querySelectorAll('[data-try-art]').forEach(a=>a.addEventListener('click',()=>selectArt(a.dataset.tryArt,{navigate:false})));
 document.querySelectorAll('[data-next-world]').forEach(a=>a.addEventListener('click',()=>nextWorld()));
 document.querySelectorAll('[data-open-worlds]').forEach(a=>a.addEventListener('click',e=>{if(document.body.dataset.page==='index'){e.preventDefault();root.scrollIntoView({behavior:reduced?'instant':'smooth'});openPicker('room');}}));
 root.classList.add('controls-active');sync();
 matchMedia('(max-width:760px)').addEventListener('change',()=>sync());
 // The photograph and the original catalog artwork stay separate. No 3D runtime.
 function layoutArtwork(){
  const image=$('.room-art-fallback'),w=stage.clientWidth,h=stage.clientHeight;
  if(!w||!h)return;
  const controls=$('.world-controls');controls.style.setProperty('--picker-space',Math.max(160,controls.offsetTop-18)+'px');
  const aspect=art.artwork.width/art.artwork.height,narrow=w/h<1.2;
  const maxWidth=w*(view==='artwork'?.86:narrow?.7:room.width);
  const maxHeight=h*(view==='artwork'?.59:.4);
  const rim=view==='artwork'||frame==='none'?0:Math.max(3,Math.min(7,maxWidth*.011));
  const contentWidth=Math.min(maxWidth-2*rim,(maxHeight-2*rim)*aspect);
  const width=contentWidth+2*rim,height=contentWidth/aspect+2*rim;
  const cx=view==='artwork'||narrow?w*.5:w*room.x,cy=h*(view==='artwork'?.39:room.y);
  image.style.left=(cx-width/2)+'px';image.style.top=(cy-height/2)+'px';
  image.style.width=width+'px';image.style.height=height+'px';
  image.style.setProperty('--frame-size',rim+'px');
  image.style.setProperty('--frame-depth',Math.max(2,rim)+'px');
  alignRoomLight(image,stage,room.aspect,getComputedStyle($('.room-photo')).backgroundPosition);
  const r=image.getBoundingClientRect(),outer=root.getBoundingClientRect();
  projectLabel({x:r.right-outer.left,y:r.top-outer.top+r.height*.34,visible:view==='room'});
 }
 // Sample the photograph at the exact wall position occupied by the artwork.
 // Only this presentation layer receives room light; the source image is untouched.
 function alignRoomLight(frameElement,roomElement,aspect,position='50% 50%'){
  const w=roomElement.clientWidth,h=roomElement.clientHeight;
  const coverWidth=Math.max(w,h*aspect),coverHeight=coverWidth/aspect;
  const [px=50,py=50]=position.split(' ').map(parseFloat);
  const frameBox=frameElement.getBoundingClientRect(),roomBox=roomElement.getBoundingClientRect();
  const rim=parseFloat(getComputedStyle(frameElement).getPropertyValue('--frame-size'))||0;
  frameElement.style.setProperty('--light-size',`${coverWidth}px ${coverHeight}px`);
  frameElement.style.setProperty('--light-position',`${(w-coverWidth)*px/100-(frameBox.left-roomBox.left)-rim}px ${(h-coverHeight)*py/100-(frameBox.top-roomBox.top)-rim}px`);
 }
 const closing=document.querySelector('.closing-art');
 function lightClosingArtwork(){
  if(!closing)return;
  closing.style.setProperty('--frame-size',Math.max(3,Math.min(7,closing.clientWidth*.011))+'px');
  closing.style.setProperty('--room-light-image',`url("${roomImage('collectors-room')}")`);
  alignRoomLight(closing,closing.parentElement,2,getComputedStyle(document.querySelector('.closing-room-photo')).objectPosition);
 }
 const observer=new ResizeObserver(()=>{layoutArtwork();lightClosingArtwork();});observer.observe(stage);
 if(closing)observer.observe(closing.parentElement);
 window.addEventListener('pagehide',()=>observer.disconnect(),{once:true});
 function projectLabel({x,y,visible=true}){
  const hotspot=$('[data-art-hotspot]'),leader=$('[data-room-leader]');if(!hotspot)return;
  hotspot.hidden=!visible;if(leader)leader.toggleAttribute('hidden',!visible);if(!visible)return;
  hotspot.style.left=x+'px';hotspot.style.top=y+'px';
  const label=hotspot.querySelector('[data-art-label]'),labelWidth=label?.getBoundingClientRect().width||150;
  const left=x+labelWidth+50>root.clientWidth-16;
  hotspot.classList.toggle('label-left',left);
  const sign=left?-1:1,relativeY=y-stage.offsetTop;
  const rise=Math.max(12,Math.min(stage.clientWidth<650?54:34,relativeY-26));if(label)label.style.top=(-rise-15)+'px';
  const path=$('[data-leader-line]');if(path)path.setAttribute('d',`M ${x} ${y} L ${x+sign*24} ${y-rise} L ${x+sign*32} ${y-rise}`);
 }
 layoutArtwork();
 if(!isProduct&&catalog[stored.art]?.artwork&&stored.art!==art.slug){
  selectArt(stored.art);
 }else{
  const initialRequest=artworkRequest;
  $('.room-art-fallback img').decode().catch(()=>{}).then(()=>{
   if(initialRequest===artworkRequest)warmNearbyArtwork();
  });
 }
}
