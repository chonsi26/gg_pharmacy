// ─────────────────────────────------------------------------------------------
// Saves every Watsons product image used by watsons_products.php.
//
// HOW TO USE
//  1. In Chrome/Edge open this page (any image on that host works):
//       https://medias.watsons.com.ph/publishing/Colgate_Image1_10099320-odE7YHxR-zoom.jpg?version=1783930967
//  2. Press F12 -> Console. If asked, type  allow pasting  and press Enter.
//  3. Paste this whole script and press Enter.
//  4. If the browser asks to "allow multiple downloads", click Allow.
//  5. Move all downloaded files into:  database/seeders/data/images/
//     (keep their original names - the seeder finds them by file name)
//  6. Run:  php artisan db:seed
//
// It must be run on medias.watsons.com.ph so the requests are same-origin.
// ─────────────────────────────------------------------------------------------
const urls = [
  "https://medias.watsons.com.ph/publishing/50034594-UuDAfU5p-zFVmre0h-zoom.png?version=1791515186",
  "https://medias.watsons.com.ph/publishing/WTCPH-10085026-front-AeC94fGG-zoom.png?version=1791514975",
  "https://medias.watsons.com.ph/publishing/WTCPH-50005598-front-MFq8cNhr-zoom.png?version=1791475225",
  "https://medias.watsons.com.ph/publishing/50047301-axIp4NxM-KGXC3yfw-zoom.png?version=1791515236",
  "https://medias.watsons.com.ph/publishing/Jardiance%2010%20mg_Image1_10101452-R1BZrLJt-zoom.png?version=1772097059",
  "https://medias.watsons.com.ph/publishing/WTCPH-10000016-front-zoom.jpg?version=1734016411",
  "https://medias.watsons.com.ph/publishing/WTCPH-10027907-front-mYe0OVqN-zoom.png?version=1791514853",
  "https://medias.watsons.com.ph/publishing/WTCPH-10044909-front-l1gtWKXI-zoom.png?version=1791514894",
  "https://medias.watsons.com.ph/publishing/WTCPH-10093458-front-sJRlHq0c-zoom.png?version=1791515032",
  "https://medias.watsons.com.ph/publishing/CONZACE%20100S_IMAGE%201_10065073-MgNZf3eD-laH4H2u8-zoom.png?version=1791514932",
  "https://medias.watsons.com.ph/publishing/watsons%20generics_50036197_01-HJcRV0AU-3FhfUnou-zoom.png?version=1792425704",
  "https://medias.watsons.com.ph/publishing/WTCPH-50049718-front-zoom.jpg?version=1734335212",
  "https://medias.watsons.com.ph/publishing/WTCPH-50051980-front-mMbAKQam-zoom.png?version=1791515267",
  "https://medias.watsons.com.ph/publishing/TWYNSTA%20TAB%2040MG_5MG%2030%27S_Image1_10087913-4JER98jU-zoom.png?version=1772000808",
  "https://medias.watsons.com.ph/publishing/10096408-FttQ5h4z-zoom.png?version=1762573997",
  "https://medias.watsons.com.ph/publishing/ENERVON%20Z%2B%2028%2B4_IMAGE%201_50054571-hTB48lrv-HXSGw4RH-zoom.png?version=1791515285",
  "https://medias.watsons.com.ph/publishing/WTCPH-10084266-front-owcsu4Yf-zoom.png?version=1791514968",
  "https://medias.watsons.com.ph/publishing/watsons%20generics_10096072_01-rEWpQyC2-jYhnRrOZ-zoom.png?version=1793289648",
  "https://medias.watsons.com.ph/publishing/WTCPH-10000550-front-9HI16OLb-zoom.png?version=1791514807",
  "https://medias.watsons.com.ph/publishing/WTCPH-10072482-front-zoom.jpg?version=1734052278",
  "https://medias.watsons.com.ph/publishing/WTCPH-10092890-front-zoom.jpg?version=1734065572",
  "https://medias.watsons.com.ph/publishing/WTCPH-10095471-front-zoom.jpg?version=1734048701",
  "https://medias.watsons.com.ph/publishing/WTCPH-10087859-front-afZUxMIo-zoom.png?version=1791514989",
  "https://medias.watsons.com.ph/publishing/WTCPH-50051549-front-EiAJidla-zoom.png?version=1793289646",
  "https://medias.watsons.com.ph/publishing/10039990-c7w141Iz-8BkGAeMv-zoom.png?version=1791514873",
  "https://medias.watsons.com.ph/publishing/WTCPH-10001695-front-zoom.jpg?version=1734010948",
  "https://medias.watsons.com.ph/publishing/WTCPH-50025313-front-zoom.jpg?version=1734108364",
  "https://medias.watsons.com.ph/publishing/WTCPH-10089724-front-zoom.jpg?version=1734055241",
  "https://medias.watsons.com.ph/publishing/WTCPH-10093528-front-zoom.jpg?version=1734054954",
  "https://medias.watsons.com.ph/publishing/WTCPH-10100946-front-zoom.jpg?version=1734019715",
  "https://medias.watsons.com.ph/publishing/WTCPH-10000066-front-VfiwSgQW-zoom.png?version=1791514778",
  "https://medias.watsons.com.ph/publishing/WTCPH-10096368-front-zoom.jpg?version=1734049315",
  "https://medias.watsons.com.ph/publishing/WTCPH-10095581-front-zoom.jpg?version=1734006810",
  "https://medias.watsons.com.ph/publishing/10100847-24VJbAua-pGpcy8pM-zoom.png?version=1793289849",
  "https://medias.watsons.com.ph/publishing/Glyxambi%2025mg%20_%205mg_Image1_50018947-EidcoCZ4-zoom.png?version=1772096716",
  "https://medias.watsons.com.ph/publishing/10054397-SrStT1UR-NTErNjpc-zoom.png?version=1791514920",
  "https://medias.watsons.com.ph/publishing/Jardiance%20Duo%2012.5%20mg_%201%20g_Image1_50009773-fPzfhazw-zoom.png?version=1772097978",
  "https://medias.watsons.com.ph/publishing/10084433-CSMOO9zc-zoom.png?version=1762574176",
  "https://medias.watsons.com.ph/publishing/WTCPH-10095066-front-zoom.jpg?version=1734012151",
  "https://medias.watsons.com.ph/publishing/WTCPH-50000321-front-zoom.jpg?version=1734032347",
  "https://medias.watsons.com.ph/publishing/WTCPH-50049719-front-zoom.jpg?version=1734335198",
  "https://medias.watsons.com.ph/publishing/50050169-xhcASPGz-zoom.png?version=1766124614",
  "https://medias.watsons.com.ph/publishing/WTCPH-50052369-front-zoom.jpg?version=1738252825",
  "https://medias.watsons.com.ph/publishing/50002256-OXMsIktT-zoom.png?version=1762532881",
  "https://medias.watsons.com.ph/publishing/WTCPH-50001533-front-pNoHdLjb-zoom.png?version=1791515093",
  "https://medias.watsons.com.ph/publishing/50030606-fEvvaSty-e6rpgWtf-zoom.png?version=1793289730",
  "https://medias.watsons.com.ph/publishing/10095469-2cIRo11b-IdQJjWg3-zoom.png?version=1793289793",
  "https://medias.watsons.com.ph/publishing/WTCPH-10056304-front-zoom.jpg?version=1734018187",
  "https://medias.watsons.com.ph/publishing/WTCPH-50051538-front-zoom.jpg?version=1734342112",
  "https://medias.watsons.com.ph/publishing/WTCPH-10088423-front-zoom.jpg?version=1734069356",
  "https://medias.watsons.com.ph/publishing/watsons%20generics_10095163_01-NuxwGVrE-zoom.png?version=1743743657",
  "https://medias.watsons.com.ph/publishing/WTCPH-50000319-front-zoom.jpg?version=1734019958",
  "https://medias.watsons.com.ph/publishing/WTCPH-10093533-front-zoom.jpg?version=1734016456",
  "https://medias.watsons.com.ph/publishing/WTCPH-50040875-front-zoom.jpg?version=1734145101",
  "https://medias.watsons.com.ph/publishing/WTCPH-10084095-front-OfnEjvp2-zoom.png?version=1791514967",
  "https://medias.watsons.com.ph/publishing/WTCPH-10098539-front-zoom.jpg?version=1734054693",
  "https://medias.watsons.com.ph/publishing/WTCPH-50002878-front-zoom.jpg?version=1734051258",
  "https://medias.watsons.com.ph/publishing/TWYNSTA%20TAB%2080MG_10MG%2030%27S%20%20_Image1_10087916-6Ic2x3WJ-zoom.png?version=1772012980",
  "https://medias.watsons.com.ph/publishing/WTCPH-10032620-front-zoom.jpg?version=1734070408",
  "https://medias.watsons.com.ph/publishing/WTCPH-10085021-front-V9bSI692-zoom.png?version=1791514973",
  "https://medias.watsons.com.ph/publishing/WTCPH-50040874-front-zoom.jpg?version=1734144796",
  "https://medias.watsons.com.ph/publishing/10000359-Zxpq48BO-HpD6H4EQ-zoom.png?version=1791514792",
  "https://medias.watsons.com.ph/publishing/ROSUCOL%20TAB%2010MG%20X30_10093546-L9inTo87-zoom.jpg?version=1766375885",
  "https://medias.watsons.com.ph/publishing/WTCPH-10039400-front-HHGIs0KQ-zoom.png?version=1791514871",
  "https://medias.watsons.com.ph/publishing/WTCPH-10065517-front-zoom.jpg?version=1734069204",
  "https://medias.watsons.com.ph/publishing/WTCPH-10093532-front-zoom.jpg?version=1734014325",
  "https://medias.watsons.com.ph/publishing/10096407-YTERTN6Q-zoom.png?version=1762532833",
  "https://medias.watsons.com.ph/publishing/50027636-EPAQVC5j-HZpe0gN5-zoom.png?version=1793289797",
  "https://medias.watsons.com.ph/publishing/50011100-WxHMsvj8-zoom.png?version=1762577839",
  "https://medias.watsons.com.ph/publishing/50013849-2fYIlZ0d-zoom.png?version=1766124028",
  "https://medias.watsons.com.ph/publishing/ATENURIX%20TAB%2040MG%20X30_10091162-ur3qL4Rg-zoom.jpg?version=1766369457",
  "https://medias.watsons.com.ph/publishing/WTCPH-50049015-front-zoom.jpg?version=1751385051",
  "https://medias.watsons.com.ph/publishing/50037451-LzTVwZNV-vMSDeThk-zoom.png?version=1791515200",
  "https://medias.watsons.com.ph/publishing/WTCPH-10093817-front-zoom.jpg?version=1734029415",
  "https://medias.watsons.com.ph/publishing/Diamicron%20MR%2060mg_Image%201_10091148-XMHBoh6A-zoom.JPG?version=1780363043",
  "https://medias.watsons.com.ph/publishing/WTCPH-50019374-front-zoom.jpg?version=1734094343",
  "https://medias.watsons.com.ph/publishing/WTCPH-50012501-side-zoom.jpg?version=1720786685",
  "https://medias.watsons.com.ph/publishing/WTCPH-50037974-front-zoom.jpg?version=1734328240",
  "https://medias.watsons.com.ph/publishing/WTCPH-10100952-front-zoom.jpg?version=1734021229",
  "https://medias.watsons.com.ph/publishing/WTCPH-10038431-front-zoom.jpg?version=1734053835",
  "https://medias.watsons.com.ph/publishing/WTCPH-10096685-front-zoom.jpg?version=1734051453"
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