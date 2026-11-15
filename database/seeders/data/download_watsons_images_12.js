// ─────────────────────────────────────────────────────────────────────────────
// Saves every Watsons product image used by watsons_products_12.php
// (batch 12: links 1101–1200).
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
// 100 images, one per product (#1101–#1200).
//   #1136 is VERIFIED (real URL taken from the live product page, with its ?version= suffix).
//   All the others (#1181–#1200 included) are UNVERIFIED: the product pages did not show image links, so they follow the usual
//   WTCPH-<product code>-front-zoom.jpg pattern and many may fail with HTTP 404 (Watsons often appends a
//   ?version= suffix or a random token to the real file name). For any that fail, open the product page,
//   right-click the image and save it by hand into database/seeders/data/images/ (the seeder finds it by the
//   8-digit product code in the file name). The products still seed fine without an image.
// ─────────────────────────────────────────────────────────────────────────────
const urls = [
  // 1101 Urofix Dutasteride + Tamsulosin HCl 500mcg/400mcg 30 Capsules  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50052445-front-zoom.jpg",
  // 1102 Telmez HCT Telmisartan + Hydrochlorothiazide 80mg/12.5mg Tablet (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50046928-front-zoom.jpg",
  // 1103 Vaseline Healthy Bright Gluta-Hya Serum Burst Lotion Dewy Radiance Bundle 330ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50033816-front-zoom.jpg",
  // 1104 Garnier Bright Complete Anti-Acne 3-in-1 Cleanser 100ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50042386-front-zoom.jpg",
  // 1105 Solmux Advance Carbocisteine + Zinc 500mg/10mg 60ml Suspension  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50041927-front-zoom.jpg",
  // 1106 Aveeno Daily Moisturizing Body Wash 1L  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50030273-front-zoom.jpg",
  // 1107 Miine Exfoliating Bath Gloves Pair  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10042644-front-zoom.jpg",
  // 1108 Luxe Organix Castor Oil Mascara Serum 10ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50003283-front-zoom.jpg",
  // 1109 Arcoxia Etoricoxib 120mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10034310-front-zoom.jpg",
  // 1110 Vivalyte Plus Electrolyte Drink with Zinc Orange Flavor 230ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50006070-front-zoom.jpg",
  // 1111 Appebon Vitamin B-Complex + Iron + Lysine + Buclizine HCl Kids Syrup 250ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10084884-front-zoom.jpg",
  // 1112 Nivea Extra Bright 5-in-1 Pore Minimising Toner 200ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10094339-front-zoom.jpg",
  // 1113 Watsons Love My Smile Dental Floss Picks Flat Thread 300s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50037000-front-zoom.jpg",
  // 1114 Vitress Hair Freshener Meadow Zest 50ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50031511-front-zoom.jpg",
  // 1115 Met-Tathione Softcap with DHA 500mg 30 Capsules  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50030466-front-zoom.jpg",
  // 1116 Approvel Irbesartan 300mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10003577-front-zoom.jpg",
  // 1117 Neozep Syrup Reformulated 60ml (Phenylephrine HCl + Chlorphenamine Maleate + Paracetamol)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10000569-front-zoom.jpg",
  // 1118 Snailwhite Whipp Soap 80g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50025329-front-zoom.jpg",
  // 1119 Metfor PR 1000 Metformin Hydrochloride 1g Prolonged-Release Tablet (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50055717-front-zoom.jpg",
  // 1120 Watsons Invigorating Wet Wipes Unscented 10 Wipes x 3 Pack  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50050057-front-zoom.jpg",
  // 1121 Dove Radiant+Care Serum Bar Triples 50x Niacinamide + Vegan Collagen 90g x 3  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50048100-front-zoom.jpg",
  // 1122 Febuday Febuxostat 40mg Film-Coated Tablet (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50044467-front-zoom.jpg",
  // 1123 Luxe Organix 5in1 UV Pro Whitening Serum Sunscreen SPF 100 PA 40ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50042079-front-zoom.jpg",
  // 1124 Zithromin Azithromycin Dihydrate 500mg 1 Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50027410-front-zoom.jpg",
  // 1125 Zemiglo Gemigliptin Tartrate Sesquihydrate 50mg 1 Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50016070-front-zoom.jpg",
  // 1126 Glucerna SR TC Choco 400g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50018716-front-zoom.jpg",
  // 1127 Luxe Organix Cica Rescue Gentle Brightening Cleanser 150g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50011790-front-zoom.jpg",
  // 1128 Vaseline Petroleum Jelly 106g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50000307-front-zoom.jpg",
  // 1129 Bactroban Mupirocin 20mg/g Ointment 5g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50008547-front-zoom.jpg",
  // 1130 Katialis Ointment Antibacterial/Antifungal 15g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10001059-front-zoom.jpg",
  // 1131 Reinin Gabapentin 100mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10062021-front-zoom.jpg",
  // 1132 Johnson's Baby Milk + Rice Baby Bath 200ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10019263-front-zoom.jpg",
  // 1133 Celeteque Dermo Science Acne Facial Moisturizer 50ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10088548-front-zoom.jpg",
  // 1134 Luxe Organix DermaPro 2in1 KP Bumps Corrector Smooth Restore Treatment Lotion 200ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50058981-front-zoom.jpg",
  // 1135 Nivea Derma Skin Clear Toner 200ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50052118-front-zoom.jpg",
  // 1136 Aminolog 60mg Tablet (Sold Per Piece)  (VERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50048076-front-zoom.jpg?version=1734339425",
  // 1137 Lubie Multi-Purpose Lube 10ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50033858-front-zoom.jpg",
  // 1138 Benadryl AH 25mg 4 Capsules Allergy Medicine Antihistamine (Diphenhydramine HCl)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50022664-front-zoom.jpg",
  // 1139 Clium CRX Husk Dalandan Flavor 1 Sachet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50014055-front-zoom.jpg",
  // 1140 Watsons Invigorating Wet Wipes Sakura Scented 3 x 10 Sheets  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10098999-front-zoom.jpg",
  // 1141 Vasalat Amlodipine 10mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10064412-front-zoom.jpg",
  // 1142 Cetaphil Pro AD Derma Skin Restoring Moisturizer 295ml for Eczema-Prone Skin  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10086498-front-zoom.jpg",
  // 1143 Cherifer Forte with Zinc 240ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10042551-front-zoom.jpg",
  // 1144 Vaseline Lip Therapy Rosy Lips Mini 0.25oz  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50020045-front-zoom.jpg",
  // 1145 Sisters Day Maxi Regular Flow with Wings Cottony Comfort 81 Pads  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10017366-front-zoom.jpg",
  // 1146 Lummarx Denture Cleansing Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50046169-front-zoom.jpg",
  // 1147 Nurtec Rimegepant 75mg ODT 8s (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50045422-front-zoom.jpg",
  // 1148 Luxe Organix Cica Soothing Gel 100ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50030461-front-zoom.jpg",
  // 1149 Snailwhite CC Sunscreen SPF 50 PA 6ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50030601-front-zoom.jpg",
  // 1150 Fun-G Tawas Deo Spray 100ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50005669-front-zoom.jpg",
  // 1151 United Tiki Tiki Drops 30ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10001239-front-zoom.jpg",
  // 1152 Koolfever Cooling Gel Adult 2 Sheets Pack  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10028333-front-zoom.jpg",
  // 1153 Med Guard Dental Flossers Individual 50s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10093673-front-zoom.jpg",
  // 1154 Celeteque Dermo Science Hydration Make-Up Remover Wipes 20 Sheets  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10088539-front-zoom.jpg",
  // 1155 Mounjaro KwikPen 7.5mg/0.6ml (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50058412-front-zoom.jpg",
  // 1156 Imodium 8s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50043376-front-zoom.jpg",
  // 1157 Gillette Blue 2 Flexi Disposable Razor 10+5s Pack  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50037599-front-zoom.jpg",
  // 1158 Sisters Panty Liner Mega Pack 36s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50008379-front-zoom.jpg",
  // 1159 Modess Regular Cottony Soft with Wings Sanitary Napkins 32s Regular Flow Fast Absorbing vs Leaks  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50031036-front-zoom.jpg",
  // 1160 Lactacyd Odor Block Daily Feminine Wash 250ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50003154-front-zoom.jpg",
  // 1161 Vaseline Petroleum Jelly 49g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50000306-front-zoom.jpg",
  // 1162 Inoflox Ofloxacin 200mg 1 Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10000381-front-zoom.jpg",
  // 1163 Eskinol Deep Cleanser Pimple Fighting 225ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10000667-front-zoom.jpg",
  // 1164 Luxe Organix DermaPro TXA Melasma Clear Exo-10 Exosome Bright Treatment Cream 150ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50058385-front-zoom.jpg",
  // 1165 Dove Bundle: Dove Radiant Care Deodorant Serum Deep Renew 45ml + Free Pore Smoothener Roll-On  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50033694-front-zoom.jpg",
  // 1166 Koolfever Kids Mint 2s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50029570-front-zoom.jpg",
  // 1167 Utrogestan Progesterone 200mg Vaginal (Sold Per Piece) (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50021029-front-zoom.jpg",
  // 1168 Buscopan Plus 500mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10000135-front-zoom.jpg",
  // 1169 Smooth Only the Purest Cotton Balls 150s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10095427-front-zoom.jpg",
  // 1170 Nurocer Piracetam 800mg 1 Film-Coated Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50006524-front-zoom.jpg",
  // 1171 Ritemed Omeprazole 20mg (Sold Per Piece) (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10090011-front-zoom.jpg",
  // 1172 Luxe Organix Hair Professionals 3in1 Dry Shampoo Volume Blowout Blissful Breeze 110ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50053732-front-zoom.jpg",
  // 1173 Gretor Ticagrelor 90mg Film-Coated Tablet (Sold Per Piece) (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50053240-front-zoom.jpg",
  // 1174 Abanvex Apixaban 2.5mg Film-Coated Tablet (Sold Per Piece) (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50052635-front-zoom.jpg",
  // 1175 Qvex 100mg Tablet (Sold Per Piece) (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50050695-front-zoom.jpg",
  // 1176 Ming's Pei Pa Koa Candy Value Pack  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50039376-front-zoom.jpg",
  // 1177 Luxe Organix Curl Define Intensive Hydration Daily Conditioner 210ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50042487-front-zoom.jpg",
  // 1178 Irbezyd Irbesartan 300mg 1 Film-Coated Tablet (Sold Per Piece) (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50037259-front-zoom.jpg",
  // 1179 Felodipine 5mg 1 Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10090203-front-zoom.jpg",
  // 1180 Combizar Losartan Potassium + Hydrochlorothiazide 100mg/25mg (Sold Per Piece) (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50001121-front-zoom.jpg",
  // 1181 Dermaid Aroma Oil Moisturizing Roll-On Lavender 10ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50011691-front-zoom.jpg",
  // 1182 Dailyvasc Amlodipine 5mg 1 Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10101595-front-zoom.jpg",
  // 1183 Eskinol Deep Cleanser Papaya Smooth White 225ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10032919-front-zoom.jpg",
  // 1184 Lipitor 80mg (Sold Per Piece) (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10025776-front-zoom.jpg",
  // 1185 Trust Quality Condom Natural 3s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10095657-front-zoom.jpg",
  // 1186 Alzor Olmesartan Medoxomil 40mg 1 Film-Coated Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10092479-front-zoom.jpg",
  // 1187 Cholinerv Citicoline Sodium 1g 1 Tablet (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10101597-front-zoom.jpg",
  // 1188 Orita Interfolded Paper Towel 150 Sheets  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10053468-front-zoom.jpg",
  // 1189 Sisters Night Plus Heavy Flow with Wings 270mm 8 Pads  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10040737-front-zoom.jpg",
  // 1190 PH Care Feminine Wash Floral Clean 150ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10088079-front-zoom.jpg",
  // 1191 Med Guard Toothbrush Medium  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10052952-front-zoom.jpg",
  // 1192 Fortima Film-Coated Tablet 40 Pieces  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50061400-front-zoom.jpg",
  // 1193 Adrylex Adapalene 1mg/g 15g Topical Gel (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50042652-front-zoom.jpg",
  // 1194 Avon Feelin Fresh Ultra Glutathione Quelch 55g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50050085-front-zoom.jpg",
  // 1195 A Bonne Spa Milk Salt Refill with Ziplock 300g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50032062-front-zoom.jpg",
  // 1196 ClariClear Nasal Spray 15ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50033504-front-zoom.jpg",
  // 1197 Luxe Organix Cica Rescue Gentle Brightening Derma Relief Moisturizer 50g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50038376-front-zoom.jpg",
  // 1198 PH Care Feminine Wash Delicate White 5ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50027901-front-zoom.jpg",
  // 1199 Nicorette Menthol Mint Nicotine Gum 2mg 15s - Quit Smoking  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50021832-front-zoom.jpg",
  // 1200 Garnier Clinical Serum Bright Complete 30ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50016004-front-zoom.jpg"
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