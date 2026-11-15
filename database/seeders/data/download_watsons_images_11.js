// ─────────────────────────────------------------------------------------------
// Saves every Watsons product image used by watsons_products_11.php
// (batch 11: links 1001–1100).
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
// 100 images, one per product (#1001–#1100).
//   #1020 is VERIFIED (real URL taken from the live product page, with its ?version= suffix).
//   All the others are UNVERIFIED: the product pages did not show image links, so they follow the usual
//   WTCPH-<product code>-front-zoom.jpg pattern and many may fail with HTTP 404 (Watsons often appends a
//   ?version= suffix or a random token to the real file name). For any that fail, open the product page,
//   right-click the image and save it by hand into database/seeders/data/images/ (the seeder finds it by the
//   8-digit product code in the file name). The products still seed fine without an image.
// ─────────────────────────────────────────────────────────────────────────────
const urls = [
  // 1001 Glimesyn Glimepiride 2mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10100943-front-zoom.jpg",
  // 1002 ATC Health Silymarin + Sodium Ascorbate (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10087400-front-zoom.jpg",
  // 1003 Stresstabs Multivitamins + Iron 8 Tablets  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50000185-front-zoom.jpg",
  // 1004 Olay Natural White Pink Fairness Resealable Cream 7.5g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10091806-front-zoom.jpg",
  // 1005 Amlobloc Duo Losartan Potassium 50mg + Amlodipine 5mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50031592-front-zoom.jpg",
  // 1006 Nivea Micellair Fair and Glow Micellar Water 125ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50016858-front-zoom.jpg",
  // 1007 Gastrec Omeprazole 40mg 1 Delayed-Release Capsule (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10101409-front-zoom.jpg",
  // 1008 Dove Radiant Care Serum Bar Triple 50x Niacinamide + Omega-6 90g x 3s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50048094-front-zoom.jpg",
  // 1009 Luxe Organix Niacinamide + Bakuchiol + Retinol Velvet Salt Scrub 300g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50042172-front-zoom.jpg",
  // 1010 Ecospirin Plus 75mg/75mg Film-Coated Tablet (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50011350-front-zoom.jpg",
  // 1011 Rosalta Rosuvastatin Calcium 20mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50027438-front-zoom.jpg",
  // 1012 Ritemed Cefixime 200mg 1 Capsule  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10098324-front-zoom.jpg",
  // 1013 Daphne Lynestrenol 500mcg Tablets x 28 Tablets Pack  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10050432-front-zoom.jpg",
  // 1014 Luxe Organix Aloe Vera Soothing Gel 99% 300ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10098853-front-zoom.jpg",
  // 1015 SkinWhite Classic Light Lotion SPF20 200ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10048626-front-zoom.jpg",
  // 1016 Bactidol Oral Antiseptic Gargle Mouthwash 120ml for Sore Throat and Itchy Throat  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10024137-front-zoom.jpg",
  // 1017 Miracle White Pure Glow Gluta Collagen 30 Capsules  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50052821-front-zoom.jpg",
  // 1018 Watsons Pregnancy Test Strips 1s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50034972-front-zoom.jpg",
  // 1019 Antamin Chlorphenamine Maleate 4mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10083865-front-zoom.jpg",
  // 1020 Tranamic Tranexamic Acid 500mg + Mefenamic Acid 250mg Film-Coated Tablet (Sold Per Piece)  (VERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50057612-front-zoom.jpg?version=1773183006",
  // 1021 Luxe Organix Dermapro Salicylic Acid 2% Acne Rescue Serum Antibacterial Body Wash 500ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50048547-front-zoom.jpg",
  // 1022 Nivea Deodorant Extra Brightening Anti-Perspirant Roll-On 50ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10049014-front-zoom.jpg",
  // 1023 Nelapine Nifedipine 20mg 1 Sustained-Release Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50008325-front-zoom.jpg",
  // 1024 Bioderm Soap Clean White 135g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10024641-front-zoom.jpg",
  // 1025 Nivea Deodorant Brightening Hokkaido Rose Essence Anti-Perspirant Roll-On 50ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50016929-front-zoom.jpg",
  // 1026 Durex Condoms Sensual Strawberry 3s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50010537-front-zoom.jpg",
  // 1027 IPI Salicylic Acid 50ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10028747-front-zoom.jpg",
  // 1028 Neurobion B Vitamins B1 + B6 + B12 Tablet 30s Nerve Care  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50015669-front-zoom.jpg",
  // 1029 Iberet Folic 500 Multivitamins + Iron with Folic Acid 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10069790-front-zoom.jpg",
  // 1030 Sofeel Jumbo Travel Value Pack 3 Sets 150 Sheets  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10056319-front-zoom.jpg",
  // 1031 Selsun Blue Pro X Extra Strength Anti-Dandruff Shampoo 200ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50047311-front-zoom.jpg",
  // 1032 Salonpas Medicated Patch 40s x 1  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50011405-front-zoom.jpg",
  // 1033 Fisherman's Friend Menthol 3mg Sugar-Free Lozenge  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10093483-front-zoom.jpg",
  // 1034 Katinko Camphor + Menthol + Methyl Salicylate Ointment 5g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10003102-front-zoom.jpg",
  // 1035 Midol 200mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10000493-front-zoom.jpg",
  // 1036 Strepsils Cool Sensation 8s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10045574-front-zoom.jpg",
  // 1037 Erceflora Gut Restore 10s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50047928-front-zoom.jpg",
  // 1038 Trivan Plus Valsartan 160mg + Amlodipine 10mg Tablet (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50050161-front-zoom.jpg",
  // 1039 Luxe Organix Niacinamide + Arbutin Healthy Light Whitening Serum Perfume Infused Body Wash 1000g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50048559-front-zoom.jpg",
  // 1040 Biore UV Aqua Rich Watery Essence SPF50+ PA++++ 15g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50047281-front-zoom.jpg",
  // 1041 Katinko Nasal Inhaler 2ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50034207-front-zoom.jpg",
  // 1042 Watsons Mint Round Thread Dental Floss Picks 50s x 1 Box  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50014230-front-zoom.jpg",
  // 1043 Luxe Organix Premium Keratin Castor Oil Treatment 250ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50018337-front-zoom.jpg",
  // 1044 Clium CRX Husk Mango Flavor 30s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50014053-front-zoom.jpg",
  // 1045 Transmetil Ademetionine 500mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10099424-front-zoom.jpg",
  // 1046 Tempra Forte Paracetamol 500mg Tablet Tipid Pack  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10101065-front-zoom.jpg",
  // 1047 Dermaid Peppermint Foot Deodorant Spray 60ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10093109-front-zoom.jpg",
  // 1048 Irbevex 300 Irbesartan 300mg Tablet (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50055241-front-zoom.jpg",
  // 1049 Luxe Organix Bakuchiol + Niacinamide Nano Complex Serum Lotion SPF 50 200ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50052143-front-zoom.jpg",
  // 1050 Nivea Men Deodorant Black & White Original Anti-Perspirant Roll-On 50ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10093370-front-zoom.jpg",
  // 1051 Isomil Two 850g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50018717-front-zoom.jpg",
  // 1052 Ziac Bisoprolol Fumarate 5mg + Hydrochlorothiazide 6.25mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10028553-front-zoom.jpg",
  // 1053 Difflam Forte Throat Spray 3mg/ml 15ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10089166-front-zoom.jpg",
  // 1054 Minirin Desmopressin Acetate 100mcg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10089720-front-zoom.jpg",
  // 1055 Ritemed Ciprofloxacin 500mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10062019-front-zoom.jpg",
  // 1056 Luxe Organix Keratin Treatment Argan Oil 250ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50003382-front-zoom.jpg",
  // 1057 QuickFX Pimple Eraser 10g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10090454-front-zoom.jpg",
  // 1058 Miravast Atorvastatin + Amlodipine 10mg/5mg Film-Coated Tablet 100s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50052964-front-zoom.jpg",
  // 1059 Watsons Acne Patch Assorted 3cm  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50045558-front-zoom.jpg",
  // 1060 Risponz 2 2mg Film-Coated Tablet Antipsychotic (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50048432-front-zoom.jpg",
  // 1061 Medixon 4mg Tablet (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50042484-front-zoom.jpg",
  // 1062 Razine 500mg Tablet (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50047101-front-zoom.jpg",
  // 1063 Cildine 10mg Tablet (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50037919-front-zoom.jpg",
  // 1064 Zoresan 50mg Capsule (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50043964-front-zoom.jpg",
  // 1065 Telmitar 80mg Tablet (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50037446-front-zoom.jpg",
  // 1066 Domepa Methyldopa 250mg 1 Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50011369-front-zoom.jpg",
  // 1067 Modess All Night 16+4  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50011064-front-zoom.jpg",
  // 1068 Jardiance Duo Empagliflozin + Metformin HCl 5mg/1000mg 1 Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50009771-front-zoom.jpg",
  // 1069 Viacoram Perindopril Arginine + Amlodipine Besilate 3.5mg/2.5mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50009259-front-zoom.jpg",
  // 1070 Cetaphil Bright Healthy Radiance Brightness Reveal Bar 100g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50026316-front-zoom.jpg",
  // 1071 Rupafin Rupatadine Fumarate 10mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50009010-front-zoom.jpg",
  // 1072 Micardis Plus Telmisartan 80mg + Hydrochlorothiazide 25mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10083916-front-zoom.jpg",
  // 1073 Olzadin Olanzapine 10mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10093543-front-zoom.jpg",
  // 1074 Kenzar Plus Losartan Potassium 50mg + Hydrochlorothiazide 12.5mg Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10100936-front-zoom.jpg",
  // 1075 Isoptin Verapamil 80mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10000397-front-zoom.jpg",
  // 1076 Cardiosel Metoprolol Tartrate 50mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10000153-front-zoom.jpg",
  // 1077 Galvus Vildagliptin 50mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10070517-front-zoom.jpg",
  // 1078 Eliquis Apixaban 5mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10096749-front-zoom.jpg",
  // 1079 Montra Isosorbide Mononitrate 60mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10089186-front-zoom.jpg",
  // 1080 Vivalyte Sodium 230mg Potassium 200mg Total Carbohydrates 5g Sugar 4g Apple Flavor Bottle 250ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50006069-front-zoom.jpg",
  // 1081 Vicks VapoRub Pocket Pack 10g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10002929-front-zoom.jpg",
  // 1082 Betadine Povidone Iodine 7.5% Cleanser 60ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10000957-front-zoom.jpg",
  // 1083 Sisters Over Night Dry with Wings Cottony Comfort 340mm 4 Pads  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10092078-front-zoom.jpg",
  // 1084 Epiduo Adapalene + Benzoyl Peroxide 0.3%/2.5% Gel 15g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50020962-front-zoom.jpg",
  // 1085 Cherifer Forte Syrup 240ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10042549-front-zoom.jpg",
  // 1086 Ferlin Iron + Vitamin B-Complex Drops 15ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10000319-front-zoom.jpg",
  // 1087 Naturals Stevia + Monk Fruit Sugar Replacement 40 Sticks (80g)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50054692-front-zoom.jpg",
  // 1088 Nivea Sun Protect & Dry Touch SPF50 200ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50053608-front-zoom.jpg",
  // 1089 Physiogel Soothing Care A.I. Cream 50ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50046939-front-zoom.jpg",
  // 1090 Vicks Twinpack Inhaler Key Chain 2s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50032278-front-zoom.jpg",
  // 1091 Tempra Paracetamol 325mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10002835-front-zoom.jpg",
  // 1092 Watsons x Flower Tissue Box 100s 3ply x 3 Box  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50024422-front-zoom.jpg",
  // 1093 Vicks Camphor + Menthol Inhaler 0.5ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10002954-front-zoom.jpg",
  // 1094 Silka Green Papaya Whitening Soap 135g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50008936-front-zoom.jpg",
  // 1095 Myra Ultimate with Astaxanthin Box of 8s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50003303-front-zoom.jpg",
  // 1096 Funxion Pregabalin 150mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50004478-front-zoom.jpg",
  // 1097 Vicks VapoRub 25g with Ready Display Box  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10092701-front-zoom.jpg",
  // 1098 Body Treats Melon Hand Soap 750ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10046038-front-zoom.jpg",
  // 1099 Belo SunExpert Brightening Body Lotion SPF50 200ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50060051-front-zoom.jpg",
  // 1100 Pure Form Pre + Probiotic for Gut Health Capsule  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50054659-front-zoom.jpg"
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