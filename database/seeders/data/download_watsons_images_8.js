// ─────────────────────────────------------------------------------------------
// Saves every Watsons product image used by watsons_products_8.php
// (batch 8: links 701–800).
//
// HOW TO USE
//  1. In Chrome/Edge open this page (any image on that host works):
//       https://medias.watsons.com.ph/publishing/WTCPH-50030516-front-zoom.jpg
//  2. Press F12 -> Console. If asked, type  allow pasting  and press Enter.
//  3. Paste this whole script and press Enter.
//  4. If the browser asks to "allow multiple downloads", click Allow.
//  5. Move all downloaded files into:  database/seeders/data/images/
//     (keep their original names - the seeder finds them by file name)
//  6. Run:  php artisan db:seed
//
// It must be run on medias.watsons.com.ph so the requests are same-origin.
//
// 100 images, one per product (#701–#800). ALL are UNVERIFIED (pages not opened):
// they follow the usual Watsons naming pattern (WTCPH-<product code>-front-zoom.jpg)
// and many may fail with HTTP 404. For any that fail, open the product page,
// right-click the image and save it by hand into database/seeders/data/images/
// (the seeder finds it by the 8-digit product code in the file name). The
// products still seed fine without an image.
// ─────────────────────────────────────────────────────────────────────────────
const urls = [
  // 701 Ritemed Valsartan 80mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50030516-front-zoom.jpg",
  // 702 Ritemed Irbesartan 150mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50027377-front-zoom.jpg",
  // 703 Watsons Generics Cranberry 300mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10098861-front-zoom.jpg",
  // 704 Centrum Advance Multivitamins & Minerals 30 Tablets Bottle  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10101094-front-zoom.jpg",
  // 705 QuickFX Pimple Eraser 30g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10101025-front-zoom.jpg",
  // 706 Mistel Telmisartan 40mg Tablet (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50044979-front-zoom.jpg",
  // 707 MX3 Coffee Mix Low Acid Coffee with Mangosteen Exocarp 10g 1 Sachet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10094969-front-zoom.jpg",
  // 708 Mounjaro KwikPen 2.5mg/0.6ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50057683-front-zoom.jpg",
  // 709 Prolix Prednisone 20mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50009389-front-zoom.jpg",
  // 710 Fern-C Plus 500mg/10mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50033227-front-zoom.jpg",
  // 711 UDCAcid Ursodeoxycholic Acid 300mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10101407-front-zoom.jpg",
  // 712 Godex Carnitine Orotate + 17 Amino Acids + Adenine HCl + Vitamin B Complex 1 Capsule  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10088287-front-zoom.jpg",
  // 713 Legalon 140mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10083310-front-zoom.jpg",
  // 714 Buscopan Hyoscine N-Butylbromide 10mg + Paracetamol 500mg Film-Coated Tablet (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10090910-front-zoom.jpg",
  // 715 Fisherman's Friend Lemon Sugar Free 24g 1s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10026352-front-zoom.jpg",
  // 716 Sofeel Travel Pack Facial Tissue Value Pack 3 Packs 100 Sheets  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10056318-front-zoom.jpg",
  // 717 Slinda Film-Coated Tablet 4mg 28s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50041660-front-zoom.jpg",
  // 718 Montiget Montelukast Na 4mg 1 Sachet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10069985-front-zoom.jpg",
  // 719 Myra E Smooth Glow Whitening Facial Wash 50ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50005889-front-zoom.jpg",
  // 720 Visanne Dienogest 2mg x28 Tablets Pack  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10097343-front-zoom.jpg",
  // 721 Aveeno Baby Daily Moisture Lotion 227g Newborn for Sensitive Baby Skin Baby Body Lotion  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50014334-front-zoom.jpg",
  // 722 Doxicon Doxycycline 100mg 1 Capsule  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10026187-front-zoom.jpg",
  // 723 Keppra Levetiracetam 500mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10060554-front-zoom.jpg",
  // 724 Levotics CR Tablet 90mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50046137-front-zoom.jpg",
  // 725 Neurobion B Vitamins B1 + B6 + B12 Tablet 1s Nerve Care  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10002498-front-zoom.jpg",
  // 726 Band-Aid Strips 50s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50051760-front-zoom.jpg",
  // 727 Dermaid Sterilized Gauze Swab  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50051093-front-zoom.jpg",
  // 728 KA Plus Multivitamins + Minerals + Amino Acids + Deanol + Ginseng (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50041380-front-zoom.jpg",
  // 729 Nutrabliss WS L-Arginine 500mg Vegetarian Capsule (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50027653-front-zoom.jpg",
  // 730 Amazar Amlodipine Besilate + Losartan Potassium 5mg/50mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10098787-front-zoom.jpg",
  // 731 Proglin Vildagliptin 50mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10096683-front-zoom.jpg",
  // 732 Arcoxia Etoricoxib 90mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10034311-front-zoom.jpg",
  // 733 Lomotil Loperamide Diarrhea Medicine Tablet 2mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10048635-front-zoom.jpg",
  // 734 Proursan Ursodeoxycholic Acid Forte 500mg Film-Coated Tablet (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50052371-front-zoom.jpg",
  // 735 Erceflora Gut Defense 5s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50048021-front-zoom.jpg",
  // 736 Tareg Valsartan 160mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10096254-front-zoom.jpg",
  // 737 Exforge Amlodipine Besilate 5mg + Valsartan 160mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10069180-front-zoom.jpg",
  // 738 Ciprobay Ciprofloxacin Hydrochloride 500mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10001964-front-zoom.jpg",
  // 739 Meptin Procaterol Hydrochloride 25mcg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10000483-front-zoom.jpg",
  // 740 Watsons Invigorating Wet Wipes Aloe Vera Scented 10 Wipes x 3 Pack  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50050043-front-zoom.jpg",
  // 741 Nitroxel Nebivolol 5mg Tablet 30s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50052597-front-zoom.jpg",
  // 742 Monvex-L Montelukast + Levocetirizine Hydrochloride 10mg/5mg Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50049568-front-zoom.jpg",
  // 743 Erceflora Gut Restore 5s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50047929-front-zoom.jpg",
  // 744 Nivea Men Cool Kick Deodorant Roll-On 50ml Bundle  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50046523-front-zoom.jpg",
  // 745 Luxe Organix Whitening Repair Whip Moisturizer 80ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50029671-front-zoom.jpg",
  // 746 Pecof Salbutamol Sulfate 2mg + Bromhexine HCl 8mg + Guaifenesin 100mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10074304-front-zoom.jpg",
  // 747 Stresstabs Multivitamins 600mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10000954-front-zoom.jpg",
  // 748 Bisosten Bisoprolol Fumarate 5mg Film-Coated Tablet (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50044959-front-zoom.jpg",
  // 749 Januvia Sitagliptin 100mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10054189-front-zoom.jpg",
  // 750 Garnier Micellar Water Pink for Sensitive Skin 125ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50007931-front-zoom.jpg",
  // 751 Zydactin 50 Spironolactone Diuretic 50mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50053991-front-zoom.jpg",
  // 752 Luxe Organix Milk Gluta Whitening Serum Lotion 350ml (Buy 2 Get 50% Off)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50044683-front-zoom.jpg",
  // 753 Metta Metformin 500mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50006388-front-zoom.jpg",
  // 754 Entresto Sacubitril/Valsartan 100mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10101796-front-zoom.jpg",
  // 755 Acnetrex Isotretinoin 10mg 30 Softgel Capsule  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10094015-front-zoom.jpg",
  // 756 Lyrica 75mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10050642-front-zoom.jpg",
  // 757 Creamsilk Treatment Amino Collagen Damage Repair 500ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50053951-front-zoom.jpg",
  // 758 Telmirin Telmisartan 40mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50050166-front-zoom.jpg",
  // 759 Sinutab Cold 500mg/5mg Film-Coated Tablet (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50036229-front-zoom.jpg",
  // 760 Seroxat Paroxetine HCl 20mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10059102-front-zoom.jpg",
  // 761 Natal Plus (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50013978-front-zoom.jpg",
  // 762 Ritemed Clonidine 75mcg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10093544-front-zoom.jpg",
  // 763 Nolvadex Tamoxifen Citrate 20mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10066410-front-zoom.jpg",
  // 764 Watsons Flat Thread Mint Dental Flossers Loose Box  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10089582-front-zoom.jpg",
  // 765 Kotex Freedom Ultrathin Wings 23cm Twin Pack Promo  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50038447-front-zoom.jpg",
  // 766 Coxoral Celecoxib 200mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10100176-front-zoom.jpg",
  // 767 Luxe Organix Whitening Repair Foam Cleanser 150g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50014627-front-zoom.jpg",
  // 768 Amoclav Amoxicillin + Clavulanic Acid 500mg/125mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10055004-front-zoom.jpg",
  // 769 Hydro-Aid Grape Flavored Electrolyte Drink 250ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50035171-front-zoom.jpg",
  // 770 Vinca 2mg Tablet Box of 28s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50042380-front-zoom.jpg",
  // 771 Neomet Metformin Hydrochloride 500mg Film-Coated 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50004503-front-zoom.jpg",
  // 772 Nivea Deodorant Dry Comfort Roll-On 50ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10011801-front-zoom.jpg",
  // 773 Body Treats Antibacterial Hand Soap 750ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10101333-front-zoom.jpg",
  // 774 Kojie San Skin Lightening Classic Soap 65g x 3 Value Pack  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10098963-front-zoom.jpg",
  // 775 Celeteque Skin Defense Brightening Silky Sun Cream SPF60 40ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50054723-front-zoom.jpg",
  // 776 Lactezin Lactoferrin + D-Alpha Tocopheryl Acetate + Zinc 100mg/11 IU/5mg 8 Capsules  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50037214-front-zoom.jpg",
  // 777 Charmee Feminine Pads All Night Long Cottony with Wings 10 Pads  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50011065-front-zoom.jpg",
  // 778 Ohhira Mountain Fruit Extract Probiotics (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10069459-front-zoom.jpg",
  // 779 Nutrilin Vitamins B-Complex and Taurine Drops 30ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10041543-front-zoom.jpg",
  // 780 Zetlow 10mg Tablet (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50048201-front-zoom.jpg",
  // 781 Tri Plus Trimetazidine Hydrochloride 60mg Sustained Release 1 Capsule (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50036147-front-zoom.jpg",
  // 782 UHP Natafol 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50035090-front-zoom.jpg",
  // 783 Tamsolin Tamsulosin HCl 400mcg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10087638-front-zoom.jpg",
  // 784 Neomerdin Carbimazole 20mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10093669-front-zoom.jpg",
  // 785 Concore Bisoprolol Fumarate 10mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10083530-front-zoom.jpg",
  // 786 Torus Rosuvastatin 20mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10095253-front-zoom.jpg",
  // 787 Ritemed Metronidazole 500mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10087932-front-zoom.jpg",
  // 788 Cholinerv Citicoline Sodium 500mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10067040-front-zoom.jpg",
  // 789 UHP Calcium Lactate 650mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10000138-front-zoom.jpg",
  // 790 Johnson's Baby Milk + Rice Bath 200ml Free 100ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50043264-front-zoom.jpg",
  // 791 Prazole Pantoprazole and Domperidone 40mg/30mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10089079-front-zoom.jpg",
  // 792 Betadine Throat Spray 50ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50006813-front-zoom.jpg",
  // 793 Tamduet Dutasteride + Tamsulosin Hydrochloride 500mcg/400mcg Capsule (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50046927-front-zoom.jpg",
  // 794 Spirofar Spironolactone 25mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50017704-front-zoom.jpg",
  // 795 Watsons Washproof Plasters 100s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10087343-front-zoom.jpg",
  // 796 Cathy Doll Aloe Vera Snail Serum Soothing Gel 10g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50001027-front-zoom.jpg",
  // 797 Oxecure Acne Clear Powder Mud 5g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50015976-front-zoom.jpg",
  // 798 Celeteque Sun Matte Moisturizer with SPF30 50ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10088545-front-zoom.jpg",
  // 799 pH Care Natural Protection Wipes 10s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50011066-front-zoom.jpg",
  // 800 Ritemed Clarithromycin 500mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10085184-front-zoom.jpg"
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