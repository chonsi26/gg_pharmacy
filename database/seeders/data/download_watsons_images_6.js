// ─────────────────────────────------------------------------------------------
// Saves every Watsons product image used by watsons_products_6.php
// (batch 6: links 501–600).
//
// HOW TO USE
//  1. In Chrome/Edge open this page (any image on that host works):
//       https://medias.watsons.com.ph/publishing/WTCPH-10092481-front-zoom.jpg
//  2. Press F12 -> Console. If asked, type  allow pasting  and press Enter.
//  3. Paste this whole script and press Enter.
//  4. If the browser asks to "allow multiple downloads", click Allow.
//  5. Move all downloaded files into:  database/seeders/data/images/
//     (keep their original names - the seeder finds them by file name)
//  6. Run:  php artisan db:seed
//
// It must be run on medias.watsons.com.ph so the requests are same-origin.
//
// 101 images, one per product (#501–#600, plus #523 Duvadilan added last as a gap-fill). ALL are UNVERIFIED (pages not opened):
// they follow the usual Watsons naming pattern (WTCPH-<product code>-front-zoom.jpg)
// and many may fail with HTTP 404. For any that fail, open the product page,
// right-click the image and save it by hand into database/seeders/data/images/
// (the seeder finds it by the 8-digit product code in the file name). The
// products still seed fine without an image.
// ─────────────────────────────────────────────────────────────────────────────
const urls = [
  // 501 Alzor Olmesartan Medoxomil 20mg + Amlodipine Besilate 5mg Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10092481-front-zoom.jpg",
  // 502 Llanol Allopurinol 100mg Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10000460-front-zoom.jpg",
  // 503 Centravim Vitamin B Complex Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10026927-front-zoom.jpg",
  // 504 Co-Aprovel Irbesartan + Hydrochlorothiazide 150mg/12.5mg Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10003898-front-zoom.jpg",
  // 505 Lustatin Rosuvastatin Calcium 40mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50011233-front-zoom.jpg",
  // 506 Luxe Organix Himalayan Salt Gargle Stick 10ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50013052-front-zoom.jpg",
  // 507 Myra E Smooth Glow Whitening Facial Moisturizer 40ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50005887-front-zoom.jpg",
  // 508 Aldomet Methyldopa 250mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10000018-front-zoom.jpg",
  // 509 Tramal Tramadol HCl 50mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10002878-front-zoom.jpg",
  // 510 Tempra Orange Syrup 120mg 60ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10003635-front-zoom.jpg",
  // 511 Regenesis Max Food Supplement 1s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50036786-front-zoom.jpg",
  // 512 Citrapos Potassium Citrate 1080mg Extended-Release Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50036148-front-zoom.jpg",
  // 513 Watsons Generics Turmeric Curcumin 3050mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50027648-front-zoom.jpg",
  // 514 Tegretol Carbamazepine 200mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50013390-front-zoom.jpg",
  // 515 Micardis Plus Telmisartan 80mg + HCTZ 12.5mg Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10087114-front-zoom.jpg",
  // 516 Cozaar Losartan Potassium 50mg Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10001229-front-zoom.jpg",
  // 517 Montiget Montelukast Sodium 5mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10059882-front-zoom.jpg",
  // 518 Calciumade Calcium + Vitamin D3 + Minerals 12 Tablets  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10067746-front-zoom.jpg",
  // 519 Colgate Optic White Purple Whitening Toothpaste 37g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50046512-front-zoom.jpg",
  // 520 Alzor Olmesartan Medoxomil 20mg + Amlodipine Besilate 10mg Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10092480-front-zoom.jpg",
  // ── Batch 27 (links 521–550) ──
  // 521 Allerkid Cetirizine Hydrochloride 5mg/5ml Syrup 60ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10089804-front-zoom.jpg",
  // 522 Glucopro Wintermelon Tea Green 1 Sachet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50035698-front-zoom.jpg",
  // 523 Bestatin Atorvastatin (as Calcium) 20mg Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50004480-front-zoom.jpg",
  // 524 Omepron Omeprazole 40mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10090905-front-zoom.jpg",
  // 525 Luxe Organix Dermapro PDRN Ceramides Exosome Recovery Barrier Cream Balm 30g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50058482-front-zoom.jpg",
  // 526 Stimuno 250mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50042763-front-zoom.jpg",
  // 527 Xelevia Sitagliptin 50mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10092063-front-zoom.jpg",
  // 528 Purifol Folic Acid 5mg Capsule  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10040015-front-zoom.jpg",
  // 529 Flanax Naproxen Sodium 275mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10002148-front-zoom.jpg",
  // 530 Watsons Generics Vildagliptin 50mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50049721-front-zoom.jpg",
  // 531 pH Care Feminine Wash Delicate White 250ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50027900-front-zoom.jpg",
  // 532 Eyeberry Dietary Food Supplement  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50025432-front-zoom.jpg",
  // 533 Risdin Risperidone 2mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10093535-front-zoom.jpg",
  // 534 Angiozem Diltiazem Hydrochloride 60mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50004479-front-zoom.jpg",
  // 535 Luxe Organix Aqua Daily Sunscreen SPF50 PA+++ UVA/UVB Protection 50ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50011966-front-zoom.jpg",
  // 536 Ambesyl Amlodipine 5mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10077507-front-zoom.jpg",
  // 537 Alzor Olmesartan Medoxomil 20mg 1 Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10092478-front-zoom.jpg",
  // 538 Cialis Tadalafil 5mg 1 Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10098646-front-zoom.jpg",
  // 539 Ceelin Ascorbic Acid + Zinc Syrup 120ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10079469-front-zoom.jpg",
  // 540 Urodil 300mg Tablet by 100s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50048981-front-zoom.jpg",
  // 541 Lanistor Lamotrigine 50mg Anti-Epileptic Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50043163-front-zoom.jpg",
  // 542 Sinutab Cold Plus Paracetamol 500mg + Phenylephrine 5mg + Chlorphenamine 2mg Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50036230-front-zoom.jpg",
  // 543 Concore Bisoprolol Fumarate + Amlodipine 5mg/5mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10102287-front-zoom.jpg",
  // 544 Hemostan Tranexamic Acid 500mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10000361-front-zoom.jpg",
  // 545 Deplatt Clopidogrel Bisulfate 75mg 1 Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10100919-front-zoom.jpg",
  // 546 Triveram Atorvastatin 20mg + Perindopril Arginine 5mg + Amlodipine 5mg 1 Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50011346-front-zoom.jpg",
  // 547 Celcoxx Celecoxib 200mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10059880-front-zoom.jpg",
  // 548 Luxe Organix Whitening Repair Ultralight Glow Toner 150ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50009279-front-zoom.jpg",
  // 549 Co-Diovan Valsartan 160mg + Hydrochlorothiazide 12.5mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10028477-front-zoom.jpg",
  // 550 Tamdutor Dutasteride 500mcg + Tamsulosin 400mcg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50053241-front-zoom.jpg",
  // ── Batch 28 (links 551–580) ──
  // 551 Lipway Fenofibrate 160mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50009789-front-zoom.jpg",
  // 552 Myelax Eperisone 50mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50007689-front-zoom.jpg",
  // 553 Qtern Saxagliptin (as Hydrochloride) + Dapagliflozin (as Propanediol Monohydrate) 5mg/10mg 1 Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50027205-front-zoom.jpg",
  // 554 Triplixam Perindopril Arginine 10mg + Indapamide 2.5mg + Amlodipine Besilate 10mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50008388-front-zoom.jpg",
  // 555 Torus Rosuvastatin Calcium 10mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10095252-front-zoom.jpg",
  // 556 Dioxel Diosmin + Hesperidin 450mg/50mg 1 Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10101971-front-zoom.jpg",
  // 557 Valovir Valacyclovir 500mg Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50044678-front-zoom.jpg",
  // 558 Ramipro Ramipril 5mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10096186-front-zoom.jpg",
  // 559 Decilone Dexamethasone 4mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10000220-front-zoom.jpg",
  // 560 Co-Diovan Valsartan 80mg + Hydrochlorothiazide 12.5mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10003567-front-zoom.jpg",
  // 561 Zoltax Cefuroxime Axetil 500mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10076291-front-zoom.jpg",
  // 562 Multivent Salbutamol (as Sulfate) + Ipratropium (as Bromide) 2.5mg/500mcg per 2.5ml 1 Nebule  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50006771-front-zoom.jpg",
  // 563 Eveprim Evening Primrose Oil 1000mg 1 Softgel Capsule  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10036247-front-zoom.jpg",
  // 564 ATC Health Grape Seed 500mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10087401-front-zoom.jpg",
  // 565 Cetaphil Gentle Skin Cleanser 473ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10081407-front-zoom.jpg",
  // 566 Simeco Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10030565-front-zoom.jpg",
  // 567 Ritemed Ambroxol 30mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10025795-front-zoom.jpg",
  // 568 Mucosolvan Ambroxol Hydrochloride 75mg Retard  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10000528-front-zoom.jpg",
  // 569 Luxe Organix Trouble Acne Spot Patch Day 10g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50001362-front-zoom.jpg",
  // 570 Expel Pedia Syrup 15mg 60ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10043366-front-zoom.jpg",
  // 571 Sucranorm 850mg Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50046992-front-zoom.jpg",
  // 572 Watsons Generics Sitagliptin 50mg 1 Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50049014-front-zoom.jpg",
  // 573 Nutrabliss WS Apple Cider Vinegar 500mg Capsule  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50031078-front-zoom.jpg",
  // 574 Diovan Valsartan 80mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10001462-front-zoom.jpg",
  // 575 Ritemed Gliclazide 80mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10094926-front-zoom.jpg",
  // 576 Valpros Sodium Valproate 500mg 1 Controlled-Release Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10100931-front-zoom.jpg",
  // 577 Clazol Cilostazol 50mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10064667-front-zoom.jpg",
  // 578 Jeunesse Anion Pantyliners Long 10s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50033135-front-zoom.jpg",
  // 579 Cetaphil Bright Healthy Radiance Brightness Refresh Toner  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50026802-front-zoom.jpg",
  // 580 Cetaphil Gentle Skin Cleanser 118ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10098771-front-zoom.jpg",
  // ── Batch 29 (links 581–600) ──
  // 581 Nutrabliss WS Watsons Advance Probiotics 3g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50058904-front-zoom.jpg",
  // 582 Trilaxant Baclofen 10mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50017692-front-zoom.jpg",
  // 583 Glipten Teneligliptin (as Hydrobromide Hydrate) 20mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50014315-front-zoom.jpg",
  // 584 Simplex Cotton Tip Applicator  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10090726-front-zoom.jpg",
  // 585 Med-Guard Cotton Buds Blue 200 Tips  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10052954-front-zoom.jpg",
  // 586 Watsons Dental Floss Round Thread Pack Mint 50s x 3 Boxes  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10089579-front-zoom.jpg",
  // 587 Watsons Generics Paracetamol + Phenylephrine Hydrochloride + Chlorphenamine Maleate 500mg/10mg/2mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50047284-front-zoom.jpg",
  // 588 Disudrin Phenylephrine HCl 5mg + Chlorphenamine Maleate 1mg Syrup 120ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10054984-front-zoom.jpg",
  // 589 Aveeno Face C.R. Oat Gel Moisturizer 50ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50049046-front-zoom.jpg",
  // 590 Bewell-C Zinc Protect Ascorbic Acid + Zinc 24 + 6 Capsules Promo Pack  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50036785-front-zoom.jpg",
  // 591 Ritemed Clopidogrel Bisulfate 75mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10090201-front-zoom.jpg",
  // 592 Pond's Age Miracle Night Cream 9g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50001965-front-zoom.jpg",
  // 593 Norvasc Protect 5mg/10mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10090366-front-zoom.jpg",
  // 594 Arbloc Losartan Potassium 50mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10099226-front-zoom.jpg",
  // 595 Lacipil Lacidipine 4mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10003982-front-zoom.jpg",
  // 596 Nuelin Theophylline 125mg 1 Sustained-Release Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10000583-front-zoom.jpg",
  // 597 Renogard 600mg Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50021207-front-zoom.jpg",
  // 598 Neomerdin Carbimazole 5mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10093668-front-zoom.jpg",
  // 599 Diclegis 10mg/10mg Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50048066-front-zoom.jpg",
  // 600 Leginsol-E with Fish Oil 1 Softgel Capsule  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50040320-front-zoom.jpg",
  // 523 (gap fill) Duvadilan Isoxsuprine Hydrochloride 10mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10000269-front-zoom.jpg"
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