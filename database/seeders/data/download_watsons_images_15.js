// ─────────────────────────────────────────────────────────────────────────────
// Saves every Watsons product image used by watsons_products_15.php
// (batch 15: links 1401–1500).
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
// 100 images, one per product (#1401–#1500).
//   All are UNVERIFIED: the product pages did not show image links, so they follow the usual
//   WTCPH-<product code>-front-zoom.jpg pattern and many may fail with HTTP 404 (Watsons often appends a
//   ?version= suffix or a random token to the real file name). For any that fail, open the product page,
//   right-click the image and save it by hand into database/seeders/data/images/ (the seeder finds it by the
//   8-digit product code in the file name). The products still seed fine without an image.
// ─────────────────────────────────────────────────────────────────────────────
const urls = [
  // 1401 Tuseran Night 12.5mg 60ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50009297-front-zoom.jpg",
  // 1402 Ascof Lagundi for Kids 300mg/5ml Ponkan Flavored Syrup 120ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10030349-front-zoom.jpg",
  // 1403 K-CAB Film-Coated Tablet 50mg (Sold Per Piece) (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50042638-front-zoom.jpg",
  // 1404 Dove Sensitive Body Wash 1+1 650ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50043343-front-zoom.jpg",
  // 1405 Luxe Organix 2-in-1 Acne Rescue Mud Powder Overnight Pimple Drying 9g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50043209-front-zoom.jpg",
  // 1406 Dove Pink Beauty Bar Triples 135g x 3  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50026322-front-zoom.jpg",
  // 1407 Colgate Fresh Confidence Cooling Crystals Peppermint Ice Toothpaste 173g Twin Pack  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50013922-front-zoom.jpg",
  // 1408 Luxe Organix Bright Eyes Eye Cream 80% Galactomyces 15g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50014626-front-zoom.jpg",
  // 1409 Colgate Triple Action Anti-Cavity Family Toothpaste 120g Twin Pack  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50011767-front-zoom.jpg",
  // 1410 Modess All Night Extra Long with Wings Sanitary Napkin 10s Heavy Flow  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10099139-front-zoom.jpg",
  // 1411 Dove Radiant Care Deodorant Dry Serum 3% Niacinamide + 10x Vitamin C & E Dark Marks Reducer 40ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50007309-front-zoom.jpg",
  // 1412 Fisherman's Friend Menthol 3mg Sugar-Free Lozenge x 1 Pack  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10102459-front-zoom.jpg",
  // 1413 Salinase Spray 30ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10095482-front-zoom.jpg",
  // 1414 Watsons Brightening Scented Cream Body Wash 1L  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10089623-front-zoom.jpg",
  // 1415 Watsons Washproof Plaster 10s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10085815-front-zoom.jpg",
  // 1416 Strepsils Orange 8s (Buy 2 Get 25% Off)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50046459-front-zoom.jpg",
  // 1417 Qualisafe Urgent Transparent Wound Dressing Medium  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50055280-front-zoom.jpg",
  // 1418 Watsons Generics Omega-3 Oil Softgel Capsule 1400mg 30s Pack  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50053301-front-zoom.jpg",
  // 1419 Luxe Organix Hyaluron White Double Collagen Hydra Cooling Moisturizer 50g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50037445-front-zoom.jpg",
  // 1420 Med Guard Toothbrush 3-in-1 with Soft Curved Bristles 3s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10047801-front-zoom.jpg",
  // 1421 Nicorette Menthol Mint Nicotine Gum 4mg 15s - Quit Smoking  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50036789-front-zoom.jpg",
  // 1422 Belo Essentials Beauty Deo Shower 40ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10098090-front-zoom.jpg",
  // 1423 Aveeno Skin Relief Body Lotion 1L for Sensitive, Dry, Itchy Skin, Eczema, Atopic Dermatitis  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50030162-front-zoom.jpg",
  // 1424 Deo-Nat Papaya Mineral Deodorant Spray 100ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50029672-front-zoom.jpg",
  // 1425 Celcoxx Celecoxib 400mg (Sold Per Piece) (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10084207-front-zoom.jpg",
  // 1426 Exforge Amlodipine Besilate 5mg + Valsartan 80mg 1 Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10069178-front-zoom.jpg",
  // 1427 Nivea Sun Protect & Moisture Lotion with SPF 50 125ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10035378-front-zoom.jpg",
  // 1428 Montemax Montelukast Sodium 10mg 1 Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10064374-front-zoom.jpg",
  // 1429 Tempra Orange Syrup 120mg 120ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10030140-front-zoom.jpg",
  // 1430 pH Care Feminine Wash Delicate White 150ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10098518-front-zoom.jpg",
  // 1431 Nivea Body Milk Rich Nourishing 190ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10080472-front-zoom.jpg",
  // 1432 Watsons Generics Calcium Citrate + Vitamin D + Zinc + Copper + Manganese + Magnesium Tablet 10s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50062200-front-zoom.jpg",
  // 1433 Sofy Soft Tampon Heavy Flow 9s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50059568-front-zoom.jpg",
  // 1434 pH Care Anti-Chafe Cream 50ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50058894-front-zoom.jpg",
  // 1435 Dr. O Skin Lab Hypochlorous Skin Rescue Spray 0.65% Hypochlorous Acid Anti-Acne Mist 50ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50058401-front-zoom.jpg",
  // 1436 Luxe Organix Niacinamide + Bakuchiol + Retinol Velvet Shower Salt Scrub 320g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50042202-front-zoom.jpg",
  // 1437 Fern-C Plus Ascorbic Acid + Zinc 27+3 Capsules  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50040229-front-zoom.jpg",
  // 1438 Luxe Organix Whitening Repair Ultra Light Glow Micellar Makeup Remover Cleansing Wipes 30s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50038837-front-zoom.jpg",
  // 1439 iColor Organic Hair Dye Shampoo 25ml Black  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50011584-front-zoom.jpg",
  // 1440 Watsons Disposable Underwear Pure Cotton Ladies Extra Large 5s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10092355-front-zoom.jpg",
  // 1441 Combizar Losartan Potassium + Hydrochlorothiazide 50mg/12.5mg (Sold Per Piece) (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50001123-front-zoom.jpg",
  // 1442 Silka Papaya Facial Cleanser 150ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10093368-front-zoom.jpg",
  // 1443 Watsons Shea Butter Peach Scented Cream Hand Wash 500ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10090320-front-zoom.jpg",
  // 1444 Garnier Micellar Water with Argan Oil for Waterproof Makeup 125ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50003162-front-zoom.jpg",
  // 1445 Eczacort Hydrocortisone Cream 10g (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10089137-front-zoom.jpg",
  // 1446 Calci-Aid Calcium Carbonate 500mg 30 Capsules  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50010629-front-zoom.jpg",
  // 1447 Salonpas Pain Relief Patch 5s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10087412-front-zoom.jpg",
  // 1448 Muconase Nasal Spray 30ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10082683-front-zoom.jpg",
  // 1449 Sofy Soft Tampon Light to Regular Flow 10s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50059569-front-zoom.jpg",
  // 1450 Snow Caps Gold L-Glutathione 749mg 30s with Free Snow Skin Whitening Soap  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50055195-front-zoom.jpg",
  // 1451 Pure Form Pre + Probiotic for Gut Health Capsule  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50054660-front-zoom.jpg",
  // 1452 Celeteque DermoScience Brightening Micellar Milk 125ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50048092-front-zoom.jpg",
  // 1453 Skelan Forte 275mg/325mg (Sold Per Piece) (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50049516-front-zoom.jpg",
  // 1454 Tempra Forte 250mg Syrup Strawberry 120ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50027404-front-zoom.jpg",
  // 1455 Pond's White Beauty Tone-Up Milk Cream 6g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50003312-front-zoom.jpg",
  // 1456 Watsons Generics Flaxseed Oil Softgel (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50031041-front-zoom.jpg",
  // 1457 Nivea Deodorant Black & White Fresh Anti-Perspirant Roll-On 50ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50008012-front-zoom.jpg",
  // 1458 Watsons Honey and Milk Scented Cream Hand Wash 500ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10090318-front-zoom.jpg",
  // 1459 AidPlus Sterilized Gauze Pads 4x4  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50009974-front-zoom.jpg",
  // 1460 Luxe Organix Power Glow Vita C Serum 30ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50009277-front-zoom.jpg",
  // 1461 Efficascent Oil Extra Strength 25ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10001381-front-zoom.jpg",
  // 1462 Decilone Dexamethasone 500mcg 1 Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10000221-front-zoom.jpg",
  // 1463 Ascof Lagundi for Adults 600mg Tablet x 12 Tablets Pack  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10074363-front-zoom.jpg",
  // 1464 Robitussin Dextromethorphan HBr + Guaifenesin 15mg/100mg per 5ml Syrup 60ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10000950-front-zoom.jpg",
  // 1465 Trust Condom Strawberry 3s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10098195-front-zoom.jpg",
  // 1466 PinkCheck Cassette Early Pregnancy Test Kit 1s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10094168-front-zoom.jpg",
  // 1467 Luxe Organix Bye Bye Bum Bumps Clear & Firm Cellulite Cream 230g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50058977-front-zoom.jpg",
  // 1468 Reagila Antipsychotic 1.5mg Capsule (Sold Per Piece) (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50053672-front-zoom.jpg",
  // 1469 Telarb Telmisartan + Amlodipine 40mg/10mg Tablet (Sold Per Piece) (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50053485-front-zoom.jpg",
  // 1470 Treviamet Metformin Hydrochloride + Sitagliptin 1g/50mg Film-Coated Tablet (Sold Per Piece) (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50052447-front-zoom.jpg",
  // 1471 Lupibile 500mg Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50051926-front-zoom.jpg",
  // 1472 Charmee Feminine Pads Cottony for Heavy Flow 8+1s with Wings  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10067022-front-zoom.jpg",
  // 1473 Charmee Pantyliner Breathable Lavender Scent 20 Liners  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10055067-front-zoom.jpg",
  // 1474 Nivea Soft Moisturizing Creme 200ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10099055-front-zoom.jpg",
  // 1475 Cetaphil Moisturizing Lotion 473ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10098097-front-zoom.jpg",
  // 1476 Safeguard Pure White Bar Soap 55g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10050052-front-zoom.jpg",
  // 1477 Listerine Cool Mint Mild Taste Mouthwash 250ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50063377-front-zoom.jpg",
  // 1478 Giggles Baby Wipes Unscented Mini Pack 8 Wipes  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50053659-front-zoom.jpg",
  // 1479 Geisha White Classic Bar 65g x 3  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50048554-front-zoom.jpg",
  // 1480 Watsons Naturals Rosemary Shampoo 490ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50044661-front-zoom.jpg",
  // 1481 Dove Beauty Bar White 135g x 3  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50026323-front-zoom.jpg",
  // 1482 Nivea Lip Scrub with Rosehip Oil 4.8g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50030578-front-zoom.jpg",
  // 1483 Trimic Metronidazole 500mg + 100mg Vaginal Suppository 1 Piece (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50003536-front-zoom.jpg",
  // 1484 Watsons Generics Vitamin C + Vitamin E + Glutathione Effervescent Tablet 10s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50027643-front-zoom.jpg",
  // 1485 Watsons Waterproof Plasters Assorted Size 8s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50012082-front-zoom.jpg",
  // 1486 Lactacyd White Intimate Daily Feminine Wash 250ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10093561-front-zoom.jpg",
  // 1487 Celeteque DermoScience Hydration Toner Alcohol-Free 65ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10081413-front-zoom.jpg",
  // 1488 Milcu Sports Deodorant Underarm & Foot Powder 80g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10084860-front-zoom.jpg",
  // 1489 Plavix Clopidogrel Bisulfate 75mg 1 Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10003255-front-zoom.jpg",
  // 1490 Depo-Medrol Methylprednisolone Acetate 40mg/ml Suspension for Injection 5ml (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10089309-front-zoom.jpg",
  // 1491 White Flower Medicine Oil Classic No. 4 2.5ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10003118-front-zoom.jpg",
  // 1492 Pond's BB Magic Powder 45g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50003391-front-zoom.jpg",
  // 1493 Watsons Scented Nourish and Repair Conditioner 1L  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50000565-front-zoom.jpg",
  // 1494 Dove Soap Exfoliating 90g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10057717-front-zoom.jpg",
  // 1495 Modess Regular Ultra Thin with Wings Sanitary Napkin 10s Light Flow  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10003613-front-zoom.jpg",
  // 1496 Premiere Ultra Thin 3s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10026066-front-zoom.jpg",
  // 1497 Belo Intense White Deo Roll-On Bundle 2 x 25ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50062870-front-zoom.jpg",
  // 1498 Strepsils Twin Pack Save 15%  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50059287-front-zoom.jpg",
  // 1499 Watsons Scented Alcohol Fresh Bamboo 40ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50055249-front-zoom.jpg",
  // 1500 Papilocare Vaginal Gel 5ml (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50055476-front-zoom.jpg"
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