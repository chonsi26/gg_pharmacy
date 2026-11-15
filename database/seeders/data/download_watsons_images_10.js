// ─────────────────────────────------------------------------------------------
// Saves every Watsons product image used by watsons_products_10.php
// (batch 10: links 901–1000).
//
// HOW TO USE
//  1. In Chrome/Edge open this page (any image on that host works):
//       https://medias.watsons.com.ph/publishing/10001399%20%281%29-9t3HvBMR-zoom.png?version=1770198992
//  2. Press F12 -> Console. If asked, type  allow pasting  and press Enter.
//  3. Paste this whole script and press Enter.
//  4. If the browser asks to "allow multiple downloads", click Allow.
//  5. Move all downloaded files into:  database/seeders/data/images/
//     (keep their original names - the seeder finds them by file name)
//  6. Run:  php artisan db:seed
//
// It must be run on medias.watsons.com.ph so the requests are same-origin.
//
// 100 images, one per product (#901–#1000).
//   VERIFIED   (15): #901–#907, #909, #911–#915, #917, #920 - the exact "-zoom" image URL (with ?version=)
//   copied from the product page itself.
//   #921 (Julitam) is also VERIFIED (exact URL from the product page).
//   #922–#940 are UNVERIFIED: the pages could not be opened, so they follow the usual pattern and many may fail with HTTP 404;
//   save any that fail by hand into database/seeders/data/images/ (the seeder finds them by the 8-digit product code in the file name).
//   #941 (Siftus) and #942 (Pneumotyl) are VERIFIED (exact "-front-zoom" URL with ?version= from the product page).
//   #943–#970 are UNVERIFIED: they follow the usual WTCPH-<product code>-front-zoom.jpg pattern and many may fail with HTTP 404;
//   save any that fail by hand into database/seeders/data/images/ (the seeder finds them by the 8-digit product code in the file name).
//   #971–#1000 are all UNVERIFIED: the pages could not be opened, so they follow the usual
//   WTCPH-<product code>-front-zoom.jpg pattern and many may fail with HTTP 404; save any that fail by hand into
//   database/seeders/data/images/ (the seeder finds them by the 8-digit product code in the file name).
//   UNVERIFIED (5):  #908 SkinWhite, #910 Propan TLC Drops, #916 Rexona, #918 Pentasa, #919 Nivea Derma Skin Clear -
//   their pages did not show an image link, so they follow the usual Watsons naming pattern
//   (WTCPH-<product code>-front-zoom.jpg) and may fail with HTTP 404. For any that fail, open the
//   product page, right-click the image and save it by hand into database/seeders/data/images/
//   (the seeder finds it by the 8-digit product code in the file name: 10048625 / 10083036 / 10071213 /
//   10097573 / 50052116). The products still seed fine without an image.
// ─────────────────────────────────────────────────────────────────────────────
const urls = [
  // 901 Salonpas Methylsalicylate 105mg + Menthol 31.5mg Medicated Patch 10s
  "https://medias.watsons.com.ph/publishing/10001399%20%281%29-9t3HvBMR-zoom.png?version=1770198992",
  // 902 Green Cross 70% Alcohol with Moisturizer 500ml
  "https://medias.watsons.com.ph/publishing/10047296-8wpUVEns-zoom.png?version=1762516789",
  // 903 Robitussin Dextromethorphan + Guaifenesin Syrup 120ml
  "https://medias.watsons.com.ph/publishing/10000949-KYZ9ruma-zoom.png?version=1762514125",
  // 904 Charmee Ultra-Thin Napkin with Wings for Heavy Flow 6s
  "https://medias.watsons.com.ph/publishing/CHARMEE_IMAGE1_50061991-E7Jyfo68-zoom.jpg?version=1785725333",
  // 905 Luxe Organix Niacinamide + Tranexamic Acid Youthful Glow Whitening Serum Lotion SPF 50 PA++++ 200ml
  "https://medias.watsons.com.ph/publishing/WTCPH-50047868-front-tg8UpBik-zoom.png?version=1794499230",
  // 906 Amvasc Amlodipine 5mg + Hydrochlorothiazide 12.5mg 1 Tablet
  "https://medias.watsons.com.ph/publishing/WTCPH-10088716-front-zoom.jpg?version=1734051672",
  // 907 Safeguard Foaming Hand Soap Pure White Refill 400ml x 1
  "https://medias.watsons.com.ph/publishing/WTCPH-50032733-front-zoom.jpg?version=1734118311",
  // 908 SkinWhite Classic Light Lotion SPF20 100ml  (UNVERIFIED - see note above)
  "https://medias.watsons.com.ph/publishing/WTCPH-10048625-front-zoom.jpg",
  // 909 Tambocor Flecainide Acetate 100mg 1 Tablet
  "https://medias.watsons.com.ph/publishing/WTCPH-10084626-front-zoom.jpg?version=1734017379",
  // 910 Propan TLC Drops 15ml  (UNVERIFIED - see note above)
  "https://medias.watsons.com.ph/publishing/WTCPH-10083036-front-zoom.jpg",
  // 911 My Daily Collagen Collagen Drink 100ml (Sold Per Bottle)
  "https://medias.watsons.com.ph/publishing/WTCPH-BP_50026189-front-zoom.jpg?version=1720777392",
  // 912 Pond's Age Miracle Day Cream 9g
  "https://medias.watsons.com.ph/publishing/WTCPH-10086790-front-W2ZYTXYU-zoom.png?version=1794499303",
  // 913 Celeteque Dermo Science Acne Solution Oil Control Toner 125ml
  "https://medias.watsons.com.ph/publishing/WTCPH-10081416-front-IuHUXUWl-zoom.png?version=1794499301",
  // 914 Remasta 100mg Film-Coated Tablet (Sold Per Piece)
  "https://medias.watsons.com.ph/publishing/WTCPH-50047269-front-zoom.jpg?version=1734352374",
  // 915 Garnier Micellar Water Vitamin C 125ml
  "https://medias.watsons.com.ph/publishing/50031030_01_OP-CTs8yV4V-zoom.png?version=1789438677",
  // 916 Rexona Roll On Quantum Dry 25ml  (UNVERIFIED - see note above)
  "https://medias.watsons.com.ph/publishing/WTCPH-10071213-front-zoom.jpg",
  // 917 Piozone Pioglitazone Hydrochloride 30mg 1 Tablet
  "https://medias.watsons.com.ph/publishing/WTCPH-10052512-front-W8qC55AL-zoom.png?version=1791514920",
  // 918 Pentasa Mesalazine 500mg 1 Tablet  (UNVERIFIED - see note above)
  "https://medias.watsons.com.ph/publishing/WTCPH-10097573-front-zoom.jpg",
  // 919 Nivea Derma Skin Clear Deep Pore Cleanser 150ml  (UNVERIFIED - see note above)
  "https://medias.watsons.com.ph/publishing/WTCPH-50052116-front-zoom.jpg",
  // 920 Hepbest Tenofovir Alafenamide 25mg Film-Coated Tablet 30s
  "https://medias.watsons.com.ph/publishing/WTCPH-50040765-front-zoom.png?version=1734147730",
  // 921 Julitam Levetiracetam 500mg 1 Film-Coated Tablet  (VERIFIED - exact URL from the product page)
  "https://medias.watsons.com.ph/publishing/WTCPH-50037264-front-zoom.jpg?version=1734155992",
  // 922 Sisters Pantyliner 180mm 20 Longer Pantyliners Budget Pack  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10100151-front-zoom.jpg",
  // 923 Celetor Celecoxib 200mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50035577-front-zoom.jpg",
  // 924 Breecort Budesonide 500mcg/2ml Nebule  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50006674-front-zoom.jpg",
  // 925 Hyzaar Losartan 100mg + HCTZ 12.5mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10064865-front-zoom.jpg",
  // 926 Norizec Glimepiride 1mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10042652-front-zoom.jpg",
  // 927 Co-Aprovel Irbesartan + HCTZ 300mg/12.5mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10007322-front-zoom.jpg",
  // 928 Nivea Original Care Lip Balm 4.8g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10026702-front-zoom.jpg",
  // 929 Kastair Montelukast 5mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10059796-front-zoom.jpg",
  // 930 Isordil Isosorbide Dinitrate 5mg Sublingual  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10000405-front-zoom.jpg",
  // 931 Moringa Vita  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10097831-front-zoom.jpg",
  // 932 Ad-C Ascorbic Acid + Zinc 500mg/15mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10088390-front-zoom.jpg",
  // 933 Canephron Uno  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50054572-front-zoom.jpg",
  // 934 Telmiget Duo Telmisartan + Amlodipine 80mg/10mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50051412-front-zoom.jpg",
  // 935 Ritemed Vitamin B1 + B6 + B12 15+5  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50037970-front-zoom.jpg",
  // 936 Sangobion Iron 14+2 Capsules Promo Pack  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10101915-front-zoom.jpg",
  // 937 Mucosolvan 30mg Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10000530-front-zoom.jpg",
  // 938 Iselpin Sucralfate 1g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10000390-front-zoom.jpg",
  // 939 Pro-Source VCO Extra Virgin Coconut Oil 250ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10040474-front-zoom.jpg",
  // 940 Unomet 50mg/1g Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50055834-front-zoom.jpg",
  // 941 Siftus 7.5 Ivabradine Anti-angina Film-Coated Tablet 7.5mg (Sold Per Piece)  (VERIFIED - exact URL from the product page)
  "https://medias.watsons.com.ph/publishing/WTCPH-50053993-front-zoom.jpg?version=1750445425",
  // 942 Pneumotyl Acetylcysteine 200mg 1 Tablet (Sold Per Piece)  (VERIFIED - exact URL from the product page)
  "https://medias.watsons.com.ph/publishing/WTCPH-50031451-front-zoom.jpg?version=1734158818",
  // 943 Cetaphil Gentle Skin Cleanser 60ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10003657-front-zoom.jpg",
  // 944 Optein Lutein (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10073706-front-zoom.jpg",
  // 945 Fisherman's Friend Mandarin and Ginger 25g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10064039-front-zoom.jpg",
  // 946 Allerkid Cetirizine 5mg/5ml Syrup 30ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10053181-front-zoom.jpg",
  // 947 Nutroplex Multivitamins + Minerals + Lysine Syrup 250ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10000597-front-zoom.jpg",
  // 948 EZ Clear Lubricating Jelly Sachet 10ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10093484-front-zoom.jpg",
  // 949 Deo-Nat Aloe Mineral Deodorant Spray 100ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10088404-front-zoom.jpg",
  // 950 Luxe Organix Bio Collagen PDRN Boost Advance Renewal Complex Serum Treatment Hydrogel Mask 25g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50062757-front-zoom.jpg",
  // 951 Luxe Organix Bye Bye Bum Bumps Clear Smooth Booty Scrub 285g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50048708-front-zoom.jpg",
  // 952 Cetaphil Perfecting Serum 30ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50039893-front-zoom.jpg",
  // 953 Lacibloc Lacidipine 6mg Film-Coated Tablet (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50037384-front-zoom.jpg",
  // 954 Dove Sensitive Beauty Bar 135g x 3s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50036957-front-zoom.jpg",
  // 955 Pinkcheck Cassette Early Pregnancy Test Kit 2s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10094169-front-zoom.jpg",
  // 956 Med-Guard Anti-Bacterial Wipes with Moisturizer 10 Sheets  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10086228-front-zoom.jpg",
  // 957 Watsons Herbal Salt Mouthwash 500ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10101327-front-zoom.jpg",
  // 958 Cetaphil Gentle Exfoliating SA Cleanser 236ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50053275-front-zoom.jpg",
  // 959 CeraVe Moisturizing Cream 50ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50053260-front-zoom.jpg",
  // 960 Molev Montelukast + Levocetirizine DiHCl 10mg/5mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50026269-front-zoom.jpg",
  // 961 SkinWhite Papaya Milk Lotion 200ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10086207-front-zoom.jpg",
  // 962 Dove Deeply Nourishing Body Wash Refill Pouch 650ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50013405-front-zoom.jpg",
  // 963 Abdin Aripiprazole 5mg Tablet (1 Tablet)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10099045-front-zoom.jpg",
  // 964 Bactiv Co-Amoxiclav 1g 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10096528-front-zoom.jpg",
  // 965 Raxide Ranitidine Hydrochloride 150mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10038246-front-zoom.jpg",
  // 966 TRESemmé Keratin Smooth Kera10 Shampoo 170ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10097965-front-zoom.jpg",
  // 967 Celeteque Dermo Science Acne Cleansing Gel 50ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10088547-front-zoom.jpg",
  // 968 Noston 1080mg/10mEq Extended-Release Tablet Antiurolithic (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50054785-front-zoom.jpg",
  // 969 Lactezin 12+2 Promo Pack  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50037053-front-zoom.jpg",
  // 970 Torsit-M 50mg/1g (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50048200-front-zoom.jpg",
  // 971 Telmirin Plus Telmisartan + Hydrochlorothiazide 40mg/12.5mg Tablet (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50050167-front-zoom.jpg",
  // 972 Forherdin Metformin Hydrochloride 500mg Film-Coated Tablet Box of 100s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50050012-front-zoom.jpg",
  // 973 Kera-Luxe Salon Perfection Brazillian Botox Daily Keratin Treatment Rebond Straight 700ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50045763-front-zoom.jpg",
  // 974 Mezacar 200mg (Prescription Required)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50044466-front-zoom.jpg",
  // 975 Gabix Gabapentin 300mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10065170-front-zoom.jpg",
  // 976 SkinWhite Powder Silk Whitening Deodorant 40ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10070213-front-zoom.jpg",
  // 977 Hypace Enalapril 20mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10055791-front-zoom.jpg",
  // 978 Glucovance Metformin Hydrochloride 500mg + Glibenclamide 5mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10052146-front-zoom.jpg",
  // 979 Maxivent Doxofylline 400mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10088787-front-zoom.jpg",
  // 980 Keppra Levetiracetam 250mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10083405-front-zoom.jpg",
  // 981 Zeman ZMA Powder Soft Gelatin Capsule (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10087388-front-zoom.jpg",
  // 982 Betadine Oral Gargle 120ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10000964-front-zoom.jpg",
  // 983 Kojie San Plus Glycolic Acid Soap  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50051369-front-zoom.jpg",
  // 984 Luxe Organix Retinol + Bakuchiol Overnight Radiant Glow Botox Lifting Moisturizer 50g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50037815-front-zoom.jpg",
  // 985 Sofy Comfort Nite Slim Wing 35.5cm 8 Pads  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50033693-front-zoom.jpg",
  // 986 Novofine 32G 4mm 1 Sterile Needle  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50012102-front-zoom.jpg",
  // 987 Belo Intensive Whitening Bar 65g (2+1)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50020620-front-zoom.jpg",
  // 988 Bactidol Extra Soothing Honey Lemon Lozenge 16pcs  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50030397-front-zoom.jpg",
  // 989 Forcad Simvastatin 10mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10099241-front-zoom.jpg",
  // 990 Spasmomen Otilonium 40mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10091661-front-zoom.jpg",
  // 991 Atenurix Febuxostat 80mg 1 Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10091163-front-zoom.jpg",
  // 992 Garnier Micellar Water Salicylic Acid BHA for Oily and Acne-Prone Skin 125ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50007934-front-zoom.jpg",
  // 993 Luxe Organix Hypochlorous Acid 0.02% Clear Skin Defense Antibacterial Mist 100ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50059252-front-zoom.jpg",
  // 994 Nature's Spring pH9 Drinking Water 1000ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50057222-front-zoom.jpg",
  // 995 Perfect Smile 12-Hour Total Care Teeth Whitening Oral Rinse Stick  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50050918-front-zoom.jpg",
  // 996 Pitator 4mg Film-Coated Tablet (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50039379-front-zoom.jpg",
  // 997 Trajenta Duo 2.5mg/1g Film-Coated Tablet (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50043955-front-zoom.jpg",
  // 998 Vicks VapoDrops Lozenges Orange Menthol 24s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50041431-front-zoom.jpg",
  // 999 Ritemed Furosemide 40mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50020327-front-zoom.jpg",
  // 1000 Luxe Organix Intensive Whitening Underarm Cream 10% Niacinamide 30g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50014448-front-zoom.jpg"
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