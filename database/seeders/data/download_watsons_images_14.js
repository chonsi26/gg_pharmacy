// ─────────────────────────────────────────────────────────────────────────────
// Saves every Watsons product image used by watsons_products_14.php
// (batch 14: links 1301–1400).
//
// HOW TO USE
//  1. In Chrome/Edge open this page (any image on that host works):
//       https://medias.watsons.com.ph/publishing/WTCPH-10100943-front-zoom.jpg
//  2. Press F12 -> Console. If asked, type  allow pasting  and press Enter.
//  3. Paste this whole script and press Enter.
//  4. If the browser asks to "allow multiple downloads", click Allow.
//  5. Move all downloaded files into:  database/seeders/data/images/
//     (keep their original names - the seeder finds them by file name)
//  6. Run:  php artisan db:seed
//
// It must be run on medias.watsons.com.ph so the requests are same-origin.
//
// 100 images, one per product (#1301–#1400).
//   All are UNVERIFIED: the product pages did not show image links, so they follow the usual
//   WTCPH-<product code>-front-zoom.jpg pattern and many may fail with HTTP 404 (Watsons often appends a
//   ?version= suffix or a random token to the real file name). For any that fail, open the product page,
//   right-click the image and save it by hand into database/seeders/data/images/ (the seeder finds it by the
//   8-digit product code in the file name). The products still seed fine without an image.
// ─────────────────────────────────────────────────────────────────────────────
const urls = [
  // 1301 Cetaphil Moisturizing Lotion Body and Face 118ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50010870-front-zoom.jpg",
  // 1302 Ensure Gold Vanilla 400g Powdered Milk Adult Supplement  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10074846-front-zoom.jpg",
  // 1303 Charmee Feminine Pads Cottony with Wings 8+1 Free Pad  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10042377-front-zoom.jpg",
  // 1304 Clean & Clear Oil Control Film 60s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10003406-front-zoom.jpg",
  // 1305 Mosbeau Placenta White Advanced Tablet 650mg x120 Tablets  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10090543-front-zoom.jpg",
  // 1306 Pure Form Ultra Micronized Creatine Monohydrate Unflavored Powder 300g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50054652-front-zoom.jpg",
  // 1307 Co-Ivyzar Irbesartan + Hydrochlorothiazide 300mg/12.5mg (Sold Per Piece) (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50053054-front-zoom.jpg",
  // 1308 Escivex 5mg (Sold Per Piece) (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50037975-front-zoom.jpg",
  // 1309 Ritemed Sitagliptin 50mg 1 Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50046716-front-zoom.jpg",
  // 1310 Duobloc Carvedilol 25mg 1 Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50004496-front-zoom.jpg",
  // 1311 Nuwhite S-Acetyl Glutathione with Marine Collagen, Sodium Ascorbate and Vitamin E Food Supplement 30 Capsules  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50016780-front-zoom.jpg",
  // 1312 Cetaphil Pro AD Derma Skin Restoring Wash 295ml for Eczema-Prone Skin / Atopic Dermatitis  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10086497-front-zoom.jpg",
  // 1313 Betadine Feminine Wash Lavender Mist 250ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10101051-front-zoom.jpg",
  // 1314 Modess Maternity Extra Long Sanitary Napkin 7s Med-Heavy Flow (Post-Partum)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10003672-front-zoom.jpg",
  // 1315 Lifecaps Mangosteen and Ampalaya 400mg Food Supplement (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50011804-front-zoom.jpg",
  // 1316 Ator Atorvastatin Calcium 10mg 1 Film-Coated Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50006516-front-zoom.jpg",
  // 1317 Norizec Glimepiride 2mg 1 Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10042653-front-zoom.jpg",
  // 1318 Duodart Dutasteride 0.5mg + Tamsulosin Hydrochloride 0.4mg 1 Capsule (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10087928-front-zoom.jpg",
  // 1319 Fixcom Rifampicin 150mg + Isoniazid 75mg + Pyrazinamide 400mg + Ethambutol 275mg 1 Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10064414-front-zoom.jpg",
  // 1320 Trihemic Multivitamins + Iron + Docusate Sodium 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10000955-front-zoom.jpg",
  // 1321 Vaseline Lip Therapy Rosy Lips with Petroleum Jelly 4.6g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50025756-front-zoom.jpg",
  // 1322 Watsons Sweet Dream Baby Bath 850ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50010151-front-zoom.jpg",
  // 1323 Watsons Nourish and Repair Jojoba and Shea Butter Conditioner 1L  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50000266-front-zoom.jpg",
  // 1324 Body Treats Wild Orchid Hand Soap 750ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10092697-front-zoom.jpg",
  // 1325 Fino Premium Touch Hair Mask 230g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50052641-front-zoom.jpg",
  // 1326 Belo Dewy Essence Sunscreen SPF50 PA 30ml (Buy 1 Take 1)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50052619-front-zoom.jpg",
  // 1327 AndroGel Testosterone 16.2mg/g (1.62% w/w) Transdermal Gel 88g (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50051140-front-zoom.jpg",
  // 1328 Nivea Pearl Radiant Micellar Water 200ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50050925-front-zoom.jpg",
  // 1329 Luseco Luseogliflozin 2.5mg 1 Film-Coated Tablet (Sold Per Piece) (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50042229-front-zoom.jpg",
  // 1330 Efficascent Oil Extreme 25ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10091152-front-zoom.jpg",
  // 1331 EQ Dry Disposable Baby Diapers S 40s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50026625-front-zoom.jpg",
  // 1332 Dr. Sensitive All Day No Sebum Blur Powder 25g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50012874-front-zoom.jpg",
  // 1333 Imodium 2mg 4s Diarrhea Medicine (Loperamide)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50000003-front-zoom.jpg",
  // 1334 I-Max Metformin Hydrochloride 500mg 1 Caplet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10029039-front-zoom.jpg",
  // 1335 Ritemed Amoxicillin 500mg (Sold Per Piece) (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10030303-front-zoom.jpg",
  // 1336 Durex Condoms Invisible Extra Sensitive 3s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50018609-front-zoom.jpg",
  // 1337 Watsons Honey and Milk Cream Hand Soap 500ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10090312-front-zoom.jpg",
  // 1338 Safeguard Derm Protect Face & Body Wash Dry Itchy Skin Relief 570g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50060200-front-zoom.jpg",
  // 1339 Xerevo Clopidogrel 75mg Film-Coated Tablet 100s (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50052965-front-zoom.jpg",
  // 1340 Systane Hydration Preservative-Free  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50047895-front-zoom.jpg",
  // 1341 The Collagen Collagen Peptides Powder 300g Unflavored  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50036523-front-zoom.jpg",
  // 1342 Garnier Bright Complete Brightening Scrub 100ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10065206-front-zoom.jpg",
  // 1343 Cetaphil Daily Exfoliating Cleanser 178ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50011188-front-zoom.jpg",
  // 1344 Mepresone Methylprednisolone 4mg 1 Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10100762-front-zoom.jpg",
  // 1345 Co-Ax Co-Amoxiclav 625mg 1 Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10099204-front-zoom.jpg",
  // 1346 Tergecef Cefixime 200mg 1 Capsule (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10001026-front-zoom.jpg",
  // 1347 E-Z Lubricating Jelly 76g Tube  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50007454-front-zoom.jpg",
  // 1348 Ceelin Ascorbic Acid Drops 30ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10000157-front-zoom.jpg",
  // 1349 IVI Ready-to-Drink Collagen Apple 1000mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50008327-front-zoom.jpg",
  // 1350 Watsons Invigorating Wet Wipes Lavender Scented 3x10 Sheets  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10099001-front-zoom.jpg",
  // 1351 Deo-Nat Natural Mineral Deodorant Spray 100ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10087629-front-zoom.jpg",
  // 1352 Celeteque DermoScience Brightening Power Cream 50ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10088543-front-zoom.jpg",
  // 1353 CeraVe Facial Moisturizing Lotion 52ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50053258-front-zoom.jpg",
  // 1354 Ming's Pei Pa Koa Candy 60g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50045003-front-zoom.jpg",
  // 1355 Garnier Bright Complete Salicylic Acid Anti-Acne Serum 30ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50039336-front-zoom.jpg",
  // 1356 Eco Bath Mild & Gentle Extra Sensitive Bathing Wipes 10 Extra Large Wipes  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50037281-front-zoom.jpg",
  // 1357 Milk Essence Soothing Baby Lotion 850ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50033747-front-zoom.jpg",
  // 1358 Oral-B Precision Clean Refill 2s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50003149-front-zoom.jpg",
  // 1359 Ritemed Sildenafil 100mg 1 Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10090008-front-zoom.jpg",
  // 1360 Imigran Sumatriptan 50mg 1 Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10059101-front-zoom.jpg",
  // 1361 Sunburst Saw Palmetto 160mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10075319-front-zoom.jpg",
  // 1362 Ceelin Ascorbic Acid Syrup 120ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10000158-front-zoom.jpg",
  // 1363 Silka Papaya Soap 90g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10088248-front-zoom.jpg",
  // 1364 Garnier Micellar Water for Acne Twin Pack  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50059965-front-zoom.jpg",
  // 1365 Myox Tablet 90mg (Sold Per Piece) (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50045720-front-zoom.jpg",
  // 1366 Biogesic MLN 250mg Syrup 1 Bottle 60ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50046452-front-zoom.jpg",
  // 1367 Oral-B Floss Pick 75s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50040227-front-zoom.jpg",
  // 1368 ClearSight Food Supplement with Lutein + DHA (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50036655-front-zoom.jpg",
  // 1369 Efficascent Oil Efficascent Ointment 10g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50010223-front-zoom.jpg",
  // 1370 K-Glow Collagen Bright Whitening Soap 3x60g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50033293-front-zoom.jpg",
  // 1371 Rabegen Rabeprazole Sodium + Itopride Hydrochloride 20mg/150mg 1 Modified-Release Tablet (Sold Per Piece) (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50005506-front-zoom.jpg",
  // 1372 Difflam Lozenge Mint 8s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50009815-front-zoom.jpg",
  // 1373 Cetaphil Bright Healthy Radiance Brightening Night Comfort Cream  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50026804-front-zoom.jpg",
  // 1374 Diovan Valsartan 320mg 1 Capsule (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10084776-front-zoom.jpg",
  // 1375 Watsons Generics Cefuroxime Antibacterial Film-Coated Tablet 500mg (Sold Per Piece) (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10101078-front-zoom.jpg",
  // 1376 Liza Drospirenone + Ethinylestradiol 3mg/30mcg 21 Pills Box (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10092730-front-zoom.jpg",
  // 1377 Augmentin Amoxicillin 500mg + Clavulanic Acid 125mg 1 Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10072860-front-zoom.jpg",
  // 1378 Cataflam Diclofenac Potassium 50mg Sugar-Coated Tablet 1 Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10001923-front-zoom.jpg",
  // 1379 Carnicor L-Carnitine 330mg Tablet 1 Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10000971-front-zoom.jpg",
  // 1380 Save Our Skin Clindamycin with Vitamin E 1 (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10054978-front-zoom.jpg",
  // 1381 Cherifer Drops 30ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10001062-front-zoom.jpg",
  // 1382 Trust Ultra Thin Shower Fresh 3s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10101687-front-zoom.jpg",
  // 1383 Body Treats Firming Hydrogel Eye Patch 3 Sheets  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10099399-front-zoom.jpg",
  // 1384 Listerine Cool Mint Mouthwash 750ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10080559-front-zoom.jpg",
  // 1385 Watsons Sugar-Free Mint Breathe Strips  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10046389-front-zoom.jpg",
  // 1386 Physiogel Daily Moisture Therapy Cream 75ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10038245-front-zoom.jpg",
  // 1387 Luxe Organix Bio-Niacinamide TXA Boost Dark Spot Correcting Serum Treatment Hydrogel Mask 25g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50062758-front-zoom.jpg",
  // 1388 Vaseline Gluta-Hya PDRN Serum Burst Lotion 300ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50062864-front-zoom.jpg",
  // 1389 Luxe Organix Fragrances Golden Hour Eau de Toilette 100ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50054386-front-zoom.jpg",
  // 1390 Luxe Organix Premium Keratin Curl Define Essence 250ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50049631-front-zoom.jpg",
  // 1391 Luxe Organix Advanced Retinol + Bakuchiol Deep Wrinkle Eye Contour Overnight Cream 12g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50043252-front-zoom.jpg",
  // 1392 Mucoprotec FC Rebamipide 100mg Film-Coated Tablet (Sold Per Piece) (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50035904-front-zoom.jpg",
  // 1393 Babyflo Gentle Buds Plastic White 200s (2+1)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50024154-front-zoom.jpg",
  // 1394 Dermazole Ketoconazole 20mg/ml (2% w/v) Shampoo 50ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50004870-front-zoom.jpg",
  // 1395 Ventolin Salbutamol Sulfate 100mcg Inhaler 200 Doses (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10002941-front-zoom.jpg",
  // 1396 Ritemed Metoprolol 50mg 1 Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10090916-front-zoom.jpg",
  // 1397 Cardiosel Metoprolol Succinate 47.5mg 1 Extended-Release Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10089967-front-zoom.jpg",
  // 1398 Inspra Eplerenone 50mg 1 Film-Coated Tablet (Sold Per Piece) (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10101425-front-zoom.jpg",
  // 1399 Durex Condoms Extra Safe 3s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50006817-front-zoom.jpg",
  // 1400 Muconase Sodium Chloride 0.65% Nasal Spray 60ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10100363-front-zoom.jpg"
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