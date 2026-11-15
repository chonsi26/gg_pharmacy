// ─────────────────────────------------------------------------------------------
// Saves every Watsons product image used by watsons_products_9.php
// (batch 9: links 801–900).
//
// HOW TO USE
//  1. In Chrome/Edge open this page (any image on that host works):
//       https://medias.watsons.com.ph/publishing/WTCPH-10000226-front-zoom.jpg?version=1734068514
//  2. Press F12 -> Console. If asked, type  allow pasting  and press Enter.
//  3. Paste this whole script and press Enter.
//  4. If the browser asks to "allow multiple downloads", click Allow.
//  5. Move all downloaded files into:  database/seeders/data/images/
//     (keep their original names - the seeder finds them by file name)
//  6. Run:  php artisan db:seed
//
// It must be run on medias.watsons.com.ph so the requests are same-origin.
//
// 100 images, one per product (#801–#900).
//   VERIFIED   (#806, #807, #814, #843, #845): the exact "-zoom" image URL (with ?version=) copied from the product page.
//   UNVERIFIED (the other 95, incl. all of #851–#900): they follow the usual Watsons naming pattern
//   (WTCPH-<product code>-front-zoom.jpg) and some may fail with HTTP 404. For any that fail, open the
//   product page, right-click the image and save it by hand into database/seeders/data/images/
//   (the seeder finds it by the 8-digit product code in the file name). The products still seed fine
//   without an image.
// ─────────────────────────────────────────────────────────────────────────────
const urls = [
  // 801 Watsons Generics Cetirizine 10mg 30s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50052145-front-zoom.jpg",
  // 802 Xanthone Plus Gold 500mg 1 Capsule (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50036516-front-zoom.jpg",
  // 803 Dulcolax Bisacodyl 5mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10079423-front-zoom.jpg",
  // 804 Fluimucil Acetylcysteine 100mg Granules for Solution (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10003606-front-zoom.jpg",
  // 805 Tempra Orange Drops 15ml (Paracetamol)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10003637-front-zoom.jpg",
  // 806 Decolsin Dextromethorphan HBr 15mg + Phenylpropanolamine HCl 25mg + Paracetamol 325mg Capsule (Sold Per Piece)  (VERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10000226-front-zoom.jpg?version=1734068514",
  // 807 Miprogen Progesterone 200mg Soft Gelatin Oral Capsule (Sold Per Piece)  (VERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50054978-front-zoom.jpg?version=1758732610",
  // 808 Ritemed Rm Simvastatin  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50039345-front-zoom.jpg",
  // 809 Stangesic Mefenamic Acid 500mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50012137-front-zoom.jpg",
  // 810 Canditral Itraconazole 100mg 1 Capsule  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10092847-front-zoom.jpg",
  // 811 UHP Calcium Lactate 325mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10000137-front-zoom.jpg",
  // 812 Nivea Body Milk Rich Nourishing Lotion 350ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10080473-front-zoom.jpg",
  // 813 Imdur Isosorbide-5-Mononitrate 30mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10001230-front-zoom.jpg",
  // 814 Watsons Generics Paracetamol 325mg + Tramadol 37.5mg (Sold Per Piece)  (VERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10101678-front-zoom.jpg?version=1744386652",
  // 815 Nutrilin Vitamins + Minerals Plus Iron, Iodine, Taurine and Zinc Syrup 250ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10040799-front-zoom.jpg",
  // 816 Lummarx Tongue Cleaner  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50046165-front-zoom.jpg",
  // 817 Luxe Organix Perfecting UV Tint Serum Sunscreen SPF100+ PA++++ 40g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50042081-front-zoom.jpg",
  // 818 Doctor J Isopropyl Alcohol 70% 500ml Pump  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10094234-front-zoom.jpg",
  // 819 Duactam Tadalafil + Tamsulosin 5mg/400mcg Capsule (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50055681-front-zoom.jpg",
  // 820 Utrogestan Progesterone 200mg Oral Softgel (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50021027-front-zoom.jpg",
  // 821 Tolak Angin Care Essential Oil Roll On  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50022717-front-zoom.jpg",
  // 822 Celeteque DermoScience Brightening Micellar Milk 250ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50028845-front-zoom.jpg",
  // 823 Cildine Cilnidipine 20mg Tablet (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50037920-front-zoom.jpg",
  // 824 Luxe Organix Zero Shine Invisible Screen Daily Sun Stick SPF 100 17g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50046323-front-zoom.jpg",
  // 825 Ursoliv Ursodeoxycholic Acid 250mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50009524-front-zoom.jpg",
  // 826 Amara Glimepiride 2mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10101682-front-zoom.jpg",
  // 827 Gen-Cee Sodium Ascorbate 562.50mg Capsule (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50020444-front-zoom.jpg",
  // 828 Watsons Strawberry and Yoghurt Cream Hand Wash Refill 500ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10090319-front-zoom.jpg",
  // 829 Coversyl Perindopril 5mg + Indapamide 1.25mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10075324-front-zoom.jpg",
  // 830 Ator Atorvastatin Calcium 40mg 1 Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50006518-front-zoom.jpg",
  // 831 Calsan Calcium Carbonate 500mg 1 Chewable Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10001911-front-zoom.jpg",
  // 832 Cherifer Premium Vitamins and Minerals plus Chlorella Growth Factor, Taurine and Ginseng 1 Capsule  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10001376-front-zoom.jpg",
  // 833 Strepsils Honey Lemon 8s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10045576-front-zoom.jpg",
  // 834 Rexona Men Advanced Protect Invisible Dry Black + White Roll On 45ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50054262-front-zoom.jpg",
  // 835 Charmee Feminine Menstrual Pants M 6s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50051137-front-zoom.jpg",
  // 836 Nivea Cherry Shine Lip Balm 4.8g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10026701-front-zoom.jpg",
  // 837 Belo Glutathione with Collagen Dietary Supplement 30 Capsules  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50012290-front-zoom.jpg",
  // 838 Lamitor Lamotrigine 50mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10093816-front-zoom.jpg",
  // 839 Diamicron Gliclazide 80mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10002022-front-zoom.jpg",
  // 840 Nature's Spring pH9 Drinking Water 500ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50057035-front-zoom.jpg",
  // 841 Vertisin Cinnarizine 25mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50004508-front-zoom.jpg",
  // 842 Bactidol Oral Antiseptic Gargle Mouthwash 60ml for Sore Throat, Itchy Throat  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10024130-front-zoom.jpg",
  // 843 Idezar Irbesartan 150mg Film-Coated Tablet (Sold Per Piece)  (VERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10098901-front-kvT1fWUF-zoom.png?version=1791515071",
  // 844 Met Tathione Dietary Supplement Sachet 2pcs Capsule  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10094236-front-zoom.jpg",
  // 845 Mounjaro KwikPen 10mg (Tirzepatide)  (VERIFIED)
  "https://medias.watsons.com.ph/publishing/50058411%20-%20mounjaro-5AR8oTQh-zoom.png?version=1770199141",
  // 846 Acnes Mentholatum Anti-Acne Pimple Patch 26s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50054615-front-zoom.jpg",
  // 847 pH Care Feminine Wash Natural Protection 50ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10097770-front-zoom.jpg",
  // 848 Milcu Unscented Deo Roll On 50ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10016876-front-zoom.jpg",
  // 849 Naturals Stevia Sugar Replacement 40 Sticks x 2g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50054691-front-zoom.jpg",
  // 850 Dove Sakura Blossom Sakura x Pink Salt Body Wash Refill 650ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50049072-front-zoom.jpg",
  // 851 Berocca Effervescent Tablet Orange Flavor  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50020465-front-zoom.jpg",
  // 852 Difflam Eucalyptus and Menthol Lozenge 8s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50009816-front-zoom.jpg",
  // 853 Zanidip Lercanidipine 20mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10050753-front-zoom.jpg",
  // 854 Monea Curl Defining Milk Hair Lotion 200ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10072879-front-zoom.jpg",
  // 855 Luxe Organix Color Shampoo Black 30ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50003346-front-zoom.jpg",
  // 856 Entresto Sacubitril 50mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10101795-front-zoom.jpg",
  // 857 pH Care Feminine Wash Cooling Comfort 150ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10088083-front-zoom.jpg",
  // 858 Watsons Generics Candesartan 16mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50053639-front-zoom.jpg",
  // 859 Ldnil 20mg Film-Coated Tablet (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50044468-front-zoom.jpg",
  // 860 Celeteque DermoScience Hydration Micellar Water 250ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50028844-front-zoom.jpg",
  // 861 Risek Omeprazole 40mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10079467-front-zoom.jpg",
  // 862 Cuddles Cotton Squares 100 Sheets  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10011447-front-zoom.jpg",
  // 863 Watsons Generics Naproxen Sodium 550mg 1 Film-Coated Tablet (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50033050-front-zoom.jpg",
  // 864 Drenex Dexamethasone 3mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50031133-front-zoom.jpg",
  // 865 Piozone Pioglitazone Hydrochloride 15mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10052511-front-zoom.jpg",
  // 866 Ova-Mit Clomiphene Citrate 50mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10076782-front-zoom.jpg",
  // 867 Ceelin Ascorbic Acid + Zinc 100mg/10mg per 5ml Syrup 60ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10079468-front-zoom.jpg",
  // 868 Vicks VapoRub 5g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10002932-front-zoom.jpg",
  // 869 Watsons Milk Baby Bath 850ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50010150-front-zoom.jpg",
  // 870 Doctor J Ethyl Alcohol Pump 1L  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50015992-front-zoom.jpg",
  // 871 Myra E Fresh Glow Facial Moisturizer 7ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50005922-front-zoom.jpg",
  // 872 pH Care Feminine Wash Floral Clean 250ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10088080-front-zoom.jpg",
  // 873 Luxe Organix Active Dry Whitening Serum Deodorant Underarm Rescue 50g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50054969-front-zoom.jpg",
  // 874 Rosucol Rosuvastatin 5mg Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50047511-front-zoom.jpg",
  // 875 Dove Radiant Care Serum Bar 50x Niacinamide Omega-6 90g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50047887-front-zoom.jpg",
  // 876 Tempra Forte Orange Syrup 250mg 60ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10003636-front-zoom.jpg",
  // 877 Oral-B Shiny Clean Soft Toothbrush with Cap 1s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10089864-front-zoom.jpg",
  // 878 Champion Biotech Cassette Pregnancy Test Kit  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10093356-front-zoom.jpg",
  // 879 Glitaz Pioglitazone HCl 15mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10100902-front-zoom.jpg",
  // 880 Toricard Nebivolol Hydrochloride 5mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50006387-front-zoom.jpg",
  // 881 Co-Altria Montelukast 5mg + Levocetirizine 5mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50008727-front-zoom.jpg",
  // 882 Disudrin Syrup 60ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10000248-front-zoom.jpg",
  // 883 Zemidapa 50mg/10mg Film-Coated Tablet (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50055757-front-zoom.jpg",
  // 884 Pimax 400mcg Enteric-Coated Tablet Anti-Benign Prostatic Hyperplasia (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50044978-front-zoom.jpg",
  // 885 Ritemed Glibenclamide 5mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50020337-front-zoom.jpg",
  // 886 Perispa Eperisone 50mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10102357-front-zoom.jpg",
  // 887 Herpex Aciclovir 400mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50009038-front-zoom.jpg",
  // 888 Aprovasc Irbesartan 300mg + Amlodipine 5mg Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50003742-front-zoom.jpg",
  // 889 Relumins L-Glutathione 1000mg 60 Vegetarian Capsules (Bottle)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50004386-front-zoom.jpg",
  // 890 Gasmotin Mosapride 5mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10088935-front-zoom.jpg",
  // 891 Regeron Multivitamins + Iron (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10002651-front-zoom.jpg",
  // 892 Old Spice WC Invisible Solid Wolfthorn 73g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50025371-front-zoom.jpg",
  // 893 Ivi Premium Collagen Drink 50ml Peach  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10090983-front-zoom.jpg",
  // 894 Johnson's Baby Milk + Rice Baby Lotion 200ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10028905-front-zoom.jpg",
  // 895 Ginger Bon Honey Lemon Flavor Ginger Chewy Candy 125g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50034430-front-zoom.jpg",
  // 896 Nivea Deodorant Brightening Sakura Rose Essence Anti-Perspirant Roll-On 50ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50016930-front-zoom.jpg",
  // 897 Watsons Green Tea and Apple Scented Hand Soap Refill 500ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10090321-front-zoom.jpg",
  // 898 Buscopan Venus Hyoscine N-Butylbromide + Paracetamol 4 Film-Coated Tablet Convenience Pack  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50039983-front-zoom.jpg",
  // 899 Ritemed Valsartan 160mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50030513-front-zoom.jpg",
  // 900 Feliz Escitalopram Oxalate 10mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10099231-front-zoom.jpg"
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