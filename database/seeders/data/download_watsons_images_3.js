// ─────────────────────────────------------------------------------------------
// Saves every Watsons product image used by watsons_products_3.php
// (batch 3: links 201–300).
//
// HOW TO USE
//  1. In Chrome/Edge open this page (any image on that host works):
//       https://medias.watsons.com.ph/publishing/10086858-2OlRPFsa-6fI3NOGM-zoom.png?version=1791514982
//  2. Press F12 -> Console. If asked, type  allow pasting  and press Enter.
//  3. Paste this whole script and press Enter.
//  4. If the browser asks to "allow multiple downloads", click Allow.
//  5. Move all downloaded files into:  database/seeders/data/images/
//     (keep their original names - the seeder finds them by file name)
//  6. Run:  php artisan db:seed
//
// It must be run on medias.watsons.com.ph so the requests are same-origin.
//
// 100 images, one per product. Every URL is the "-zoom" image taken from the
// product page itself (links 201–280), EXCEPT the two marked UNVERIFIED below
// (#207 Fenostat and #219 Dr. Wong's Sulfur Soap): their pages did not show an
// image link when I read them, so those two follow the usual Watsons naming
// pattern and may fail with HTTP 404. If they do, open the product page, save
// the product image by hand into database/seeders/data/images/ (the seeder
// finds it by the product code in the file name: 50017703 / 10095811). The
// products still seed fine without an image.
//
// #281–#300 (and #261–#280) are ALL UNVERIFIED (pages not opened): they follow the usual Watsons
// naming pattern and many may fail with HTTP 404. For any that fail, save the image
// by hand into database/seeders/data/images/ (the seeder finds it by product code).
// ─────────────────────────────────────────────────────────────────────────────
const urls = [
  // 201 Medicol Ibuprofen
  "https://medias.watsons.com.ph/publishing/10086858-2OlRPFsa-6fI3NOGM-zoom.png?version=1791514982",
  // 202 Ritemed Dicycloverine
  "https://medias.watsons.com.ph/publishing/WTCPH-50043534-front-zoom.jpg?version=1734330754",
  // 203 Maxi-Peel #3
  "https://medias.watsons.com.ph/publishing/WTCPH-10010085-front-zoom.jpg?version=1734044638",
  // 204 Tamplus
  "https://medias.watsons.com.ph/publishing/TAMPLUS%20CAP%20400MCG5MG%20X30_10093549-G4gkWbFo-zoom.jpg?version=1766388906",
  // 205 Rosucol 20mg
  "https://medias.watsons.com.ph/publishing/ROSUCOL%20TAB%2020MG%20X30_10093547-hMgMOSab-zoom.jpg?version=1766375992",
  // 206 Nizoral Shampoo 6ml
  "https://medias.watsons.com.ph/publishing/Nizoral%20Shampoo%206ml_Image1_10001569-Fim12ATP-zoom.png?version=1772605592",
  // 207 Fenostat  (UNVERIFIED - see note above)
  "https://medias.watsons.com.ph/publishing/WTCPH-50017703-front-zoom.jpg",
  // 208 Folicard
  "https://medias.watsons.com.ph/publishing/WTCPH-10057218-front-zoom.jpg?version=1734009654",
  // 209 pH Care Feminine Wash
  "https://medias.watsons.com.ph/publishing/WTCPH-10088084-front-1xZUBykw-zoom.png?version=1791514995",
  // 210 Aldactone 25mg
  "https://medias.watsons.com.ph/publishing/WTCPH-10000015-front-zoom.jpg?version=1734053774",
  // 211 Pred
  "https://medias.watsons.com.ph/publishing/WTCPH-10007654-front-zoom.jpg?version=1734070401",
  // 212 Yaz
  "https://medias.watsons.com.ph/publishing/WTCPH-10075143-front-zoom.jpg?version=1734057151",
  // 213 Hidrasec
  "https://medias.watsons.com.ph/publishing/WTCPH-10056913-front-zoom.jpg?version=1734053745",
  // 214 Glucophage XR 750
  "https://medias.watsons.com.ph/publishing/WTCPH-10081578-front-zoom.jpg?version=1734020827",
  // 215 Watsons Generics Ambroxol
  "https://medias.watsons.com.ph/publishing/10096372-rUKYlQDo-04MEyHGY-zoom.png?version=1793289794",
  // 216 Lipitor 10mg
  "https://medias.watsons.com.ph/publishing/WTCPH-10001378-front-zoom.jpg?version=1734052720",
  // 217 Serenata 50mg
  "https://medias.watsons.com.ph/publishing/WTCPH-BP_10100926-front-zoom.jpg?version=1721948131",
  // 218 Ezetrol
  "https://medias.watsons.com.ph/publishing/WTCPH-10035617-front-zoom.jpg?version=1734020776",
  // 219 Dr. Wong's Sulfur Soap 80g  (UNVERIFIED - see note above)
  "https://medias.watsons.com.ph/publishing/WTCPH-10095811-front-zoom.jpg",
  // 220 Hygienix Alcohol 500ml
  "https://medias.watsons.com.ph/publishing/HYGIENIX_image%20001_50012567-r2WdGJKg-zoom.jpg?version=1761127778",
  // 221 Ceelin Ascorbic Acid + Zinc Syrup 250ml
  "https://medias.watsons.com.ph/publishing/10079470-XixqZSRQ-vWNkpBlK-zoom.png?version=1791514952",
  // 222 Indirin Propranolol HCl 40mg 1 Film-Coated Tablet
  "https://medias.watsons.com.ph/publishing/WTCPH-50005343-front-zoom.jpg?version=1734037298",
  // 223 TDL Paracetamol + Tramadol 325mg/37.5mg 1 Film-Coated Tablet
  "https://medias.watsons.com.ph/publishing/WTCPH-10102407-front-zoom.jpg?version=1734023041",
  // 224 Depamax Valproic Acid 500mg 1 Tablet
  "https://medias.watsons.com.ph/publishing/WTCPH-50011097-front-zoom.jpg?version=1734051370",
  // 225 Platz Clopidogrel 75mg 1 Tablet
  "https://medias.watsons.com.ph/publishing/WTCPH-50000311-front-zoom.jpg?version=1734037302",
  // 226 Menelat Mirtazapine 30mg 1 Film-Coated Tablet
  "https://medias.watsons.com.ph/publishing/WTCPH-10093074-front-zoom.jpg?version=1734019900",
  // 227 Advil Ibuprofen 200mg 10 Softgel Capsules
  "https://medias.watsons.com.ph/publishing/10086859-DdVUSq12-zoom.png?version=1762531900",
  // 228 BD Syringe Pentapoint 4mm 32G x 1 Syringe
  "https://medias.watsons.com.ph/publishing/WTCPH-10100540-front-zoom.jpg?version=1734010451",
  // 229 Amvasc BE Amlodipine Besilate 10mg 1 Tablet
  "https://medias.watsons.com.ph/publishing/WTCPH-10067004-front-YOLTho9a-zoom.png?version=1791514936",
  // 230 Vaneular Vitamin B Complex
  "https://medias.watsons.com.ph/publishing/10000873-MR4wJrw4-qZQ3T3ZS-zoom.png?version=1791514842",
  // 231 Siverol Tramadol Hydrochloride 50mg
  "https://medias.watsons.com.ph/publishing/WTCPH-10003253-front-nvVpgiwp-zoom.png?version=1791514842",
  // 232 Advil Ibuprofen 200mg Sold Per Piece
  "https://medias.watsons.com.ph/publishing/WTCPH-10086860-front-zoom.jpg?version=1734008007",
  // 233 Sedazine Hydroxyzine Hydrochloride 10mg 1 Film-Coated Tablet
  "https://medias.watsons.com.ph/publishing/WTCPH-50037382-front-zoom.jpg?version=1734143710",
  // 234 Kozta Sitagliptin 100mg 1 Tablet
  "https://medias.watsons.com.ph/publishing/WTCPH-50046707-front-cs1nzoea-zoom.png?version=1791515233",
  // 235 Ritemed Metoprolol 100mg 1 Tablet
  "https://medias.watsons.com.ph/publishing/WTCPH-10090917-front-zoom.jpg?version=1734069605",
  // 236 Fortima Film Coated 30 Tablets
  "https://medias.watsons.com.ph/publishing/WTCPH-50031339-front-BZ0krjiq-zoom.png?version=1791515186",
  // 237 Dayvigo Lemborexant 5mg Film-Coated Tablet
  "https://medias.watsons.com.ph/publishing/RX1_50042924-NUEOEMyo-zoom.jpg?version=1734149617",
  // 238 Folicard Vitamin B Complex + Folic Acid
  "https://medias.watsons.com.ph/publishing/WTCPH-10082751-front-zoom.jpg?version=1734013927",
  // 239 Virbez Irbesartan 150mg 1 Tablet
  "https://medias.watsons.com.ph/publishing/WTCPH-50017697-front-zoom.jpg?version=1734051696",
  // 240 Polynerv Vit B1 + B12 + B6 500mg/1mg/250mg 1 Film-Coated Tablet
  "https://medias.watsons.com.ph/publishing/WTCPH-10002606-front-zoom.jpg?version=1734070415",
  // 241 Celeteque Hydration Facial Wash 250ml
  "https://medias.watsons.com.ph/publishing/WTCPH-10081419-front-o3r31Nhb-zoom.png?version=1791514959",
  // 242 Neobloc Metoprolol 50mg
  "https://medias.watsons.com.ph/publishing/WTCPH-10000559-front-zoom.jpg?version=1734070501",
  // 243 Nutrabliss Biotin 5000mcg
  "https://medias.watsons.com.ph/publishing/nutrabliss_50030607_01-qq9wSM5m-zoom.jpg?version=1765263149",
  // 244 Biofitea Tea
  "https://medias.watsons.com.ph/publishing/10040293-hgLtEboE-LkxqrOep-zoom.png?version=1793289727",
  // 245 Celeteque Brightening Facial Wash 75ml
  "https://medias.watsons.com.ph/publishing/WTCPH-10088544-front-zoom.jpg?version=1733992364",
  // 246 Trust pills
  "https://medias.watsons.com.ph/publishing/Trust%20Pill%20%2810001428%29%20-front-yCrewv21-zoom.png?version=1745920731",
  // 247 Mosegor Vita
  "https://medias.watsons.com.ph/publishing/WTCPH-10002443-front-zoom.jpg?version=1734070250",
  // 248 Saridon 9+1
  "https://medias.watsons.com.ph/publishing/Saridon_Image1_50055317-sFYRKedo-zoom.jpg?version=1789530837",
  // 249 Ritemed Atorvastatin 40mg
  "https://medias.watsons.com.ph/publishing/WTCPH-10090920-front-zoom.jpg?version=1734053780",
  // 250 Ritemed Rosuvastatin 10mg
  "https://medias.watsons.com.ph/publishing/WTCPH-50020331-front-zoom.jpg?version=1734126145",
  // 251 Decolgen No-Drowse
  "https://medias.watsons.com.ph/publishing/50016092-d0AF0UuV-W5Mitpx9-zoom.png?version=1791515155",
  // 252 Tascit
  "https://medias.watsons.com.ph/publishing/WTCPH-50006201-front-HKMk6QXM-zoom.png?version=1791515117",
  // 253 Primavix
  "https://medias.watsons.com.ph/publishing/50034259-NwEsyFjS-zoom.png?version=1762498758",
  // 254 Watsons Generics Celecoxib
  "https://medias.watsons.com.ph/publishing/WTCPH-50035419-front-zoom.jpg?version=1734133797",
  // 255 Calcidin Plus
  "https://medias.watsons.com.ph/publishing/WTCPH-50055316-front-zoom.jpg?version=1766537431",
  // 256 Micardis 80mg
  "https://medias.watsons.com.ph/publishing/WTCPH-10086462-front-zoom.jpg?version=1734070608",
  // 257 Urinorm 80mg
  "https://medias.watsons.com.ph/publishing/WTCPH-10095654-front-WZdBJBN5-zoom.png?version=1791515054",
  // 258 Kojie San Trio Pack
  "https://medias.watsons.com.ph/publishing/IMAGE%201_10088217-Mwzs2BEB-zoom.png?version=1765250718",
  // 259 Lanistor
  "https://medias.watsons.com.ph/publishing/WTCPH-50043091-front-zoom.jpg?version=1734164248",
  // 260 Nebil
  "https://medias.watsons.com.ph/publishing/WTCPH-50008624-front-zoom.jpg?version=1734067577",
  // 261 Watsons Generics Evening Primrose Oil Softgel Capsule 1400mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50027633-front-zoom.jpg",
  // 262 Bewell-C C Plus Calcium  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10094468-front-zoom.jpg",
  // 263 Finarid Finasteride 5mg Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50044975-front-zoom.jpg",
  // 264 Caltrate Advance  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10034994-front-zoom.jpg",
  // 265 Watsons Dental Floss Anti-Bacterial Agent Fluoride Green Tea 10m  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10088757-front-zoom.jpg",
  // 266 Watsons Generics Allopurinol 300mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50053649-front-zoom.jpg",
  // 267 Ponstan Mefenamic Acid 500mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10002609-front-zoom.jpg",
  // 268 Propan TLC Syrup 60ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10074167-front-zoom.jpg",
  // 269 Lipitor 20mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10001379-front-zoom.jpg",
  // 270 Qtipine Quetiapine Fumarate 25mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10100925-front-zoom.jpg",
  // 271 Rosufen 160mg/20mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50043743-front-zoom.jpg",
  // 272 Amlodine Plus Amlodipine Besilate 5mg Losartan 50mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10099064-front-zoom.jpg",
  // 273 Daflon Diosmin Hesperidin 450mg/50mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10001999-front-zoom.jpg",
  // 274 Xyzal Levocetirizine Dihydrochloride 5mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10034309-front-zoom.jpg",
  // 275 Asmacaire Salbutamol Sulfate 1mg/1ml 30 Nebules  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10099073-front-zoom.jpg",
  // 276 Celeteque Dermoscience Hydration Facial Moisturizer 50ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10081410-front-zoom.jpg",
  // 277 Valianz Amlodipine 5mg Valsartan 80mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10098537-front-zoom.jpg",
  // 278 Ritemed Metformin 850mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10038430-front-zoom.jpg",
  // 279 Fern-C 500mg/1000 IU/10mg Capsule  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50036609-front-zoom.jpg",
  // 280 Althea Cyproterone Acetate Ethinyl Estradiol 2mg/35mcg Tablets x1 Box  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10045564-front-zoom.jpg",
  // 281 Norgesic Orphenadrine Citrate Paracetamol 35mg/450mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10000580-front-zoom.jpg",
  // 282 Crestor Rosuvastatin 5mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10066406-front-zoom.jpg",
  // 283 Erceflora Gut Defense 10s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50048050-front-zoom.jpg",
  // 284 Adalat Nifedipine 30mg GITS MR 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10001723-front-zoom.jpg",
  // 285 Sisters Ultra Comfort 245mm With Wings 6 Thin Pads  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10040738-front-zoom.jpg",
  // 286 Pred Prednisone 20mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10054454-front-zoom.jpg",
  // 287 Vasacad-S 200mg Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50058163-front-zoom.jpg",
  // 288 Watsons Generics Apixaban 2.5mg 1 Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50049722-front-zoom.jpg",
  // 289 pH Care Floral Clean Wipes 10s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10085174-front-zoom.jpg",
  // 290 Liveraide Silymarin  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10034389-front-zoom.jpg",
  // 291 Sidapvia 10mg/100mg Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50057233-front-zoom.jpg",
  // 292 Celeteque Hydration Gentle Exfoliating Facial Wash 60ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10081420-front-zoom.jpg",
  // 293 Centrum Advance Tablet 1pc  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10101093-front-zoom.jpg",
  // 294 Bewell-C Sodium Ascorbate 500mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10081064-front-zoom.jpg",
  // 295 Algesia Tramadol HCl Paracetamol 37.5mg/325mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10085025-front-zoom.jpg",
  // 296 Allerzet Levocetirizine Dihydrochloride 5mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10093469-front-zoom.jpg",
  // 297 Panamed KN95 W Mask 1s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50027586-front-zoom.jpg",
  // 298 Remoston Blumea Balsamifera L. (Sambong) Leaf 500mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50039972-front-zoom.jpg",
  // 299 Lee MF Rhino Herbal Tea  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10087607-front-zoom.jpg",
  // 300 Calprem Calcium Carbonate Calcitriol Zinc Picolinate 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50033723-front-zoom.jpg"
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