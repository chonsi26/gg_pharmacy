// ─────────────────────────────------------------------------------------------
// Saves every Watsons product image used by watsons_products_2.php
// (batch 2: links 101–200).
//
// HOW TO USE
//  1. In Chrome/Edge open this page (any image on that host works):
//       https://medias.watsons.com.ph/publishing/WTCPH-10070595-front-zoom.jpg?version=1734070698
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
// product page itself (links 101–200).
// ─────────────────────────────------------------------------------------------
const urls = [
  "https://medias.watsons.com.ph/publishing/WTCPH-10070595-front-zoom.jpg?version=1734070698",
  "https://medias.watsons.com.ph/publishing/GAVISCON_10102350_01-VY7kiA1V-zoom.jpg?version=1755257262",
  "https://medias.watsons.com.ph/publishing/WTCPH-50005466-front-zoom.jpg?version=1734048591",
  "https://medias.watsons.com.ph/publishing/WTCPH-10064425-front-zoom.jpg?version=1734068667",
  "https://medias.watsons.com.ph/publishing/WTCPH-10083491-front-zoom.jpg?version=1734070602",
  "https://medias.watsons.com.ph/publishing/WTCPH-50025758-front-zoom.jpg?version=1734126238",
  "https://medias.watsons.com.ph/publishing/WTCPH-50043527-front-zoom.jpg?version=1734322126",
  "https://medias.watsons.com.ph/publishing/WTCPH-10003520-front-zoom.jpg?version=1734011858",
  "https://medias.watsons.com.ph/publishing/WTCPH-10088999-front-zoom.jpg?version=1734005651",
  "https://medias.watsons.com.ph/publishing/10093208-KyMt37W6-k6BqGaqh-zoom.png?version=1791515028",
  "https://medias.watsons.com.ph/publishing/WTCPH-50011231-front-zoom.jpg?version=1734051474",
  "https://medias.watsons.com.ph/publishing/WTCPH-10000579-front-zoom.jpg?version=1734055109",
  "https://medias.watsons.com.ph/publishing/WTCPH-10092476-front-lafzJbwa-zoom.png?version=1791515028",
  "https://medias.watsons.com.ph/publishing/WTCPH-10096313-front-zoom.jpg?version=1734069569",
  "https://medias.watsons.com.ph/publishing/ROSUFEN%20TAB%2016010M6%20X%2030_50006219-rQN3dxvk-zoom.jpg?version=1766388539",
  "https://medias.watsons.com.ph/publishing/WTCPH-50040869-front-zoom.jpg?version=1734329446",
  "https://medias.watsons.com.ph/publishing/WTCPH-10003234-front-zoom.jpg?version=1734070395",
  "https://medias.watsons.com.ph/publishing/50025331-bxf3qGwj-Wadml7gM-zoom.png?version=1791515162",
  "https://medias.watsons.com.ph/publishing/50025084-O2UQijxm-ij4O9FH2-zoom.png?version=1791515161",
  "https://medias.watsons.com.ph/publishing/50009657-6BsLCDNp-ZhL0VAh5-zoom.png?version=1791515126",
  "https://medias.watsons.com.ph/publishing/WTCPH-50053912-front-zoom.jpg?version=1748361015",
  "https://medias.watsons.com.ph/publishing/WTCPH-10098898-front-zoom.jpg?version=1734055165",
  "https://medias.watsons.com.ph/publishing/50047302-kyioVhI5-OnsLqo4T-zoom.png?version=1791515237",
  "https://medias.watsons.com.ph/publishing/50047084_02-FsxRSLZe-GDnKg8cy-zoom.png?version=1791515235",
  "https://medias.watsons.com.ph/publishing/WTCPH-50009044-front-zoom.jpg?version=1734052118",
  "https://medias.watsons.com.ph/publishing/50001422%20Lactezin%20100s-iRhHa27x-r6n2q6jz-zoom.png?version=1791515091",
  "https://medias.watsons.com.ph/publishing/WTCPH-50006037-front-whtfWksJ-zoom.png?version=1791515117",
  "https://medias.watsons.com.ph/publishing/ATENURIX%20TAB%2020MG%20X30_10099065-rtPxQzPc-zoom.jpg?version=1766393323",
  "https://medias.watsons.com.ph/publishing/50036398-erOWBus7-RX0eKiOJ-zoom.png?version=1791515191",
  "https://medias.watsons.com.ph/publishing/50035540_BestShape%20PDP%20image%20revised-f5vYmrHD-zoom.jpg?version=1764646457",
  "https://medias.watsons.com.ph/publishing/WTCPH-50040801-front-zDaQdkl2-zoom.png?version=1791515220",
  "https://medias.watsons.com.ph/publishing/50025797-7c1i4Lhu-noWR7bOY-zoom.png?version=1791515163",
  "https://medias.watsons.com.ph/publishing/WTCPH-50021218-front-cjKHJT5D-zoom.png?version=1791515159",
  "https://medias.watsons.com.ph/publishing/WTCPH-10081411-front-mRyX0uva-zoom.png?version=1791514955",
  "https://medias.watsons.com.ph/publishing/WTCPH-10093285-front-FHB0Fg5k-zoom.png?version=1791515028",
  "https://medias.watsons.com.ph/publishing/10094915-xTLqBUeF-tdkLjMRW-zoom.png?version=1791515054",
  "https://medias.watsons.com.ph/publishing/WTCPH-50042897-front-0a4M9ED8-zoom.png?version=1791515220",
  "https://medias.watsons.com.ph/publishing/WTCPH-10003032-front-zoom.jpg?version=1734072531",
  "https://medias.watsons.com.ph/publishing/WTCPH-50014733-front-zoom.jpg?version=1734039773",
  "https://medias.watsons.com.ph/publishing/WTCPH-10003329-front-f2TSlhdW-zoom.png?version=1791514847",
  "https://medias.watsons.com.ph/publishing/WTCPH-50050607-front-zoom.jpg?version=1734337866",
  "https://medias.watsons.com.ph/publishing/10076756-oNue7k5y-kNT7b2XI-zoom.png?version=1791514948",
  "https://medias.watsons.com.ph/publishing/WTCPH-10003958-front-zoom.jpg?version=1734012161",
  "https://medias.watsons.com.ph/publishing/50003305%20Myra%20Ultimate%2030s-jMyXQx8h-vMYuBtqs-zoom.png?version=1791515097",
  "https://medias.watsons.com.ph/publishing/WTCPH-10027466-front-zoom.jpg?version=1734016539",
  "https://medias.watsons.com.ph/publishing/Jardiance%20Duo%2012.5mg_500%20mg_Image1_50009772-OoGJwHD3-zoom.png?version=1772098164",
  "https://medias.watsons.com.ph/publishing/WTCPH-50013268-front-zoom.jpg?version=1734041230",
  "https://medias.watsons.com.ph/publishing/50030610-L3xTqYTG-zoom.png?version=1765426285",
  "https://medias.watsons.com.ph/publishing/Daflon%201000mg_Image%201_50036612-NFdTGmsR-zoom.JPG?version=1780362506",
  "https://medias.watsons.com.ph/publishing/50040036-yTvCmEUF-zoom.png?version=1762570183",
  "https://medias.watsons.com.ph/publishing/WTCPH-10041364-front-zoom.jpg?version=1734053909",
  "https://medias.watsons.com.ph/publishing/WTCPH-10098540-front-mbg4kdDU-zoom.png?version=1791515068",
  "https://medias.watsons.com.ph/publishing/WTCPH-10003235-front-zoom.jpg?version=1734070444",
  "https://medias.watsons.com.ph/publishing/WTCPH-10061635-front-zoom.jpg?version=1734015179",
  "https://medias.watsons.com.ph/publishing/WTCPH-10096315-front-zoom.jpg?version=1734069416",
  "https://medias.watsons.com.ph/publishing/WTCPH-50052600-front-zoom.jpg?version=1739508616",
  "https://medias.watsons.com.ph/publishing/RX1_50042949-YUA3bLBz-G4lDMPy1-zoom.png?version=1791515220",
  "https://medias.watsons.com.ph/publishing/WTCPH-50053610-front-zoom.jpg?version=1746816671",
  "https://medias.watsons.com.ph/publishing/WTCPH-50026139-front-zoom.jpg?version=1734162614",
  "https://medias.watsons.com.ph/publishing/WTCPH-10026177-front-zoom.jpg?version=1733999521",
  "https://medias.watsons.com.ph/publishing/WTCPH-50056792-front-zoom.jpg?version=1786463604",
  "https://medias.watsons.com.ph/publishing/WTCPH-50059078-front-HxsgiR1U-zoom.png?version=1791515345",
  "https://medias.watsons.com.ph/publishing/WTCPH-10003233-front-zoom.jpg?version=1734053729",
  "https://medias.watsons.com.ph/publishing/WTCPH-10092570-front-zoom.jpg?version=1734070817",
  "https://medias.watsons.com.ph/publishing/WTCPH-10031319-front-zoom.jpg?version=1734055018",
  "https://medias.watsons.com.ph/publishing/WTCPH-50041462-front-zoom.jpg?version=1734148829",
  "https://medias.watsons.com.ph/publishing/WTCPH-10092511-front-FDEEIQ4V-zoom.png?version=1791515028",
  "https://medias.watsons.com.ph/publishing/50048116-rKZiFo2V-CRgVnDHM-zoom.png?version=1791515243",
  "https://medias.watsons.com.ph/publishing/50003197_Nightol%20880x880%20Nightol%201%20Capsule-Pq1OHk5w-zoom.jpeg?version=1764646385",
  "https://medias.watsons.com.ph/publishing/WTCPH-10064411-front-zoom.jpg?version=1734020888",
  "https://medias.watsons.com.ph/publishing/10004138_Norvasc%2010mg-WmjOaMsG-zoom.jpg?version=1789607582",
  "https://medias.watsons.com.ph/publishing/WTCPH-50048199-front-zoom.jpg?version=1734334172",
  "https://medias.watsons.com.ph/publishing/10002424-3ZmKeXEr-zoom.png?version=1762598291",
  "https://medias.watsons.com.ph/publishing/WTCPH-50049177-front-zoom.jpg?version=1734350930",
  "https://medias.watsons.com.ph/publishing/WTCPH-10067060-front-zoom.jpg?version=1734055133",
  "https://medias.watsons.com.ph/publishing/WTCPH-10000695-front-zoom.jpg?version=1734055177",
  "https://medias.watsons.com.ph/publishing/WTCPH-10085020-front-zoom.jpg?version=1734068015",
  "https://medias.watsons.com.ph/publishing/WTCPH-10001694-front-zoom.jpg?version=1734070561",
  "https://medias.watsons.com.ph/publishing/WTCPH-10041559-front-zoom.jpg?version=1734068761",
  "https://medias.watsons.com.ph/publishing/WTCPH-10002822-front-zoom.jpg?version=1734009923",
  "https://medias.watsons.com.ph/publishing/10028534-x1h1IJxG-zoom.png?version=1781160664",
  "https://medias.watsons.com.ph/publishing/WTCPH-10059639-front-zoom.jpg?version=1734053252",
  "https://medias.watsons.com.ph/publishing/50037609-ycnYCUG3-PLPbRQGz-zoom.png?version=1791515202",
  "https://medias.watsons.com.ph/publishing/WTCPH-10097806-front-zoom.jpg?version=1734049373",
  "https://medias.watsons.com.ph/publishing/WTCPH-10099793-front-zoom.jpg?version=1734005662",
  "https://medias.watsons.com.ph/publishing/WTCPH-50049514-front-BAtliWFr-zoom.png?version=1791515250",
  "https://medias.watsons.com.ph/publishing/Glyxambi%2010%20mg_%205%20mg_Image1_50018945-rzBfj3bB-zoom.png?version=1772015348",
  "https://medias.watsons.com.ph/publishing/WTCPH-10002204-front-zoom.jpg?version=1734069624",
  "https://medias.watsons.com.ph/publishing/WTCPH-50005687-front-zoom.jpg?version=1734018630",
  "https://medias.watsons.com.ph/publishing/WTCPH-50052446-front-zoom.jpg?version=1738770660",
  "https://medias.watsons.com.ph/publishing/WTCPH-50002809-front-zoom.jpg?version=1734041761",
  "https://medias.watsons.com.ph/publishing/WTCPH-50059019-front-TClEmisW-zoom.png?version=1791515343",
  "https://medias.watsons.com.ph/publishing/WTCPH-10001979-front-zoom.jpg?version=1734070243",
  "https://medias.watsons.com.ph/publishing/WTCPH-50003097-front-zoom.jpg?version=1734069917",
  "https://medias.watsons.com.ph/publishing/WTCPH-50000320-front-zoom.jpg?version=1734055084",
  "https://medias.watsons.com.ph/publishing/WTCPH-10001747-front-zoom.jpg?version=1734055121",
  "https://medias.watsons.com.ph/publishing/10042730-lYVrzX9z-zoom.png?version=1762512493",
  "https://medias.watsons.com.ph/publishing/WTCPH-10092763-front-zoom.jpg?version=1734051081",
  "https://medias.watsons.com.ph/publishing/10000582_Norvasc%205mg%20front-SSysz2y9-zoom.jpg?version=1789607456",
  "https://medias.watsons.com.ph/publishing/WTCPH-10068302-front-zoom.jpg?version=1734070645"
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