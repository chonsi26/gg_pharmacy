// ─────────────────────────────────────────────────────────────────────────────
// Saves every Watsons product image used by watsons_products_16.php
// (batch 16: links 1501–1600).
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
// 100 images, one per product (#1501–#1600).
//   All are UNVERIFIED: the product pages did not show image links, so they follow the usual
//   WTCPH-<product code>-front-zoom.jpg pattern and many may fail with HTTP 404 (Watsons often appends a
//   ?version= suffix or a random token to the real file name). For any that fail, open the product page,
//   right-click the image and save it by hand into database/seeders/data/images/ (the seeder finds it by the
//   8-digit product code in the file name). The products still seed fine without an image.
// ─────────────────────────────────────────────────────────────────────────────
const urls = [
  // 1501 Luxe Organix Bronze Off Color Toning Blue Shampoo 270ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50056369-front-zoom.jpg",
  // 1502 Pure Form Inositol 40:1 Myo and D-Chiro Folic Acid Naturally Mildly Sweet Powder 150g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50054662-front-zoom.jpg",
  // 1503 Megan Cotton Pads 50 Pads  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50054269-front-zoom.jpg",
  // 1504 Pond's Bright Miracle Ultimate Clarity Serum 14g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50052932-front-zoom.jpg",
  // 1505 Kojie San Plus Niacinamide Soap 100g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50051372-front-zoom.jpg",
  // 1506 Celeteque DermoScience Hydration Micellar Water 125ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50048093-front-zoom.jpg",
  // 1507 Dove Radiant Care Orange Niacinamide + Vitamin C & E Beauty Bar 90g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50047888-front-zoom.jpg",
  // 1508 Ascenda Wyeth Ascenda 1 to 3 1600g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50045668-front-zoom.jpg",
  // 1509 Watsons Love My Little Ones Moisturising Baby 100% Eco-Friendly Soft Wipes 90 Wipes x 2s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50038204-front-zoom.jpg",
  // 1510 Body Treats Fresh and Clean Hand Soap Clean Cotton Scented 750ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50038510-front-zoom.jpg",
  // 1511 Luxe Organix Castor Oil Dual Tip Eyelash & Brow Growth Serum 12ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50040474-front-zoom.jpg",
  // 1512 Cerelac Wheat Banana and Milk 120g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50036622-front-zoom.jpg",
  // 1513 Dermplus Moisturizing Sunscreen SPF60 PA  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50004834-front-zoom.jpg",
  // 1514 Amlife Losartan Potassium + Amlodipine Besilate + Hydrochlorothiazide 50mg/5mg/12.5mg 1 Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50009030-front-zoom.jpg",
  // 1515 Dove Body Wash Sensitive Skin 1L  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50013381-front-zoom.jpg",
  // 1516 Elica Eczema Topical Steroid Ointment 5g (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10004009-front-zoom.jpg",
  // 1517 Fisherman's Friend Mint Sugar-Free 25g x 24  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10001401-front-zoom.jpg",
  // 1518 Watsons Moisture and Revive Nourishing Shampoo 1L  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10089666-front-zoom.jpg",
  // 1519 Colgate Sensitive Fresh Mint Toothpaste 110g Twin Pack  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10101566-front-zoom.jpg",
  // 1520 Charmee Pantyliners Breathable Deodorizing Green Tea 20s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10069461-front-zoom.jpg",
  // 1521 Old Spice High Endurance Pure Sport 45g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10016066-front-zoom.jpg",
  // 1522 Mosbeau Collagen Plus 12000mg x15 Pack  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10093815-front-zoom.jpg",
  // 1523 Body Treats Sweet Pea Hand Soap 750ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10090581-front-zoom.jpg",
  // 1524 Charmee Feminine Pads Menstrual Pants Large 6s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50051136-front-zoom.jpg",
  // 1525 Xavi 2.5mg Film-Coated Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50047346-front-zoom.jpg",
  // 1526 Luxe Organix Scalp Therapy Anti-Hair Fall Gentle Clarifying Detox Shampoo 270ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50037811-front-zoom.jpg",
  // 1527 Cerelac Rice and Soya 120g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50036624-front-zoom.jpg",
  // 1528 Focale Levetiracetam 1g 1 Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50011849-front-zoom.jpg",
  // 1529 Ampalaya Plus 550mg 30s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50025365-front-zoom.jpg",
  // 1530 Luxe Organix Anne Clutz Charcoal Oil Absorbing Blotting Paper Refill 50 Sheets  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50030260-front-zoom.jpg",
  // 1531 Femara Letrozole 2.5mg 1 Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10097560-front-zoom.jpg",
  // 1532 Lopicard Amlodipine 10mg 1 Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10067050-front-zoom.jpg",
  // 1533 Polynerv Paracetamol + Vit B1 + Vit B6 HCl + Vit B12 600mg/150mg/100mg/3000mcg (Sold Per Piece) (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10089076-front-zoom.jpg",
  // 1534 Gabix Gabapentin 100mg (Sold Per Piece) (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10065165-front-zoom.jpg",
  // 1535 Perfect Smile Whitening Gargle Stick 10ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50027213-front-zoom.jpg",
  // 1536 Robestar Rosuvastatin Film-Coated 1 Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10099043-front-zoom.jpg",
  // 1537 Coveram Perindopril Arginine 5mg + Amlodipine 10mg 1 Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10083937-front-zoom.jpg",
  // 1538 Watsons Generics Clobetasol Propionate 0.05% Cream x 5g (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10101080-front-zoom.jpg",
  // 1539 Anoion Amiodarone HCl 200mg 1 Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10059673-front-zoom.jpg",
  // 1540 Lactacyd Protecting Daily Feminine Wash 250ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10095982-front-zoom.jpg",
  // 1541 Watsons Transparent Plaster 25s Assorted Sizes  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10085817-front-zoom.jpg",
  // 1542 Hair Treats Protein Straight Hair Treatment 600g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50061538-front-zoom.jpg",
  // 1543 Myra E Whitening Glow Up Bar Soap 90g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50057106-front-zoom.jpg",
  // 1544 Biore UV Aqua Rich Watery Essence 85g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50055679-front-zoom.jpg",
  // 1545 Watsons Hair System ScalpTech Dermo Clarifying Shampoo for Oily-Prone Scalp 500ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50053478-front-zoom.jpg",
  // 1546 Snailwhite Everyday Glow Sunscreen SPF 50 PA 6ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50051376-front-zoom.jpg",
  // 1547 Escivex 10mg (Sold Per Piece) (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50037857-front-zoom.jpg",
  // 1548 Fresh Skinlab Tomato Glass Skin 3-in-1 Vitamin C Brightening pH 5.5 Toner 250ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50047310-front-zoom.jpg",
  // 1549 Johnson's Baby Regular Baby Soap 120g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50044999-front-zoom.jpg",
  // 1550 Sofy Comfort Nite Slim Wing 42.5cm 8 Pads  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50043093-front-zoom.jpg",
  // 1551 Jelly Time Natural Water-Based Lubricant 50ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50041987-front-zoom.jpg",
  // 1552 Ginger Bon Honey Lemon Flavor Ginger Chewy Candy 20g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50034429-front-zoom.jpg",
  // 1553 Focale 500mg Tablet 30s Box (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50011847-front-zoom.jpg",
  // 1554 Focale Levetiracetam 250mg 1 Film-Coated Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50011848-front-zoom.jpg",
  // 1555 Starcox Etoricoxib 120mg Film-Coated 1 Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50031168-front-zoom.jpg",
  // 1556 Watsons Isopropyl 70% Alcohol 1 Gallon  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50032180-front-zoom.jpg",
  // 1557 Getryl Glimepiride 1mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10059875-front-zoom.jpg",
  // 1558 Dermazole Plus Zinc Shampoo 50ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50008311-front-zoom.jpg",
  // 1559 Watsons Goat Milk Scented Cream Hand Wash 500ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50019784-front-zoom.jpg",
  // 1560 BD Ultra-Fine II Insulin 1ml x 1 Syringe  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10037825-front-zoom.jpg",
  // 1561 Scott's Pastilles Vitamin C Pastilles Orange 50s Bottle  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10093812-front-zoom.jpg",
  // 1562 Vaseline Gluta-Hya Niacinamide Tone-Up UV Lotion 300ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50061860-front-zoom.jpg",
  // 1563 Luxe Organix DermaPro TXA Melasma Clear 6.0 Complexion Bright Exfoliating Toner 150ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50058386-front-zoom.jpg",
  // 1564 Luxe Organix High Protection Cloud Matte Soft Blurring Mineral Serum Sunscreen SPF50 PA 40g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50058148-front-zoom.jpg",
  // 1565 Equal Classic Zero Calories Sweetener 100 Sticks x 1g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50054681-front-zoom.jpg",
  // 1566 Coxid 200mg Capsule x30  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10095407-front-zoom.jpg",
  // 1567 Luxe Organix Clinical Pro Retinol Radiance Serum 30ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50044472-front-zoom.jpg",
  // 1568 Efficascent Oil Extreme with Lavender Scent 100ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50039121-front-zoom.jpg",
  // 1569 Dr. Sensitive Niacinamide 5% + Salicylic Acid Serum 30ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50038727-front-zoom.jpg",
  // 1570 Luxe Organix Niacinamide + Alpha Arbutin Velvet Shower Salt Scrub 320g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50038735-front-zoom.jpg",
  // 1571 Difluvid Fluconazole 150mg 1 Capsule (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50015598-front-zoom.jpg",
  // 1572 Colgate Active Salt Natural Toothpaste 175g Twin Pack  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50020400-front-zoom.jpg",
  // 1573 Dermaid Lavender Foot Deodorant Spray 60ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10093108-front-zoom.jpg",
  // 1574 IPI Aceite de Manzanilla Oil Mineral Oil 25ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10002373-front-zoom.jpg",
  // 1575 Vaseline Lip Therapy Original 4.8g Stick  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50025757-front-zoom.jpg",
  // 1576 Charmee Pantyliner Powder Cool 20s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10091029-front-zoom.jpg",
  // 1577 Modess Regular Cottony Soft with Wings Sanitary Napkins 16s Regular Flow Fast Absorbing vs Leaks  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10078268-front-zoom.jpg",
  // 1578 QuickFX Sun Screen 30g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50008997-front-zoom.jpg",
  // 1579 Nivea Men Deodorant Black & White Original Anti-Perspirant Spray 150ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10093371-front-zoom.jpg",
  // 1580 Glamworks Permanent Hair Dye Color Light Brown 30ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10091768-front-zoom.jpg",
  // 1581 Sensodyne Deep Clean Toothpaste 100g Pack of 4  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50062353-front-zoom.jpg",
  // 1582 Dolo-Neurobion Paracetamol with B Vitamins 9+1 Promo Pack  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50037521-front-zoom.jpg",
  // 1583 Lysol On the Go Antibacterial Wipes 10 Sheets x 3s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50051232-front-zoom.jpg",
  // 1584 Kotex Freedom 28cm Value Pack  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50046886-front-zoom.jpg",
  // 1585 Ryaltris Olopatadine + Mometasone Furoate 600mcg/25mcg Nasal Spray 120 Metered Sprays (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50036067-front-zoom.jpg",
  // 1586 Luxe Organix Bye Brass Purple Shampoo 270ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50036502-front-zoom.jpg",
  // 1587 Dermaid CMRelief Honey and Lemon Lozenges 8s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50036260-front-zoom.jpg",
  // 1588 Ritemed Irbesartan 300mg (Sold Per Piece) (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50027378-front-zoom.jpg",
  // 1589 Safeguard Foaming Hand Soap Lemon Refill 400ml x 1  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50032612-front-zoom.jpg",
  // 1590 Aveeno Skin Relief Body Wash 1L for Sensitive, Dry, Itchy Skin, Eczema, Atopic Dermatitis  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50030274-front-zoom.jpg",
  // 1591 Zolid Pioglitazone 15mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10067857-front-zoom.jpg",
  // 1592 Getryl Glimepiride 2mg 1 Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10059876-front-zoom.jpg",
  // 1593 SkinWhite Classic MoistureWhite Soap 125g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10047019-front-zoom.jpg",
  // 1594 AidPlus Hot Water Bag 1000ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50009966-front-zoom.jpg",
  // 1595 DuoDERM Extra Thin Wound Dressing 4x4 Inches 1pc  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10094222-front-zoom.jpg",
  // 1596 Valtrex Valaciclovir 500mg 1 Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10003173-front-zoom.jpg",
  // 1597 White Flower Medicine Oil Classic No. 5  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10003119-front-zoom.jpg",
  // 1598 Green Cross 70% Antiseptic Disinfectant with Moisturizer 60ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10083551-front-zoom.jpg",
  // 1599 Carefree Breathable Panty Liners Flats 15s Feminine Care Odor Control Absorb Discharge  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10068995-front-zoom.jpg",
  // 1600 Cuddles Cotton Rounds 80 Sheets  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10042617-front-zoom.jpg"
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
