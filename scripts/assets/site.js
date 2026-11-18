/* Art Supreme storefront enhancements. All catalog pages and purchase links work without JavaScript. */
(() => {
  'use strict';
  const catalog=window.ART_CATALOG;
  if(!catalog||typeof catalog!=='object')return;
  const base=document.body.dataset.base||'';
  const page=document.body.dataset.page;
  const all=Object.values(catalog);
  const escape=value=>String(value).replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
  const productUrl=p=>`${base}product/${encodeURIComponent(p.slug)}.html`;
  const imageUrl=(p,i=0)=>`${base}products/${encodeURIComponent(p.slug)}/${encodeURIComponent(p.images[i])}`;
  let saved=[];
  try {const stored=JSON.parse(localStorage.getItem('art-supreme-saved-works')||'[]');if(Array.isArray(stored))saved=[...new Set(stored.filter(slug=>typeof slug==='string'&&catalog[slug]))];}catch{}
  function updateSaved(){
    document.querySelectorAll('[data-saved-count]').forEach(el=>el.textContent=saved.length||'');
    document.querySelectorAll('[data-save]').forEach(button=>{
      const p=catalog[button.dataset.save];if(!p)return;
      const selected=saved.includes(p.slug);button.hidden=false;button.setAttribute('aria-pressed',String(selected));
      if(button.classList.contains('save-work')){button.textContent=selected?'★':'☆';button.setAttribute('aria-label',`${selected?'Unsave':'Save'} ${p.title}`);}
      else button.innerHTML=`${selected?'Saved to your collection':'Save this work'} <span>${selected?'★':'☆'}</span>`;
    });
  }
  function toggleSaved(slug){saved=saved.includes(slug)?saved.filter(x=>x!==slug):[...saved,slug];try{localStorage.setItem('art-supreme-saved-works',JSON.stringify(saved));}catch{}updateSaved();}
  function bindSaves(root=document){root.querySelectorAll('[data-save]').forEach(button=>{
    if(button.dataset.bound)return;
    button.dataset.bound='true';button.addEventListener('click',()=>{toggleSaved(button.dataset.save);if(page==='gallery'&&state.saved)renderGallery();});
  });updateSaved();}
  function card(p){const image=p.images.length?`<img src="${p.artwork?base+p.artwork.url:imageUrl(p)}" alt="${escape(p.title)} – catalog image" loading="lazy" decoding="async">`:'<span class="no-image">Image on request</span>';return `<article class="art-card"><button type="button" class="save-work" data-save="${escape(p.slug)}" aria-pressed="false" aria-label="Save ${escape(p.title)}">☆</button>${p.sold?'<span class="sold-mark">Sold</span>':''}<a class="card-image" href="${productUrl(p)}">${image}</a><div class="card-meta"><h3><a href="${productUrl(p)}">${escape(p.title)}</a></h3><span>${escape(p.priceLabel)}</span></div><p class="card-details">${escape(p.size==='Dimensions on request'?'Generative artwork':p.size)}${p.sold?' · Sold':''}</p></article>`;}
  const query=new URLSearchParams(location.search);
  let state={category:['abstract','figure','landscape'].includes(query.get('category'))?query.get('category'):'all',query:query.get('q')||'',sort:['low','high','name'].includes(query.get('sort'))?query.get('sort'):'curated',saved:query.get('saved')==='1'};
  function rememberGallery(){const params=new URLSearchParams();if(state.category!=='all')params.set('category',state.category);if(state.query)params.set('q',state.query);if(state.sort!=='curated')params.set('sort',state.sort);if(state.saved)params.set('saved','1');const suffix=params.size?'?'+params.toString():'';history.replaceState(null,'','gallery.html'+suffix);try{sessionStorage.setItem('art-supreme-gallery',suffix);}catch{}}
  function renderGallery(){
    let works=all.filter(p=>(state.category==='all'||p.category===state.category)&&p.title.toLocaleLowerCase().includes(state.query.toLocaleLowerCase())&&(!state.saved||saved.includes(p.slug)));
    if(state.sort==='low')works.sort((a,b)=>(a.price||Infinity)-(b.price||Infinity));
    else if(state.sort==='high')works.sort((a,b)=>(b.price||-1)-(a.price||-1));
    else if(state.sort==='name')works.sort((a,b)=>a.title.localeCompare(b.title));
    const grid=document.getElementById('gallery-grid');
    grid.innerHTML=works.length?works.map(card).join(''):`<div class="empty-gallery"><h2>${state.saved?'No saved works match.':'No works found.'}</h2><p>${state.saved?'Save a work using its star, or try another filter.':'Try another title or reset the filters.'}</p><button type="button" data-reset-gallery>Show all works</button></div>`;
    document.getElementById('gallery-result-count').textContent=`${works.length} work${works.length===1?'':'s'}${state.saved?' saved':''}`;
    document.querySelectorAll('[data-category]').forEach(button=>button.setAttribute('aria-pressed',String(button.dataset.category===state.category)));
    document.getElementById('saved-only').setAttribute('aria-pressed',String(state.saved));
    document.querySelector('[data-reset-gallery]')?.addEventListener('click',resetGallery);bindSaves(grid);rememberGallery();
  }
  function resetGallery(){state={category:'all',query:'',sort:'curated',saved:false};document.getElementById('gallery-search').value='';document.getElementById('gallery-sort').value='curated';renderGallery();}
  document.querySelectorAll('.saved-nav').forEach(el=>el.hidden=false);
  if(page==='gallery'){
    document.querySelector('.gallery-controls').hidden=false;document.getElementById('reset-filters').hidden=false;
    document.getElementById('gallery-search').value=state.query;document.getElementById('gallery-sort').value=state.sort;
    document.querySelectorAll('[data-category]').forEach(button=>button.addEventListener('click',()=>{state.category=button.dataset.category;renderGallery();}));
    document.getElementById('gallery-search').addEventListener('input',event=>{state.query=event.target.value;renderGallery();});
    document.getElementById('gallery-sort').addEventListener('change',event=>{state.sort=event.target.value;renderGallery();});
    document.getElementById('saved-only').addEventListener('click',()=>{state.saved=!state.saved;renderGallery();});
    document.getElementById('reset-filters').addEventListener('click',resetGallery);renderGallery();
  }
  if(page==='product'){
    const p=catalog[document.body.dataset.product];
    if(p){
      let selected=0;
      try{const suffix=sessionStorage.getItem('art-supreme-gallery')||'';if(!suffix||suffix.startsWith('?'))document.getElementById('back-to-gallery').href=base+'gallery.html'+suffix;}catch{}
      const thumbs=document.querySelector('.product-thumbs');if(thumbs&&p.images.length>1)thumbs.hidden=false;
      document.querySelectorAll('[data-image-index]').forEach(button=>button.addEventListener('click',()=>{selected=Number(button.dataset.imageIndex);const image=document.getElementById('product-main-image');image.src=imageUrl(p,selected);image.alt=`${p.title} – catalog image ${selected+1}`;document.querySelectorAll('[data-image-index]').forEach(x=>x.setAttribute('aria-pressed',String(x===button)));}));
      const open=document.getElementById('open-image');
      if(p.images.length&&open&&typeof HTMLDialogElement!=='undefined'){
        document.querySelector('.zoom-help').hidden=false;
        open.addEventListener('click',event=>{
          event.preventDefault();const previous=document.activeElement;const dialog=document.createElement('dialog');dialog.className='zoom-dialog';dialog.setAttribute('aria-label',p.title+' image viewer');
          dialog.innerHTML=`<div class="zoom-toolbar"><span>${escape(p.title)} / Image ${selected+1} of ${p.images.length}</span><button type="button">Close ×</button></div><img src="${imageUrl(p,selected)}" alt="${escape(p.title)} – catalog image ${selected+1}">`;
          document.body.append(dialog);dialog.querySelector('button').addEventListener('click',()=>dialog.close());const previousOverflow=document.body.style.overflow;
          dialog.addEventListener('close',()=>{dialog.remove();document.body.style.overflow=previousOverflow;previous?.focus();});
          dialog.addEventListener('click',e=>{if(e.target===dialog){const rect=dialog.getBoundingClientRect();if(e.clientX<rect.left||e.clientX>rect.right||e.clientY<rect.top||e.clientY>rect.bottom)dialog.close();}});
          document.body.style.overflow='hidden';dialog.showModal();
        });
      }
    }
  }
  bindSaves();
})();
