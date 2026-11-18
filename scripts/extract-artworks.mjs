#!/usr/bin/env node
/** Reproduce the room-viewer textures using exact crops of the original catalog files.
 * Requires ImageMagick (`magick`). Never edits or repaints the catalog originals.
 */
import fs from 'node:fs';
import path from 'node:path';
import {fileURLToPath} from 'node:url';
import {spawnSync} from 'node:child_process';
const root=fileURLToPath(new URL('../',import.meta.url));
const assets=path.join(root,'scripts/assets');
const crops=JSON.parse(fs.readFileSync(path.join(assets,'artwork-crops.json'),'utf8'));
fs.mkdirSync(path.join(assets,'artworks'),{recursive:true});
for(const [slug,crop] of Object.entries(crops)){
 const {source,x,y,width,height,sourceWidth,sourceHeight}=crop;
 if(![x,y,width,height,sourceWidth,sourceHeight].every(Number.isInteger)||x<0||y<0||width<1||height<1||x+width>sourceWidth||y+height>sourceHeight)throw new Error(`Invalid crop for ${slug}`);
 const input=path.join(root,'site/products',slug,source);
 const output=path.join(assets,'artworks',slug+'.webp');
 const result=spawnSync('magick',[input,'-crop',`${width}x${height}+${x}+${y}`,'+repage','-define','webp:lossless=true',output],{stdio:'inherit'});
 if(result.error)throw result.error;
 if(result.status!==0)throw new Error(`Artwork extraction failed for ${slug}`);
 console.log(`Extracted ${slug}, ${width} × ${height} original pixels.`);
}
