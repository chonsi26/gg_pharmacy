// ─────────────────────────────------------------------------------------------
// Saves every Watsons product image used by watsons_products_4.php
// (batch 4: links 301–400).
//
// HOW TO USE
//  1. In Chrome/Edge open this page (any image on that host works):
//       https://medias.watsons.com.ph/publishing/TWYNSTA%20TAB%2080MG_5MG%2030%27S_Image1_10087915-fdZWRVGg-zoom.png?version=1772012810
//  2. Press F12 -> Console. If asked, type  allow pasting  and press Enter.
//  3. Paste this whole script and press Enter.
//  4. If the browser asks to "allow multiple downloads", click Allow.
//  5. Move all downloaded files into:  database/seeders/data/images/
//     (keep their original names - the seeder finds them by file name)
//  6. Run:  php artisan db:seed
//
// It must be run on medias.watsons.com.ph so the requests are same-origin.
//
// 100 images, one per product. #301 and #302 are the "-zoom" images taken from
// the product pages themselves (verified).
//
// #303–#400 are UNVERIFIED (pages not opened): they follow the usual Watsons
// naming pattern and many may fail with HTTP 404. For any that fail, open the
// product page, right-click the image and save it by hand into
// database/seeders/data/images/ (the seeder finds it by the 8-digit product
// code in the file name). The products still seed fine without an image.
// ─────────────────────────────────────────────────────────────────────────────
const urls = [
  // 301 Twynsta Telmisartan 80mg + Amlodipine 5mg Tablet
  "https://medias.watsons.com.ph/publishing/TWYNSTA%20TAB%2080MG_5MG%2030%27S_Image1_10087915-fdZWRVGg-zoom.png?version=1772012810",
  // 302 Tapdin Methimazole 20mg 1 Tablet
  "https://medias.watsons.com.ph/publishing/WTCPH-10061639-front-zoom.jpg?version=1734069592",
  // 303 Ivi Premium Collagen Powder Drink 7g x 1 Sachet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10088176-front-zoom.jpg",
  // 304 Allerta 10mg Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50043985-front-zoom.jpg",
  // 305 pH Care Feminine Wash Floral Clean 50ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10088078-front-zoom.jpg",
  // 306 Firialta 10mg Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50057103-front-zoom.jpg",
  // 307 Allerkast 10mg/5mg Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50044681-front-zoom.jpg",
  // 308 Zarnat Losartan Potassium 50mg 1 Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10099242-front-zoom.jpg",
  // 309 Luxe Organix Retinol Gluta Whitening Serum Lotion 350ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50044680-front-zoom.jpg",
  // 310 Gynepro Ultra Protection Feminine Wash 60ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10050586-front-zoom.jpg",
  // 311 Skinoren Azelaic Acid 150mg/g Topical Gel 30g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10098582-front-zoom.jpg",
  // 312 Equal Gold No Calories Sweetener 50 Sticks x 40g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50054682-front-zoom.jpg",
  // 313 Pneumotyl Acetylcysteine 600mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50031343-front-zoom.jpg",
  // 314 Victus Quetiapine 300mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50031132-front-zoom.jpg",
  // 315 Luxe Organix Niacinamide Alpha Arbutin Whitening Serum Lotion 350ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50044682-front-zoom.jpg",
  // 316 Ritemed Bisoprolol Fumarate 5mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10097614-front-zoom.jpg",
  // 317 Watsons Generics Gliclazide 30mg 1 Modified-Release Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10100665-front-zoom.jpg",
  // 318 Janumet Sitagliptin 50mg/Metformin 1g 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10067061-front-zoom.jpg",
  // 319 Ritemed Fenofibrate 200mg Capsule  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50056288-front-zoom.jpg",
  // 320 Bewell-C Zinc Protect Non-Acidic Vitamin C with Zinc Capsule  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50027103-front-zoom.jpg",
  // 321 Clusivol Multivitamins + Zinc + Copper 1 Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50010275-front-zoom.jpg",
  // 322 Ritemed Rosuvastatin 20mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50020333-front-zoom.jpg",
  // 323 Glipgludin 100mg/1g Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50048205-front-zoom.jpg",
  // 324 Maxi-Peel Exfoliant Solution #2 60ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10010087-front-zoom.jpg",
  // 325 Virlix Cetirizine 10mg Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10002974-front-zoom.jpg",
  // 326 Moriamin Multivitamins + Amino Acids  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10000507-front-zoom.jpg",
  // 327 Telarb Telmisartan + Amlodipine 40mg/5mg Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50053486-front-zoom.jpg",
  // 328 Lavida Levodopa 250mg + Carbidopa 25mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10100975-front-zoom.jpg",
  // 329 Liverguard Silymarin + Lecithin  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10101256-front-zoom.jpg",
  // 330 Maxi-Peel Concealing Cream Fair 10g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10079890-front-zoom.jpg",
  // 331 Tempra Forte Paracetamol 500mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10002832-front-zoom.jpg",
  // 332 Natrapharm Atorvastatin 40mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10091112-front-zoom.jpg",
  // 333 Therabloc Atenolol 100mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10041558-front-zoom.jpg",
  // 334 Celeteque Hydration Gentle Exfoliating Facial Wash 100ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10081421-front-zoom.jpg",
  // 335 ATC Health Fish Oil 1000mg 1 Box Softgel Capsule  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50013040-front-zoom.jpg",
  // 336 Ritemed Metformin Hydrochloride 500mg 1 Sustained-Release Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50035338-front-zoom.jpg",
  // 337 Carvid Carvedilol 25mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10085157-front-zoom.jpg",
  // 338 Dermaid Sterilized Gauze Swab  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50051092-front-zoom.jpg",
  // 339 Glyxera Sitagliptin 100mg Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50055236-front-zoom.jpg",
  // 340 Besprin Aspirin 100mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50017691-front-zoom.jpg",
  // 341 Buscopan Hyoscine N-Butylbromide 10mg Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10079422-front-zoom.jpg",
  // 342 Ator Atorvastatin Calcium 20mg 1 Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50006517-front-zoom.jpg",
  // 343 Propan TLC Syrup 250ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10096618-front-zoom.jpg",
  // 344 Pharex B-Complex 400s Tipid Pack  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50002653-front-zoom.jpg",
  // 345 Euthyrox Levothyroxine Sodium 150mcg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10071196-front-zoom.jpg",
  // 346 Mepresone Methylprednisolone 16mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10097018-front-zoom.jpg",
  // 347 Co-Altria Montelukast Na + Levocetirizine diHCl 10mg/5mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10087728-front-zoom.jpg",
  // 348 Noston Potassium Citrate Antiurolithic  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50054786-front-zoom.jpg",
  // 349 Venocare Diosmin + Hesperidin 450mg/50mg Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50052217-front-zoom.jpg",
  // 350 Sleepwell Melatonin 3mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10082748-front-zoom.jpg",
  // 351 Ritemed Carvedilol 6.25mg Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50051982-front-zoom.jpg",
  // 352 Skelan Naproxen Sodium 220mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10070008-front-zoom.jpg",
  // 353 Fortima Film-Coated Tablet 20 Pieces  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50061399-front-zoom.jpg",
  // 354 Duvaprine Isoxsuprine Hydrochloride 10mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50037381-front-zoom.jpg",
  // 355 Difflam Benzydamine Hydrochloride 3mg Lozenge x 1  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10003600-front-zoom.jpg",
  // 356 Watsons Generics Pregabalin 75mg Capsule  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50005465-front-zoom.jpg",
  // 357 Rosalta Rosuvastatin 10mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50018958-front-zoom.jpg",
  // 358 Micardis Plus Telmisartan 40mg + Hydrochlorothiazide 12.5mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10087113-front-zoom.jpg",
  // 359 Maalox Aluminum Hydroxide 200mg Chewable Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50007214-front-zoom.jpg",
  // 360 Myonal Eperisone Hydrochloride 50mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10002468-front-zoom.jpg",
  // 361 Zilden Sildenafil Citrate 50mg 1 Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10098936-front-zoom.jpg",
  // 362 Mounjaro KwikPen 5mg/0.6ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50057680-front-zoom.jpg",
  // 363 Aforbes Amlodipine 5mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10072481-front-zoom.jpg",
  // 364 Watsons Generics Ibuprofen 200mg Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10095466-front-zoom.jpg",
  // 365 Cecon 500mg Chewable 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10001933-front-zoom.jpg",
  // 366 Trajenta Linagliptin 5mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10090862-front-zoom.jpg",
  // 367 Dilatair Doxofylline 200mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10093564-front-zoom.jpg",
  // 368 Nitroxel Nebivolol 2.5mg Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50052598-front-zoom.jpg",
  // 369 Watsons Generics Loratadine 10mg Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10096725-front-zoom.jpg",
  // 370 Bactiv Co-Amoxiclav 625mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10096527-front-zoom.jpg",
  // 371 Hemarate Tablet 12+3  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50001941-front-zoom.jpg",
  // 372 Proglin Vildagliptin + Metformin 50mg/850mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10096686-front-zoom.jpg",
  // 373 Renalog Ketoanalogues + Essential Amino Acids 600mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50009031-front-zoom.jpg",
  // 374 Milcu Underarm & Foot Deodorant Powder 80g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10016939-front-zoom.jpg",
  // 375 Natrapharm Atorvastatin 80mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10091113-front-zoom.jpg",
  // 376 Avator Atorvastatin Calcium 40mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10099238-front-zoom.jpg",
  // 377 Dolcet Tramadol + Paracetamol 37.5mg/325mg Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10083915-front-zoom.jpg",
  // 378 Watsons Pregnancy Test Cassette 1s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50034976-front-zoom.jpg",
  // 379 Ritemed Cefalexin 500mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10094552-front-zoom.jpg",
  // 380 Telarb Plus Telmisartan + Hydrochlorothiazide 80mg/12.5mg Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50055718-front-zoom.jpg",
  // 381 Glimesyn Glimepiride 3mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50017701-front-zoom.jpg",
  // 382 Lipway Fenofibrate 250mg 1 Sustained-Release Capsule  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10047741-front-zoom.jpg",
  // 383 TMZ MR Trimetazidine HCl 35mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10100938-front-zoom.jpg",
  // 384 Levomont Montelukast (as Sodium) + Levocetirizine Hydrochloride 10mg/5mg 1 Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50008214-front-zoom.jpg",
  // 385 Valcamp Amlodipine Besilate 5mg + Valsartan 160mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50001545-front-zoom.jpg",
  // 386 Abdin Aripiprazole 10mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10099046-front-zoom.jpg",
  // 387 Allurase Allopurinol 100mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10102028-front-zoom.jpg",
  // 388 Ritemed Betahistine 24mg Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50051756-front-zoom.jpg",
  // 389 Celeteque Dermo Science Hydration Travel Pack  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10076266-front-zoom.jpg",
  // 390 Myra E Fresh Glow Glow Whitening Facial Moisturizer 40ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50005888-front-zoom.jpg",
  // 391 Watsons Generics Bacillus Clausii 2 Billion/5ml Oral Suspension  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50049723-front-zoom.jpg",
  // 392 Sovit Ascorbic Acid + Zinc + Cholecalciferol (Vitamin D3) 1 Capsule  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50038828-front-zoom.jpg",
  // 393 Stresstabs Multivitamins + Iron 30 Tablets  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50000186-front-zoom.jpg",
  // 394 pH Care Feminine Wash Cooling Comfort 50ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10088082-front-zoom.jpg",
  // 395 Osteogard Glucosamine HCl + Chondroitin Sulfate 1s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50017053-front-zoom.jpg",
  // 396 Myra E Hydrating Glow Body Lotion 200ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50059014-front-zoom.jpg",
  // 397 Watsons Generics Metoprolol Tartrate 50mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10096371-front-zoom.jpg",
  // 398 Hytaz Hydrochlorothiazide 25mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10055508-front-zoom.jpg",
  // 399 Heraclene Dibencozide 1mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10002216-front-zoom.jpg",
  // 400 Watsons Generics Vitamin A  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50027652-front-zoom.jpg"
];

(async () => {
  let ok = 0, failed = [];
  for (const u of urls) {
    try {
      const res = await fetch(u, { credentials: 'omit' });
      if (!res.ok) throw new Error('HTTP ' + res.status);
      const blob = await res.blob();
      const a = document.createElement('a');
      a.href = URL.createObjectURL(blob);
      a.download = decodeURIComponent(new URL(u).pathname.split('/').pop());
      document.body.appendChild(a);
      a.click();
      a.remove();
      setTimeout(() => URL.revokeObjectURL(a.href), 10000);
      ok++;
      console.log(`[${ok}/${urls.length}] ${a.download}`);
      await new Promise(r => setTimeout(r, 450));
    } catch (e) {
      failed.push(u);
      console.warn('FAILED', u, e.message);
    }
  }
  console.log(`Done. ${ok} saved, ${failed.length} failed.`, failed);
})();