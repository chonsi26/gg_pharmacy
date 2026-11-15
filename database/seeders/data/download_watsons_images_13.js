// ─────────────────────────────────────────────────────────────────────────────
// Saves every Watsons product image used by watsons_products_13.php
// (batch 13: links 1201–1300).
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
// 100 images, one per product (#1201–#1300).
//   All are UNVERIFIED: the product pages did not show image links, so they follow the usual
//   WTCPH-<product code>-front-zoom.jpg pattern and many may fail with HTTP 404 (Watsons often appends a
//   ?version= suffix or a random token to the real file name). For any that fail, open the product page,
//   right-click the image and save it by hand into database/seeders/data/images/ (the seeder finds it by the
//   8-digit product code in the file name). The products still seed fine without an image.
// ─────────────────────────────────────────────────────────────────────────────
const urls = [
  // 1201 Nivea Men Deodorant Deep Espresso Anti-Perspirant Roll-On 50ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50014609-front-zoom.jpg",
  // 1202 Rexona Powder Dry Deo Roll-On 25ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10086918-front-zoom.jpg",
  // 1203 Pond's Gluta-Niacinamide Sunscreen SPF50 PA++++ UV Bright 50ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50043300-front-zoom.jpg",
  // 1204 Cetaphil Bar UProtect AS G 127g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50038641-front-zoom.jpg",
  // 1205 Dermaid C Ginger and Lemon Lozenges 8s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50036259-front-zoom.jpg",
  // 1206 Absolute Distilled Drinking Water 1000ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10092999-front-zoom.jpg",
  // 1207 Klean Facemasks 3Ply Earloop Disposable 5 Masks  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50017092-front-zoom.jpg",
  // 1208 Dermaid Calamine + Zinc Oxide 60ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50006412-front-zoom.jpg",
  // 1209 Nivea Moisturizing Creme 30ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10010391-front-zoom.jpg",
  // 1210 Hialid Sodium Hyaluronate 0.1% Ophthalmic Solution 5ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10083933-front-zoom.jpg",
  // 1211 Potencee Ascorbic Acid 1000mg Forte Tablet 30 Tablets  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10064048-front-zoom.jpg",
  // 1212 Orita 3-Ply 4 Rolls Bathroom Tissue  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10084721-front-zoom.jpg",
  // 1213 Garnier Bright Complete SPF36 Multi-Action Whitening Serum Cream 7ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10094025-front-zoom.jpg",
  // 1214 PH Care Daily Feminine Wash Ultra Pure 150ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50054286-front-zoom.jpg",
  // 1215 Neurobion Vitamins B1 + B6 + B12 (9+1) Value Pack  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50040736-front-zoom.jpg",
  // 1216 Orahex Oral Rinse Mint Alcohol-Free 120ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10083539-front-zoom.jpg",
  // 1217 Aveeno Skin Relief Body Lotion 532ml - Lotion for Sensitive/Dry/Itchy Skin, Eczema, Atopic Dermatitis  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50038765-front-zoom.jpg",
  // 1218 Potencee Zn Advance 500mg/10mg/1000 IU Capsule  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50032109-front-zoom.jpg",
  // 1219 Zovirax Acyclovir 200mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10002892-front-zoom.jpg",
  // 1220 Modess All Night Ultra Thin Extra Long with Wings Sanitary Napkin 10s Med-Heavy Flow  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10087754-front-zoom.jpg",
  // 1221 Humer Sodium Chloride 150ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10102296-front-zoom.jpg",
  // 1222 Klenzit Adapalene 1mg/g 15g Gel  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10098587-front-zoom.jpg",
  // 1223 Macuvit Lutein + Alpha Lipoic Acid + Zeaxanthin Vitamins and Minerals (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10099202-front-zoom.jpg",
  // 1224 Charmee Breathable Pantyliners Unscented 20 Liners  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10055070-front-zoom.jpg",
  // 1225 Silka Whitening Herbal Soap Enriched with Vitamin E 135g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10088247-front-zoom.jpg",
  // 1226 Liveraide 9+1 Tipid Pack  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50051515-front-zoom.jpg",
  // 1227 Oxecure Acne Clear Oil Control Moisturizer 10ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50042223-front-zoom.jpg",
  // 1228 Luxe Organix Miracle Solutions AHA BHA Serum 30ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50038826-front-zoom.jpg",
  // 1229 Vastarel OD Trimetazidine Dihydrochloride 80mg 1 Capsule (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50035882-front-zoom.jpg",
  // 1230 Ozempic Semaglutide Solution for Injection 1mg/0.74ml (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50031125-front-zoom.jpg",
  // 1231 Summit Natural Drinking Water  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10089655-front-zoom.jpg",
  // 1232 Luxe Organix Keratin Manuka Honey Treatment 250ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50018335-front-zoom.jpg",
  // 1233 Lupimet Glimepiride + Metformin Hydrochloride 2mg/500mg 1 Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50006772-front-zoom.jpg",
  // 1234 Watsons Generics Ciprofloxacin 500mg Tablet (Sold Per Piece) (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10097003-front-zoom.jpg",
  // 1235 Sumapen Phenoxymethylpenicillin Potassium 500mg (Sold Per Piece) (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10000799-front-zoom.jpg",
  // 1236 Heraclene Dibencozide 3mg (Sold Per Piece) (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10047509-front-zoom.jpg",
  // 1237 Charmee Pantyliners Go Girl 20+2 Free  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10061615-front-zoom.jpg",
  // 1238 Watsons Fresh Mint Mouthwash 500ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10078327-front-zoom.jpg",
  // 1239 Myra E Whitening Glow Body Lotion 50ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50059018-front-zoom.jpg",
  // 1240 TRESemmé Keratin Smooth Bond Building Treatment Mask 180ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50054912-front-zoom.jpg",
  // 1241 Salveo ODT Olanzapine Orodispersible Tablet 5mg Antipsychotic (Sold Per Piece) (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50047882-front-zoom.jpg",
  // 1242 Aldax 50mg Tablet (Sold Per Piece) (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50050716-front-zoom.jpg",
  // 1243 Watsons One-Step Pregnancy Test Stick 1s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50034920-front-zoom.jpg",
  // 1244 Oral-B Power Electric Toothbrush Pro 100 Cross Action Free Travel Case  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50044077-front-zoom.jpg",
  // 1245 Potencee Ascorbic Acid + Zinc + Cholecalciferol (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50032103-front-zoom.jpg",
  // 1246 Cetaphil Baby Daily Lotion 400ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50019589-front-zoom.jpg",
  // 1247 Colgate Triple Action Anti-Cavity Family Toothpaste 175g Twin Pack  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50015156-front-zoom.jpg",
  // 1248 Sangobion Ferrous Gluconate + Vitamins + Minerals (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10002702-front-zoom.jpg",
  // 1249 Luxe Organix High Protection Aqua PDRN Soft Glow Serum Sunscreen SPF50 40g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50059261-front-zoom.jpg",
  // 1250 Ezoprole 40mg Capsule (Sold Per Piece) (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50044558-front-zoom.jpg",
  // 1251 Apollo Sebo de Macho 25g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50019273-front-zoom.jpg",
  // 1252 Coumadin Warfarin Na 1mg 1 Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10066431-front-zoom.jpg",
  // 1253 Luxe Organix Nail and Cuticle Castor Serum 3ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50034535-front-zoom.jpg",
  // 1254 Tiger Balm Warm Plaster 2s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50007792-front-zoom.jpg",
  // 1255 Symbicort Budesonide 160mcg + Formoterol Fumarate Dihydrate 4.5mcg Metered Dose Inhaler 1 Dose (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50009518-front-zoom.jpg",
  // 1256 Ambesyl Amlodipine 10mg 1 Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10077508-front-zoom.jpg",
  // 1257 Godex Carnitine Orotate + Pyridoxine HCl + Cyanocobalamin + Amino Acids + Adenine HCl + Riboflavin (Sold Per Piece) (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10004060-front-zoom.jpg",
  // 1258 Luxe Organix Miracle Solutions Acne Derm Toner 150ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50004427-front-zoom.jpg",
  // 1259 Kastair Montelukast Sodium 10mg 1 Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10049962-front-zoom.jpg",
  // 1260 IPI Aceite Manzanilla 50ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10002374-front-zoom.jpg",
  // 1261 Katinko Methyl Salicylate + Menthol + Camphor  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10003101-front-zoom.jpg",
  // 1262 Disudrin Phenylephrine HCl 2.5mg + Chlorphenamine Maleate 0.5mg Drops 10ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10000246-front-zoom.jpg",
  // 1263 pH Care Feminine Wash Natural Protection 150ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10098517-front-zoom.jpg",
  // 1264 Nivea Deodorant Extra Brightening Anti-Perspirant Spray 150ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10042715-front-zoom.jpg",
  // 1265 Chummy Baby Natural n Mild Gentle Baby Wet Wipes 100 Sheets  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50053582-front-zoom.jpg",
  // 1266 Kleenex Facial Tissue 6 Pack  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50053539-front-zoom.jpg",
  // 1267 Feliz S Film-Coated Tablet 20mg (Sold Per Piece) (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50042427-front-zoom.jpg",
  // 1268 Tiger Balm Plaster Large Warm 2s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50047340-front-zoom.jpg",
  // 1269 Watsons Love My Smile Dental Floss Shred Resistant Refill (62% Less Plastic)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50040827-front-zoom.jpg",
  // 1270 Charmee Panty Liner Powder Cool Longer 20s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50006142-front-zoom.jpg",
  // 1271 Celcoxx Celecoxib 100mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10059879-front-zoom.jpg",
  // 1272 Kojie San Skin Lightening Soap Family Beauty Pack 135g x 6  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50008935-front-zoom.jpg",
  // 1273 Soniphen Diphenhydramine Hydrochloride 50mg (Sold Per Piece) (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10095976-front-zoom.jpg",
  // 1274 Watsons Generics Guaifenesin + Salbutamol 100mg/2mg (Sold Per Piece) (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10100666-front-zoom.jpg",
  // 1275 Koolfever Cooling Gel Extra Cool  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10101740-front-zoom.jpg",
  // 1276 Celeteque Advanced Anti-Aging Alcohol-Free Toner 125ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50000558-front-zoom.jpg",
  // 1277 Nivea Deodorant Pearl & Beauty Anti-Perspirant Roll-On 50ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10057005-front-zoom.jpg",
  // 1278 Myra E Hydrating Glow Body Lotion 400ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50059017-front-zoom.jpg",
  // 1279 Ritemed Acetylcysteine 600mg Granules for Oral Solution Mucolytic (Sold Per Piece) (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50055278-front-zoom.jpg",
  // 1280 Gaviscon Double Action Sachet 10ml (Buy 7 Get 1 Free)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50052374-front-zoom.jpg",
  // 1281 Physiogel Soothing Care A.I. Lotion 200ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50046942-front-zoom.jpg",
  // 1282 Kojie San Skin Lightening Soap with Hydromoist 135g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50011802-front-zoom.jpg",
  // 1283 Cetaphil Gentle Foaming Cleanser 236ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50011348-front-zoom.jpg",
  // 1284 Crestor Rosuvastatin 20mg 1 Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10032475-front-zoom.jpg",
  // 1285 Neobloc Metoprolol Tartrate 100mg (Sold Per Piece) (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10000560-front-zoom.jpg",
  // 1286 Green Cross Isopropyl Alcohol 70% Solution 500ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10047291-front-zoom.jpg",
  // 1287 Garnier Vitamin C Dry Touch Cream 30ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50056316-front-zoom.jpg",
  // 1288 Nivea Luminous630 Skin Glow Serum 30ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50055143-front-zoom.jpg",
  // 1289 Watsons Generics Bisacodyl 5mg 1 Enteric-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10099078-front-zoom.jpg",
  // 1290 Colgate Optic White Purple Whitening Toothpaste 100g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50046511-front-zoom.jpg",
  // 1291 Aveeno Dermexa Cream 200ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50009231-front-zoom.jpg",
  // 1292 Aveeno Skin Relief Moisturizing Lotion 71ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50010818-front-zoom.jpg",
  // 1293 Betaloc Metoprolol 50mg 1 Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10092940-front-zoom.jpg",
  // 1294 Coveram Perindopril Arginine 5mg + Amlodipine 5mg 1 Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10083936-front-zoom.jpg",
  // 1295 Luxe Organix Keratin 10in1 Hair Elixir Leave-In Treatment 100ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50003381-front-zoom.jpg",
  // 1296 Ritemed Clonidine 150mcg 1 Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10093545-front-zoom.jpg",
  // 1297 Cybelle Cyproterone Acetate + Ethinylestradiol 2mg/35mcg x 21 Film-Coated Tablets Box (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10092729-front-zoom.jpg",
  // 1298 Hidrasec Racecadotril 30mg 1 Children Oral Powder (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10055699-front-zoom.jpg",
  // 1299 Strepsils Dry Cough 6s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10073721-front-zoom.jpg",
  // 1300 Watsons Scented Nourishing Lily and Jasmine Conditioner 1L  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50000267-front-zoom.jpg"
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