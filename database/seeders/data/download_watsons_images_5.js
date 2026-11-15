// ─────────────────────────────------------------------------------------------
// Saves every Watsons product image used by watsons_products_5.php
// (batch 5: links 401–500).
//
// HOW TO USE
//  1. In Chrome/Edge open this page (any image on that host works):
//       https://medias.watsons.com.ph/publishing/WTCPH-10087915-front-zoom.jpg
//  2. Press F12 -> Console. If asked, type  allow pasting  and press Enter.
//  3. Paste this whole script and press Enter.
//  4. If the browser asks to "allow multiple downloads", click Allow.
//  5. Move all downloaded files into:  database/seeders/data/images/
//     (keep their original names - the seeder finds them by file name)
//  6. Run:  php artisan db:seed
//
// It must be run on medias.watsons.com.ph so the requests are same-origin.
//
// 100 images, one per product (#401–#500). ALL are UNVERIFIED (pages not opened):
// they follow the usual Watsons naming pattern (WTCPH-<product code>-front-zoom.jpg)
// and many may fail with HTTP 404. For any that fail, open the product page,
// right-click the image and save it by hand into database/seeders/data/images/
// (the seeder finds it by the 8-digit product code in the file name). The
// products still seed fine without an image.
// ─────────────────────────────────────────────────────────────────────────────
const urls = [
  // 401 Twynsta Telmisartan 40mg + Amlodipine 10mg Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10087914-front-zoom.jpg",
  // 402 Ursolin Ursodeoxycholic Acid 250mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50006285-front-zoom.jpg",
  // 403 Ritemed Terazosin 5mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50021045-front-zoom.jpg",
  // 404 Synactiv Levocetirizine + Betamethasone 5mg/1mg Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50006220-front-zoom.jpg",
  // 405 Lady Pill Levonorgestrel + Ethinyl Estradiol 150/30mcg 28 Pills Box  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10030671-front-zoom.jpg",
  // 406 ATC Health Robust Extreme 400mg 2 Capsules  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10094966-front-zoom.jpg",
  // 407 Galvusmet Vildagliptin + Metformin 50mg/500mg Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10082579-front-zoom.jpg",
  // 408 Ezitin 10mg Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50037855-front-zoom.jpg",
  // 409 Fixcom Rifampicin + Isoniazid 150mg/75mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50010873-front-zoom.jpg",
  // 410 Funxion Pregabalin 75mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50003946-front-zoom.jpg",
  // 411 OB Vitamins and Minerals with Folic Acid, Iron and Taurine  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10003381-front-zoom.jpg",
  // 412 Aforbes Amlodipine 10mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10085718-front-zoom.jpg",
  // 413 Maxi-Peel Exfoliant Solution #3 30ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10003631-front-zoom.jpg",
  // 414 Essentiale Forte P 300mg Hard Gelatin Capsule  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50054294-front-zoom.jpg",
  // 415 pH Care Feminine Wash Natural Protection 250ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50027898-front-zoom.jpg",
  // 416 Itorvaz Atorvastatin 80mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10100933-front-zoom.jpg",
  // 417 Glutaphos Dicalcium Phosphate 200mg + Glutamic Acid 325mg + Thiamine 5mg + Vitamin B12 10mcg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10000354-front-zoom.jpg",
  // 418 Kessen Renal Nutrition Therapy 630mg Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50037914-front-zoom.jpg",
  // 419 Ritemed Metformin 1g Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50050490-front-zoom.jpg",
  // 420 Eltroxin Levothyroxine Sodium 50mcg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10002081-front-zoom.jpg",
  // 421 Neurogen-E Vitamin B Complex + Vitamin E 8 Tablets  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10091613-front-zoom.jpg",
  // 422 Myra E Smooth Glow Facial Moisturizer 7ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50005921-front-zoom.jpg",
  // 423 Skelan Naproxen Sodium 550mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10064675-front-zoom.jpg",
  // 424 Myra E Vitamin E 400 IU x 8 Capsules  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10039989-front-zoom.jpg",
  // 425 Rexona Motion Sense Ice Cool 48H Anti-Perspirant 25ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10071211-front-zoom.jpg",
  // 426 Olzadin Olanzapine 5mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10101142-front-zoom.jpg",
  // 427 Fisherman's Friend Strong Mint 25g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10090964-front-zoom.jpg",
  // 428 Watsons Generics Candesartan 8mg Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50053650-front-zoom.jpg",
  // 429 Jovia Escitalopram Oxalate 10mg 1 Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10093284-front-zoom.jpg",
  // 430 Jardiance Empagliflozin + Metformin HCl 5mg/500mg 1 Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50009768-front-zoom.jpg",
  // 431 Imatero Film-Coated Tablet 100mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50044677-front-zoom.jpg",
  // 432 Inderal Propranolol 40mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10002261-front-zoom.jpg",
  // 433 Luxe Organix Hydrocolloid Acne Spot Patch Night  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50001363-front-zoom.jpg",
  // 434 Ritemed Atenolol 50mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10089140-front-zoom.jpg",
  // 435 Nasoclear Nasal Spray 30ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10087040-front-zoom.jpg",
  // 436 Tenofo-B Tenofovir Disoproxil Fumarate 300mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50020195-front-zoom.jpg",
  // 437 Celeteque Brightening Alcohol-Free Toner 125ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50000559-front-zoom.jpg",
  // 438 Versant Felodipine 5mg 1 Extended-Release Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10035847-front-zoom.jpg",
  // 439 Rustor Rosuvastatin 10mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10096811-front-zoom.jpg",
  // 440 Rowatinex Pinene, Camphene, Cineol, Menthone, Menthol, Borneol  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10059761-front-zoom.jpg",
  // 441 Motilium Domperidone 10mg Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10002447-front-zoom.jpg",
  // 442 Katinko Menthol 3mg + Methyl Salicylate 1.5mg + Eucalyptus Oil 1.5mg + Camphor 1.2mg Roll-On 10ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50000071-front-zoom.jpg",
  // 443 Funxion Pregabalin + Mecobalamin 75mg/750mcg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50035355-front-zoom.jpg",
  // 444 Deslogen Desloratadine 5mg 1 Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10099121-front-zoom.jpg",
  // 445 Geisha White Intense Bar 65g x 3  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50048556-front-zoom.jpg",
  // 446 Cleene Cotton Rounds Multi-Purpose 80s x 2 Packs  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50024159-front-zoom.jpg",
  // 447 Myra E Whitening Glow Body Lotion 200ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50059020-front-zoom.jpg",
  // 448 Ramipro Ramipril 2.5mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10096185-front-zoom.jpg",
  // 449 Approvel Irbesartan 150mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10001604-front-zoom.jpg",
  // 450 Nirva Clozapine 25mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50004476-front-zoom.jpg",
  // 451 Sisters Night Plus Heavy Flow Cottony Comfort 8 Pads  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10017374-front-zoom.jpg",
  // 452 Telmirin Telmisartan 80mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50050168-front-zoom.jpg",
  // 453 Ritemed Clindamycin 300mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10088342-front-zoom.jpg",
  // 454 Ceelin Gummies 30s Pack of 2  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50045611-front-zoom.jpg",
  // 455 Pregmax Pregabalin + Methylcobalamin 75mg/750mcg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50006773-front-zoom.jpg",
  // 456 Lipitor Atorvastatin 40mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10003833-front-zoom.jpg",
  // 457 Glumet Metformin 850mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10095652-front-zoom.jpg",
  // 458 Surelax Sachet 17g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50009312-front-zoom.jpg",
  // 459 Berocca Effervescent Tablet Orange Flavor 30s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10101326-front-zoom.jpg",
  // 460 Calvit Calcium + Vitamin D 1 Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50010759-front-zoom.jpg",
  // 461 Luxe Organix Keratin Treatment Milk Protein 250ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50035246-front-zoom.jpg",
  // 462 Actazone Pioglitazone HCl 30mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50031341-front-zoom.jpg",
  // 463 Panto Plus Domperidone + Pantoprazole 40mg/30mg 1 Capsule  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10100202-front-zoom.jpg",
  // 464 Benadryl One 10mg Allergy Medicine Antihistamine Cetirizine Non-Drowsy  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10089073-front-zoom.jpg",
  // 465 Creamsilk Treatment Amino Keratin Rebond Straight 500ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50053950-front-zoom.jpg",
  // 466 Dermaid Aroma Oil Moisturizing Roll-On Chamomile 10ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50011695-front-zoom.jpg",
  // 467 Fluimucil Acetylcysteine 200mg Granules for Solution  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10003214-front-zoom.jpg",
  // 468 Watsons Generics Carbocisteine 500mg Capsule  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10096699-front-zoom.jpg",
  // 469 Zemimet SR 50mg/1g Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50047292-front-zoom.jpg",
  // 470 Mosvit Multivitamins + Minerals  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50019171-front-zoom.jpg",
  // 471 Serc Betahistine diHCl 16mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10001636-front-zoom.jpg",
  // 472 Benzac Benzoyl Peroxide Spots Treatment Gel 5% 15g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10038358-front-zoom.jpg",
  // 473 Watsons Generics Cetirizine 10mg 8+2 Capsules  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50045541-front-zoom.jpg",
  // 474 Tolak Angin Sidomuncul Anak Herbal Supplement for Kids 10ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50018603-front-zoom.jpg",
  // 475 Valcamp Amlodipine Besilate 5mg + Valsartan 80mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50001541-front-zoom.jpg",
  // 476 Coveram Perindopril Arginine 10mg + Amlodipine 10mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10083939-front-zoom.jpg",
  // 477 Salinase Sodium Chloride 0.65% Nebulizing Solution 1 Nebule  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10100201-front-zoom.jpg",
  // 478 Watsons Generics Azithromycin 500mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10096375-front-zoom.jpg",
  // 479 Ritemed Cloxacillin Sodium 500mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10035596-front-zoom.jpg",
  // 480 Mediplast Sterilized Gauze Pads 3 x 3  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10002423-front-zoom.jpg",
  // 481 Lupibile Ursodeoxycholic Acid 300mg Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50052986-front-zoom.jpg",
  // 482 Alfaren Ketoanalogues + Essential Amino Acids Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50038729-front-zoom.jpg",
  // 483 Alnix Cetirizine Dihydrochloride 5mg + Phenylephrine Hydrochloride 5mg Syrup 60ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10085778-front-zoom.jpg",
  // 484 Teldra 40mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50043144-front-zoom.jpg",
  // 485 Biogesic Paracetamol 250mg Syrup 60ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50046453-front-zoom.jpg",
  // 486 Luxe Organix Retinol + Bakuchiol Overnight Glow Gentle Treatment Cream 30g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50043210-front-zoom.jpg",
  // 487 Cortal Aspirin 500mg Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50012771-front-zoom.jpg",
  // 488 Lacta Flow 500mg Capsule  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50033432-front-zoom.jpg",
  // 489 Myra Ultimate Dietary Supplement 7+1 Promo Pack  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50054003-front-zoom.jpg",
  // 490 Glipdin Glipizide 5mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10099066-front-zoom.jpg",
  // 491 Therabloc Atenolol 25mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10025798-front-zoom.jpg",
  // 492 Nivea Strawberry Shine Lip Balm 4.8g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10026700-front-zoom.jpg",
  // 493 Watsons Generics Diosmin + Hesperidin 450mg/50mg 1 Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50042110-front-zoom.jpg",
  // 494 Zykids Forte 5mg/5mg Chewable Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50050710-front-zoom.jpg",
  // 495 Veral Verapamil HCl 240mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50026562-front-zoom.jpg",
  // 496 Brilinta Ticagrelor 90mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10093126-front-zoom.jpg",
  // 497 Celeteque Dermo Science Hydration Facial Wash 60ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10081417-front-zoom.jpg",
  // 498 Glamworks Permanent Hair Dye Color Black 30ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10090866-front-zoom.jpg",
  // 499 Ceelin Ascorbic Acid 100mg Chewable Tablets x 60  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10051285-front-zoom.jpg",
  // 500 Kotex Fresh Lightdays Long and Wide 16s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10037749-front-zoom.jpg"
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