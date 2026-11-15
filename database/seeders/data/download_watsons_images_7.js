// ─────────────────────────────------------------------------------------------
// Saves every Watsons product image used by watsons_products_7.php
// (batch 7: links 601–700).
//
// HOW TO USE
//  1. In Chrome/Edge open this page (any image on that host works):
//       https://medias.watsons.com.ph/publishing/WTCPH-50018949-front-zoom.jpg
//  2. Press F12 -> Console. If asked, type  allow pasting  and press Enter.
//  3. Paste this whole script and press Enter.
//  4. If the browser asks to "allow multiple downloads", click Allow.
//  5. Move all downloaded files into:  database/seeders/data/images/
//     (keep their original names - the seeder finds them by file name)
//  6. Run:  php artisan db:seed
//
// It must be run on medias.watsons.com.ph so the requests are same-origin.
//
// 100 images, one per product (#601–#700). ALL are UNVERIFIED (pages not opened):
// they follow the usual Watsons naming pattern (WTCPH-<product code>-front-zoom.jpg)
// and many may fail with HTTP 404. For any that fail, open the product page,
// right-click the image and save it by hand into database/seeders/data/images/
// (the seeder finds it by the 8-digit product code in the file name). The
// products still seed fine without an image.
// ─────────────────────────────────────────────────────────────────────────────
const urls = [
  // 601 Pitator Pitavastatin Calcium 2mg 1 Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50018949-front-zoom.jpg",
  // 602 Zegen Cefuroxime Axetil 500mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10089058-front-zoom.jpg",
  // 603 Platexan Clopidogrel Bisulfate 75mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10075632-front-zoom.jpg",
  // 604 Betadine Feminine Wash 100ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10001104-front-zoom.jpg",
  // 605 Cetaphil Skin Cleanser 250ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10000163-front-zoom.jpg",
  // 606 Pelargo 20mg Capsule (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50047565-front-zoom.jpg",
  // 607 Clovixa Film-Coated Tablet 75mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50044607-front-zoom.jpg",
  // 608 Kidneycare Dietary Supplement (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50014653-front-zoom.jpg",
  // 609 Rowachol Pinene, Camphene, Cineol, Menthone, Menthol, Borneol (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10059762-front-zoom.jpg",
  // 610 Benerve Thiamine Mononitrate + Pyridoxine HCl + Cyanocobalamin 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10095056-front-zoom.jpg",
  // 611 Watsons 10in1 Alcohol-Free Mouthwash 500ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10101280-front-zoom.jpg",
  // 612 Atajec Mycophenolate Mofetil 500mg Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50052686-front-zoom.jpg",
  // 613 Omegabloc Fish Oil Concentrate EPA/DHA 100mg Food Supplement  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10034052-front-zoom.jpg",
  // 614 Diovan Valsartan 160mg 1 Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10059451-front-zoom.jpg",
  // 615 Nivea Men Deodorant Black & White Fresh Anti-Perspirant Roll-On 50ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50014477-front-zoom.jpg",
  // 616 Reinin Gabapentin 300mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10062023-front-zoom.jpg",
  // 617 Efficascent Oil Relaxing Oil 6ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50006631-front-zoom.jpg",
  // 618 I-White Korea Whitening Sleeping Cream Mask 6ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50021105-front-zoom.jpg",
  // 619 Mistel Telmisartan 80mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50057099-front-zoom.jpg",
  // 620 Funxion Pregabalin 50mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10095896-front-zoom.jpg",
  // ── Batch 32 (links 621–650) ──
  // 621 Kamillosan Anti-Inflammatory Spray Solution 15ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10001313-front-zoom.jpg",
  // 622 Ritemed Furosemide 20mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50020325-front-zoom.jpg",
  // 623 Claritin Loratadine 10mg 4+1 Pack  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10102348-front-zoom.jpg",
  // 624 Met-Tathione 500mg 1pc Capsule  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50043756-front-zoom.jpg",
  // 625 Letoripe Letrozole 2.5mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50028753-front-zoom.jpg",
  // 626 Cetaphil Baby Wash & Shampoo Pump 400ml Head-to-Toe Gentle Cleansing Formula and Hypoallergenic  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50018238-front-zoom.jpg",
  // 627 Tigerfil Sildenafil 50mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10096733-front-zoom.jpg",
  // 628 Potencee Plus Collagen 15+5 Capsules  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50038690-front-zoom.jpg",
  // 629 Lioresal Baclofen 10mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10083573-front-zoom.jpg",
  // 630 Kenzar Losartan Potassium 100mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10100935-front-zoom.jpg",
  // 631 Aprovasc Irbesartan 150mg + Amlodipine 5mg 1 Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50003741-front-zoom.jpg",
  // 632 Natrapharm Gliclazide 30mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10091198-front-zoom.jpg",
  // 633 Med-Guard Cotton Buds White 200 Tips  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10052953-front-zoom.jpg",
  // 634 Ketesse Dexketoprofen Trometamol 25mg 1 Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10091653-front-zoom.jpg",
  // 635 Viartril-S Capsule 628mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10087821-front-zoom.jpg",
  // 636 Doxin Doxycycline Hydrochloride 100mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10000251-front-zoom.jpg",
  // 637 Growee Vitamins with Chlorella Growth Factor Syrup 250ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10041541-front-zoom.jpg",
  // 638 Dove Serum Bar Niacinamide, Vitamin C and E 3s 90g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50048096-front-zoom.jpg",
  // 639 Maxvit 6+1 Softgel Capsules Promo Pack  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50036983-front-zoom.jpg",
  // 640 Dermaid Aroma Oil Moisturizing Roll-On Eucalyptus 10ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50011693-front-zoom.jpg",
  // 641 Clonipress Clonidine HCl 75mcg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10093459-front-zoom.jpg",
  // 642 Glamworks Permanent Hair Dye Color Dark Brown 30ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10090864-front-zoom.jpg",
  // 643 Cetaphil Bright Healthy Radiance Brightness Reveal Creamy Cleanser  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50026803-front-zoom.jpg",
  // 644 Arbloc Amlodipine + Losartan 5mg/50mg 1 Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50006247-front-zoom.jpg",
  // 645 Salveo Olanzapine 10mg 1 Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50018291-front-zoom.jpg",
  // 646 Dermaid Calamine + Zinc Oxide 30ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50006414-front-zoom.jpg",
  // 647 Lizelle Drospirenone + Ethinyl Estradiol 3mg/20mcg x28 Tablet Box  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10095948-front-zoom.jpg",
  // 648 Memry Memantine Hydrochloride 10mg 1 Film-Coated Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50004474-front-zoom.jpg",
  // 649 Nebiloc CCB Amlodipine Besilate + Nebivolol Hydrochloride 5mg/5mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50027626-front-zoom.jpg",
  // 650 Myra E DL-Alpha Tocopheryl Acetate Vitamin E 30 Capsules  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10004131-front-zoom.jpg",
  // ── Batch 33 (links 651–680) ──
  // 651 Watsons Love My Softness Bamboo Facial Tissue Box 3ply 100s x 3 Box  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50037679-front-zoom.jpg",
  // 652 Myra E Fresh Glow Facial Wash 50ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50005890-front-zoom.jpg",
  // 653 Betadine Skin Cleanser 120ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10000103-front-zoom.jpg",
  // 654 Med-Guard Anti-Bacterial Wipes 30 Wipes  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10048686-front-zoom.jpg",
  // 655 pH Care Odor Protech Spray Cooling Comfort 50ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50052390-front-zoom.jpg",
  // 656 Pantovaz Pantoprazole (as Sodium Sesquihydrate) 40mg Tablet (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50037380-front-zoom.jpg",
  // 657 Glyzeric Metformin 500mg + Gliclazide 80mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10096183-front-zoom.jpg",
  // 658 Systane Ultra Ophthalmic Solution 10ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10088472-front-zoom.jpg",
  // 659 Luxe Organix Miracle Solutions Acne Derm Micellar Make-Up Remover Cleansing Wipes 30s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50038838-front-zoom.jpg",
  // 660 Luxe Organix Miracle Repair Niacinamide 4% Serum 30ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50009278-front-zoom.jpg",
  // 661 Akidin Biperiden Hydrochloride 2mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10093536-front-zoom.jpg",
  // 662 Nasathera Phenylpropanolamine HCl + Paracetamol (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10000556-front-zoom.jpg",
  // 663 Luxe Organix Aloe Vera Soothing Gel 99% 100ml Sachet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50007229-front-zoom.jpg",
  // 664 Rexidol Forte 6+1 Pack  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50048533-front-zoom.jpg",
  // 665 Belfour Whitening Anti-Perspirant Deodorant Spray 100ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50044980-front-zoom.jpg",
  // 666 Acnes Anti-Acne Pimple Patch 26s  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50040040-front-zoom.jpg",
  // 667 Antaxid Pantoprazole 40mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50024107-front-zoom.jpg",
  // 668 Ritemed Losartan Potassium + Hydrochlorothiazide 50/12.5mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10094515-front-zoom.jpg",
  // 669 Montra Isosorbide Mononitrate 30mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10089185-front-zoom.jpg",
  // 670 Ceelin Ascorbic Acid Chewable 100mg 30 Tablets  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10051284-front-zoom.jpg",
  // 671 Appebon Kid Chewables (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50056330-front-zoom.jpg",
  // 672 Jovia Escitalopram Film-Coated Tablet 20mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50044684-front-zoom.jpg",
  // 673 Transtat Tranexamic Acid Film-Coated Tablet 500mg  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50044589-front-zoom.jpg",
  // 674 Dulcolax Bisacodyl 4 Enteric-Coated Tablets  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50037731-front-zoom.jpg",
  // 675 Lesofat Lesofast Orlistat 120mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10069462-front-zoom.jpg",
  // 676 Fortifer Iron + Vitamin B Complex + Folic Acid 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10000339-front-zoom.jpg",
  // 677 Myra E Hydrating Glow Body Lotion 100ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50059015-front-zoom.jpg",
  // 678 Diatabs Advance 2mg/125mg 1 Pack  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50048088-front-zoom.jpg",
  // 679 Rexona Men Quantum Dry 72h Buy One Get 2nd Item at 80% Off 45ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50049526-front-zoom.jpg",
  // 680 Luxe Organix Whitening Repair Niacinamide 10% Serum 30ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50028463-front-zoom.jpg",
  // ── Batch 34 (links 681–700) ──
  // 681 Watsons Lavender Chamomile Scented Gel Hand Wash 500ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10090322-front-zoom.jpg",
  // 682 Ursofalk Ursodeoxycholic Acid 500mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10096736-front-zoom.jpg",
  // 683 Dr. Wong's Sulfure Soap with Moisturizers Aloe Vera 135g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10095812-front-zoom.jpg",
  // 684 Bewell-C Non-Acidic Vitamin C 500mg 30+10 Capsules  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10094467-front-zoom.jpg",
  // 685 Ritemed Telmisartan + Hydrochlorothiazide 40mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50043766-front-zoom.jpg",
  // 686 Serc Betahistine diHCl 24mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10070920-front-zoom.jpg",
  // 687 Fresh HealthLab KF94 Face Mask Beige  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50033157-front-zoom.jpg",
  // 688 Ritemed Pregabalin 150mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50010946-front-zoom.jpg",
  // 689 Lacipil Lacidipine 2mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10003182-front-zoom.jpg",
  // 690 Getzar Losartan Potassium 50mg (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10079465-front-zoom.jpg",
  // 691 Forcad Simvastatin 20mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10083860-front-zoom.jpg",
  // 692 Piascledin Avocado Oil 200mg + Soy Bean Oil 100mg 1 Capsule  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10097916-front-zoom.jpg",
  // 693 Warik Warfarin Sodium 2.5mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10094523-front-zoom.jpg",
  // 694 Rythma Amiodarone Hydrochloride 200mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10089152-front-zoom.jpg",
  // 695 Riliz 2mg 1 Box  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50046767-front-zoom.jpg",
  // 696 Sangobion Forte (Sold Per Piece)  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50033355-front-zoom.jpg",
  // 697 Monti Plus Montelukast + Levocetirizine 10mg/5mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50006303-front-zoom.jpg",
  // 698 Cherifer Immunomax Syrup for Kids 120ml  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10080664-front-zoom.jpg",
  // 699 Dr. Wong's Sulfur Soap 135g  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-10087870-front-zoom.jpg",
  // 700 Levare 60mg 1 Tablet  (UNVERIFIED)
  "https://medias.watsons.com.ph/publishing/WTCPH-50046768-front-zoom.jpg"
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