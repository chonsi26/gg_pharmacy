<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\FullWidthBanner;
use App\Models\NavItem;
use App\Models\Product;
use App\Models\PromoBanner;
use App\Models\Section;
use App\Models\Setting;
use App\Models\Slider;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Disable FK checks so TRUNCATE works on tables with foreign keys
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // ── 1. Settings ──────────────────────────────────────────────────────
        foreach ([
            'site_name'        => 'GG Pharmacy',
            'tagline'          => 'Search for Generic and Branded Medicine',
            'phone'            => '09188887673',
            'phone_label'      => 'Call Us Now',
            'email'            => 'onlinesale@ggpharmacy.com.ph',
            'address_line1'    => 'Zone 2, Sogod',
            'address_line2'    => 'Southern Leyte',
            'address_line3'    => 'Philippines',
            'working_hours'    => 'Mon - Sun / 8:00 AM - 9:00 PM',
            'location_strip'   => '📍 Zone 2, Sogod, Southern Leyte',
            'facebook_url'     => '#',
            'instagram_url'    => '#',
            'logo'             => 'images/logo.png',
            'shipping_message' => 'Free shipping for orders over ₱1499 (For Sogod, Southern Leyte Only)',
            'copyright'        => '© 2024 GG Pharmacy. All Rights Reserved. | Zone 2, Sogod, Southern Leyte',
            'newsletter_intro' => 'Get all the latest information on events, sales and offers. Sign up for newsletter:',
            'newsletter_note'  => 'By subscribing, you agree to receive our newsletter and accept our privacy policy.',
            'payment_methods'  => 'VISA,MC,GCash,PayMaya',
            'footer_top_text'  => 'Your Trusted Pharmacy',
            'chatbase_id'      => 'eDdta8JbEpQawG9yqfKqb',
        ] as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // ── 2. Nav Items (with parent–child relationship) ────────────────────
        NavItem::truncate();

        // Top-level items — no parent
        $home      = NavItem::create(['label' => 'HOME',                  'href' => '/', 'is_active' => true,  'sort_order' => 1]);
        $guardian  = NavItem::create(['label' => 'GUARDIAN',             'href' => '#', 'sort_order' => 2]);
        $generics  = NavItem::create(['label' => 'GG PHARMACY GENERICS', 'href' => '#', 'sort_order' => 3]);
        $health    = NavItem::create(['label' => 'HEALTHCARE',           'href' => '#', 'sort_order' => 4]);
        $beauty    = NavItem::create(['label' => 'BEAUTY AND SELF CARE', 'href' => '#', 'sort_order' => 5]);
        $baby      = NavItem::create(['label' => 'BABY AND FAMILY NEEDS','href' => '#', 'sort_order' => 6]);
        $sale      = NavItem::create(['label' => 'SALE',                 'href' => '#', 'sort_order' => 7]);

        // Dropdown children (parent_id set)
        $guardian->children()->createMany([
            ['label' => 'Guardian Personal Care',  'href' => '#', 'sort_order' => 1],
            ['label' => 'Guardian Baby Products',  'href' => '#', 'sort_order' => 2],
            ['label' => 'Guardian Health Devices', 'href' => '#', 'sort_order' => 3],
        ]);
        $generics->children()->createMany([
            ['label' => 'Pain Relief',       'href' => '#', 'sort_order' => 1],
            ['label' => 'Vitamins',          'href' => '#', 'sort_order' => 2],
            ['label' => 'Cardiovascular',    'href' => '#', 'sort_order' => 3],
        ]);
        $health->children()->createMany([
            ['label' => 'Medical Devices',   'href' => '#', 'sort_order' => 1],
            ['label' => 'Health Supplements','href' => '#', 'sort_order' => 2],
        ]);
        $beauty->children()->createMany([
            ['label' => 'Skin Care',         'href' => '#', 'sort_order' => 1],
            ['label' => 'Hair Care',         'href' => '#', 'sort_order' => 2],
        ]);
        $baby->children()->createMany([
            ['label' => 'Baby Milk',         'href' => '#', 'sort_order' => 1],
            ['label' => 'Diapers',           'href' => '#', 'sort_order' => 2],
            ['label' => 'Baby Accessories',  'href' => '#', 'sort_order' => 3],
        ]);

        // ── 3. Categories ────────────────────────────────────────────────────
        Category::truncate();
        $catHealthcare   = Category::create(['name'=>'Healthcare',        'icon_class'=>'fas fa-pills',                   'icon_color'=>'#e65100', 'bg_color'=>'#fff3e0', 'sort_order'=>1]);
        $catSupplement   = Category::create(['name'=>'Health Supplement', 'icon_class'=>'fas fa-capsules',                'icon_color'=>'#2E7D32', 'bg_color'=>'#e8f5e9', 'sort_order'=>2]);
        $catDevice       = Category::create(['name'=>'Medical Device',    'icon_class'=>'fas fa-heartbeat',               'icon_color'=>'#1565c0', 'bg_color'=>'#e3f2fd', 'sort_order'=>3]);
        $catMilk         = Category::create(['name'=>'Milk',              'icon_class'=>'fas fa-baby-carriage',           'icon_color'=>'#880e4f', 'bg_color'=>'#fce4ec', 'sort_order'=>4]);
        $catBabyCare     = Category::create(['name'=>'Baby Care',         'icon_class'=>'fas fa-baby',                    'icon_color'=>'#e91e63', 'bg_color'=>'#fce4ec', 'sort_order'=>5]);
        $catPersonalCare = Category::create(['name'=>'Personal Care',     'icon_class'=>'fas fa-pump-soap',               'icon_color'=>'#6a1b9a', 'bg_color'=>'#f3e5f5', 'sort_order'=>6]);
        $catOTC          = Category::create(['name'=>'OTC',               'icon_class'=>'fas fa-prescription-bottle-alt', 'icon_color'=>'#f57f17', 'bg_color'=>'#fff8e1', 'sort_order'=>7]);
        $catAllergy      = Category::create(['name'=>'Allergy',           'icon_class'=>'fas fa-allergies',               'icon_color'=>'#6a1b9a', 'bg_color'=>'#f3e5f5', 'sort_order'=>8,  'is_active'=>false]);
        $catVitaminC     = Category::create(['name'=>'Vitamin C',         'icon_class'=>'fas fa-capsules',                'icon_color'=>'#f57f17', 'bg_color'=>'#fff8e1', 'sort_order'=>9,  'is_active'=>false]);
        $catPainRelief   = Category::create(['name'=>'Adult Pain Relief', 'icon_class'=>'fas fa-pills',                   'icon_color'=>'#c62828', 'bg_color'=>'#ffebee', 'sort_order'=>10, 'is_active'=>false]);
        $catCardio       = Category::create(['name'=>'Cardiovascular',    'icon_class'=>'fas fa-heartbeat',               'icon_color'=>'#b71c1c', 'bg_color'=>'#ffebee', 'sort_order'=>11, 'is_active'=>false]);
        $catVitaminE     = Category::create(['name'=>'Vitamin E',         'icon_class'=>'fas fa-capsules',                'icon_color'=>'#4e342e', 'bg_color'=>'#efebe9', 'sort_order'=>12, 'is_active'=>false]);
        $catCough        = Category::create(['name'=>'Cough',             'icon_class'=>'fas fa-lungs',                   'icon_color'=>'#0277bd', 'bg_color'=>'#e1f5fe', 'sort_order'=>13, 'is_active'=>false]);
        $catMultivit     = Category::create(['name'=>'Multivitamin Adult','icon_class'=>'fas fa-capsules',                'icon_color'=>'#558b2f', 'bg_color'=>'#f1f8e9', 'sort_order'=>14, 'is_active'=>false]);

        // ── 4. Brands (ticker + featured) ────────────────────────────────────
        Brand::truncate();
        $bPhilcare      = Brand::create(['name'=>'Philcare',          'ticker_image'=>'logos/philcare.png',       'show_in_ticker'=>true,  'show_in_featured'=>false, 'sort_order'=>1]);
        $bImmuniPlus    = Brand::create(['name'=>'Immuni+',           'ticker_image'=>'logos/immuni-plus.png',    'featured_image'=>'logos/immuniplus_featured.png',  'featured_color'=>'darkred',  'show_in_ticker'=>true,  'show_in_featured'=>true,  'sort_order'=>2]);
        $bEfficasent    = Brand::create(['name'=>'Efficasent',        'ticker_image'=>'logos/efficasent.png',     'show_in_ticker'=>true,  'show_in_featured'=>false, 'sort_order'=>3]);
        $bNeurobion     = Brand::create(['name'=>'Neurobion',         'ticker_image'=>'logos/neurobion.png',      'featured_image'=>'logos/neurobion_featured.png',   'featured_color'=>'orange',   'show_in_ticker'=>true,  'show_in_featured'=>true,  'sort_order'=>4]);
        $bIPI           = Brand::create(['name'=>'IPI',               'ticker_image'=>'logos/ipi.png',            'featured_image'=>'logos/ipi_featured.png',         'featured_color'=>'white',    'show_in_ticker'=>true,  'show_in_featured'=>true,  'sort_order'=>5]);
        $bAlaxan        = Brand::create(['name'=>'Alaxan',            'ticker_image'=>'logos/alaxan.webp',        'featured_image'=>'logos/alaxan-featured.jfif',     'featured_color'=>'white',    'show_in_ticker'=>true,  'show_in_featured'=>true,  'sort_order'=>6]);
        $bEnervon       = Brand::create(['name'=>'Enervon',           'ticker_image'=>'logos/enervon.jpg',        'show_in_ticker'=>true,  'show_in_featured'=>false, 'sort_order'=>7]);
        $bLadysChoice   = Brand::create(['name'=>'Ladys Choice',      'ticker_image'=>'logos/ladyschoice.png',    'show_in_ticker'=>true,  'show_in_featured'=>false, 'sort_order'=>8]);
        $bOrganica      = Brand::create(['name'=>'Organica',          'ticker_image'=>'logos/organica.webp',      'show_in_ticker'=>true,  'show_in_featured'=>false, 'sort_order'=>9]);
        $bNestle        = Brand::create(['name'=>'Nestlé',            'ticker_image'=>'logos/nestle.png',         'show_in_ticker'=>true,  'show_in_featured'=>false, 'sort_order'=>10]);
        $bRitemed       = Brand::create(['name'=>'Ritemed',           'ticker_image'=>'logos/ritemed.png',        'featured_image'=>'logos/ritemed_featured.jpg',     'featured_color'=>'white',    'show_in_ticker'=>true,  'show_in_featured'=>true,  'sort_order'=>11]);
        $bUnilab        = Brand::create(['name'=>'Unilab',            'ticker_image'=>'logos/unilab.png',         'featured_image'=>'logos/unilab_featured.png',      'featured_color'=>'darkgreen','show_in_ticker'=>true,  'show_in_featured'=>true,  'sort_order'=>12]);
        $bNescafe       = Brand::create(['name'=>'Nescafe',           'ticker_image'=>'logos/nescafe.png',        'show_in_ticker'=>true,  'show_in_featured'=>false, 'sort_order'=>13]);
        $bPedigree      = Brand::create(['name'=>'Pedigree',          'ticker_image'=>'logos/pedigree.png',       'show_in_ticker'=>true,  'show_in_featured'=>false, 'sort_order'=>14]);
        $bPropan        = Brand::create(['name'=>'Propan TLC',        'ticker_image'=>'logos/Propan-TLC.jpg',     'show_in_ticker'=>true,  'show_in_featured'=>false, 'sort_order'=>15]);
        $bMyra          = Brand::create(['name'=>'Myra',              'ticker_image'=>'logos/myra.webp',          'show_in_ticker'=>true,  'show_in_featured'=>false, 'sort_order'=>16]);
        $bSafeguard     = Brand::create(['name'=>'Safeguard',         'ticker_image'=>'logos/safeguard.png',      'show_in_ticker'=>true,  'show_in_featured'=>false, 'sort_order'=>17]);
        $bHeadShoulders = Brand::create(['name'=>'Head And Shoulders', 'ticker_image'=>'logos/head&shoulders.png','show_in_ticker'=>true,  'show_in_featured'=>false, 'sort_order'=>18]);
        $bGuardian      = Brand::create(['name'=>'Guardian',          'show_in_ticker'=>false, 'show_in_featured'=>false, 'sort_order'=>19]);
        $bAbbott        = Brand::create(['name'=>'Abbott',            'show_in_ticker'=>false, 'show_in_featured'=>false, 'sort_order'=>20]);
        $bBayer         = Brand::create(['name'=>'Bayer',             'show_in_ticker'=>false, 'show_in_featured'=>false, 'sort_order'=>21]);

        // ── 5. Sections ───────────────────────────────────────────────────────
        Section::truncate();
        $secHotDeals  = Section::create(['key'=>'hot_deals',   'label'=>'Hot Deals of the Month',    'description'=>'View Our Hot Deals Products of the Month',                 'heading_color'=>'red',  'sort_order'=>1]);
        $secSale      = Section::create(['key'=>'sale',        'label'=>'SALE',                      'description'=>'Great Deals, Healthy Savings – Shop & Save at GG Pharmacy!','heading_color'=>'red',  'sort_order'=>2]);
        $secPromoPack = Section::create(['key'=>'promo_packs', 'label'=>'PROMO PACKS',               'description'=>'Exclusive Value Packs – Bundled Savings on Customer Favorites','heading_color'=>'text','sort_order'=>3]);
        $secGuardian  = Section::create(['key'=>'guardian',    'label'=>'Guardian Special Deals',    'description'=>'All our new arrivals in an exclusive brand selection',       'heading_color'=>'red',  'sort_order'=>4]);
        $secGenerics  = Section::create(['key'=>'generics',    'label'=>'Pharmacy Generics',      'description'=>'All our new arrivals in a exclusive brand selection',        'heading_color'=>'red',  'sort_order'=>5]);
        $secFeatured  = Section::create(['key'=>'featured',    'label'=>'FEATURED PRODUCTS',         'description'=>'',                                                           'heading_color'=>'red',  'sort_order'=>6]);

        // ── 6. Products ───────────────────────────────────────────────────────
        Product::truncate();
        $ph = 'https://via.placeholder.com/140x140/eee/999?text=';
        $ps = 'https://via.placeholder.com/120x120/eee/999?text=';
        $pi = 'https://via.placeholder.com/110x110/eee/999?text=';

        // ── Hot Deals ─────────────────────────────────────────────────────────
        $secHotDeals->products()->createMany([
            [
                'name'          => 'Casino Alcohol 500Ml',
                'price'         => 98.00,
                'image'         => $ph.'Casino+Alcohol',
                'category_id'   => $catOTC->id,
                'description'   => 'Casino Ethyl Alcohol 70% solution is an effective antiseptic for everyday use. Kills 99.9% of germs and bacteria on contact, keeping you protected throughout the day.',
                'origin'        => 'Philippines',
                'product_usage' => 'Apply directly to skin or affected area. For hand sanitizing, pour a small amount onto palms and rub together until dry. For wound cleaning, apply with a cotton ball.',
                'ingredients'   => 'Ethyl Alcohol 70%, Purified Water, Glycerin',
                'warnings'      => 'For external use only. Keep away from fire or flame. Avoid contact with eyes. Keep out of reach of children.',
                'width'         => 6.50,
                'height'        => 22.00,
                'depth'         => 6.50,
                'sort_order'    => 1,
            ],
            [
                'name'          => 'KOOLFEVER GEL CHILDREN 2 Pcs',
                'price'         => 52.00,
                'image'         => $ph.'Koolfever',
                'category_id'   => $catHealthcare->id,
                'description'   => 'Koolfever Cooling Gel Sheets for children provide gentle, soothing relief from fever. Each sheet provides up to 8 hours of cooling comfort without the need for medication.',
                'origin'        => 'Japan',
                'product_usage' => 'Peel off the protective film and apply directly to the forehead. Replace with a new sheet every 8 hours or when the sheet is no longer cool.',
                'ingredients'   => 'Water, Polyacrylic Acid, Sodium Polyacrylate, Glycerin, Menthol',
                'warnings'      => 'Do not apply to broken or irritated skin. Keep out of reach of children. Consult a doctor if fever persists beyond 3 days.',
                'width'         => 14.00,
                'height'        => 9.00,
                'depth'         => 1.00,
                'sort_order'    => 2,
            ],
            [
                'name'          => "Dr. Wong's Sulfur Soap W/ Moisturizer",
                'price'         => 67.50,
                'image'         => $ph.'Sulfur+Soap',
                'category_id'   => $catPersonalCare->id,
                'description'   => "Dr. Wong's Sulfur Soap is a dermatologist-recommended soap formulated with sulfur for the treatment of acne, pimples, and other skin blemishes. Enhanced with moisturizers to prevent skin dryness.",
                'origin'        => 'Philippines',
                'product_usage' => 'Wet face and body. Lather soap and apply gently to affected areas. Leave on for 1-2 minutes then rinse thoroughly. Use twice daily for best results.',
                'ingredients'   => 'Sulfur 3%, Sodium Lauryl Sulfate, Glycerin, Coconut Oil, Shea Butter, Fragrance',
                'warnings'      => 'Avoid contact with eyes. Discontinue use if irritation develops. Keep out of reach of children.',
                'width'         => 8.00,
                'height'        => 5.50,
                'depth'         => 3.00,
                'sort_order'    => 3,
            ],
            [
                'name'          => 'White Flower No. 3 5Ml',
                'price'         => 96.75,
                'image'         => $ph.'White+Flower',
                'category_id'   => $catOTC->id,
                'description'   => 'White Flower Embrocation is a multi-purpose analgesic oil used to relieve headaches, dizziness, insect bites, and minor muscle aches. A trusted household remedy for generations.',
                'origin'        => 'Hong Kong',
                'product_usage' => 'For headache: apply a small amount to temples and gently massage. For muscle pain: massage onto affected area. For insect bites: apply directly to the bite. Use as needed.',
                'ingredients'   => 'Lavender Oil, Eucalyptus Oil, Peppermint Oil, Methyl Salicylate, Menthol',
                'warnings'      => 'For external use only. Avoid contact with eyes and mucous membranes. Keep away from children. Do not apply to broken skin.',
                'width'         => 3.00,
                'height'        => 8.00,
                'depth'         => 3.00,
                'sort_order'    => 4,
            ],
            [
                'name'          => 'KOOLFEVER ADULT EXTRA Cooling',
                'price'         => 55.75,
                'image'         => $ph.'Koolfever+Extra',
                'category_id'   => $catHealthcare->id,
                'description'   => 'Koolfever Extra Strength Cooling Gel Sheets for adults deliver an enhanced cooling effect for fast fever relief. Each sheet provides up to 8 hours of cooling comfort without medication.',
                'origin'        => 'Japan',
                'product_usage' => 'Peel off the protective film and apply directly to the forehead. Replace with a new sheet every 8 hours or when the cooling effect diminishes.',
                'ingredients'   => 'Water, Polyacrylic Acid, Sodium Polyacrylate, Glycerin, Menthol (Enhanced Formula)',
                'warnings'      => 'Do not apply to broken or irritated skin. Consult a doctor if fever exceeds 39°C or persists beyond 3 days.',
                'width'         => 14.00,
                'height'        => 9.50,
                'depth'         => 1.00,
                'sort_order'    => 5,
            ],
        ]);

        // ── Sale ──────────────────────────────────────────────────────────────
        $secSale->products()->createMany([
            [
                'name'          => 'Livity Prime',
                'price'         => 885.00,
                'badge'         => 'MOST SOLD',
                'badge_type'    => 'most-sold',
                'image'         => $ph.'Livity',
                'description'   => 'Livity Prime is a premium multivitamin supplement formulated to support overall health, boost energy levels, and strengthen the immune system. Ideal for active adults seeking daily nutritional support.',
                'origin'        => 'Philippines',
                'product_usage' => 'Take one capsule daily after a meal, or as directed by your physician. Best taken in the morning.',
                'ingredients'   => 'Vitamin A, Vitamin C, Vitamin D3, Vitamin E, Vitamin B Complex, Zinc, Selenium, Folic Acid',
                'sort_order'    => 1,
            ],
            [
                'name'          => 'Livity Prime 2',
                'price'         => 1850.00,
                'badge'         => 'MOST SOLD',
                'badge_type'    => 'most-sold',
                'image'         => $ph.'Livity+2',
                'description'   => 'Livity Prime 2 is an advanced formula multivitamin with added antioxidants and minerals for comprehensive health support. Double the potency for maximum benefit.',
                'origin'        => 'Philippines',
                'product_usage' => 'Take one to two capsules daily after a meal, or as directed by your physician.',
                'ingredients'   => 'Vitamin A, Vitamin C, Vitamin D3, Vitamin E, Vitamin B Complex, Zinc, Selenium, CoQ10, Alpha Lipoic Acid, Folic Acid',
                'sort_order'    => 2,
            ],
            [
                'name'          => 'Arthricin 9+1 Tipid Pack',
                'price'         => 333.00,
                'badge'         => 'MOST SOLD',
                'badge_type'    => 'most-sold',
                'image'         => $ph.'Arthricin+9%2B1',
                'category_id'   => $catOTC->id,
                'description'   => 'Arthricin is a trusted pain reliever specifically formulated for joint pain and arthritis relief. This 9+1 Tipid Pack offers 10 tablets at the price of 9, giving you more savings on every purchase.',
                'origin'        => 'Philippines',
                'product_usage' => 'Adults and children 12 years and above: Take 1 tablet every 6 to 8 hours as needed. Do not exceed 3 tablets in 24 hours.',
                'ingredients'   => 'Mefenamic Acid 500mg per tablet',
                'warnings'      => 'Not recommended for patients with peptic ulcer, kidney disease, or those taking blood thinners. Consult a doctor before use.',
                'sort_order'    => 3,
            ],
            [
                'name'          => 'Liveraide 9+1 Tipid Pack',
                'price'         => 189.00,
                'badge'         => 'MOST SOLD',
                'badge_type'    => 'most-sold',
                'image'         => $ph.'Liveraide+9%2B1',
                'category_id'   => $catSupplement->id,
                'description'   => 'Liveraide is a liver health supplement that supports detoxification, protects liver cells from damage, and promotes overall liver function. The 9+1 Tipid Pack gives you bonus savings.',
                'origin'        => 'Philippines',
                'product_usage' => 'Take 1 capsule 3 times daily after meals, or as directed by your physician.',
                'ingredients'   => 'Silymarin 140mg (from Milk Thistle Extract), Microcrystalline Cellulose, Magnesium Stearate',
                'warnings'      => 'Consult a physician before use if pregnant, lactating, or taking other medications.',
                'sort_order'    => 4,
            ],
            [
                'name'          => 'Optein 9+1 Tipid Pack',
                'price'         => 281.25,
                'badge'         => 'MOST SOLD',
                'badge_type'    => 'most-sold',
                'image'         => $ph.'Optein+9%2B1',
                'category_id'   => $catSupplement->id,
                'description'   => 'Optein is an eye health supplement enriched with Lutein and Zeaxanthin to protect against macular degeneration, reduce eye strain, and support clear vision. The 9+1 Tipid Pack offers exceptional value.',
                'origin'        => 'Philippines',
                'product_usage' => 'Take 1 softgel capsule daily after a meal, or as directed by your physician.',
                'ingredients'   => 'Lutein 20mg, Zeaxanthin 4mg, Vitamin C, Vitamin E, Zinc, Omega-3 Fatty Acids',
                'sort_order'    => 5,
            ],
            [
                'name'          => 'Dermplus Sunscreen',
                'price'         => 445.50,
                'old_price'     => 495.00,
                'badge'         => '-10%',
                'badge_type'    => 'sale-badge',
                'image'         => $ph.'Dermplus',
                'category_id'   => $catPersonalCare->id,
                'description'   => 'Dermplus Sunscreen SPF 50 PA+++ provides broad-spectrum UVA and UVB protection. Its lightweight, non-greasy formula absorbs quickly and is suitable for all skin types, including sensitive skin.',
                'origin'        => 'Philippines',
                'product_usage' => 'Apply generously to all exposed skin 15 minutes before sun exposure. Reapply every 2 hours or after swimming, sweating, or towel drying.',
                'ingredients'   => 'Avobenzone, Homosalate, Octinoxate, Octocrylene, Titanium Dioxide, Niacinamide, Hyaluronic Acid, Aloe Vera Extract',
                'warnings'      => 'For external use only. Avoid contact with eyes. Discontinue use if rash or irritation occurs.',
                'width'         => 5.00,
                'height'        => 15.00,
                'depth'         => 3.50,
                'sort_order'    => 6,
            ],
        ]);

        // ── Promo Packs ───────────────────────────────────────────────────────
        $secPromoPack->products()->createMany([
            [
                'name'          => 'Claritin Tablet 10Mg (4+1 Pack)',
                'price'         => 152.00,
                'image'         => $ph.'Claritin+4%2B1',
                'category_id'   => $catAllergy->id,
                'description'   => 'Claritin (Loratadine 10mg) is a non-drowsy antihistamine that provides 24-hour relief from allergy symptoms including sneezing, runny nose, and itchy eyes. This 4+1 promo pack offers extra savings.',
                'origin'        => 'Belgium',
                'product_usage' => 'Adults and children 12 years and above: Take 1 tablet once daily. Children 6–11 years: half a tablet once daily. Take with or without food.',
                'ingredients'   => 'Loratadine 10mg, Lactose Monohydrate, Corn Starch, Magnesium Stearate',
                'warnings'      => 'Consult a doctor before use if you have liver or kidney disease. Not recommended for children under 6 years.',
                'sort_order'    => 1,
            ],
            [
                'name'          => 'Rogin-E Soft Gel Capsule (Promo Pack)',
                'price'         => 173.25,
                'image'         => $ph.'Rogin-E',
                'category_id'   => $catMultivit->id,
                'description'   => 'Rogin-E is a complete multivitamin and mineral supplement with ginseng extract. It helps boost energy, enhance mental alertness, and support overall vitality for adults.',
                'origin'        => 'Philippines',
                'product_usage' => 'Take 1 softgel capsule daily after a meal, or as directed by your physician.',
                'ingredients'   => 'Vitamin A, Vitamin B Complex, Vitamin C, Vitamin D, Vitamin E, Ginseng Extract, Zinc, Iron, Calcium',
                'sort_order'    => 2,
            ],
            [
                'name'          => 'Sinecod Forte 7+1 Tipid Pack',
                'price'         => 155.00,
                'image'         => $ph.'Sinecod+7%2B1',
                'category_id'   => $catCough->id,
                'description'   => 'Sinecod Forte (Butamirate Citrate) is a non-narcotic cough suppressant that effectively relieves dry, irritating cough. The 7+1 Tipid Pack gives you 8 tablets at the price of 7.',
                'origin'        => 'Switzerland',
                'product_usage' => 'Adults: Take 1 tablet 3 times daily. Children 12 years and above: same as adult dose. Do not exceed prescribed dosage.',
                'ingredients'   => 'Butamirate Citrate 50mg per tablet',
                'warnings'      => 'Do not use with other cough medicines. Consult a doctor if cough persists for more than 7 days.',
                'sort_order'    => 3,
            ],
            [
                'name'          => 'Tempra Forte 500mg Tablet',
                'price'         => 35.75,
                'image'         => $ph.'Tempra+Forte',
                'category_id'   => $catPainRelief->id,
                'description'   => 'Tempra Forte (Paracetamol 500mg) provides fast and effective relief from mild to moderate pain including headache, toothache, and muscle aches, as well as fever reduction.',
                'origin'        => 'Philippines',
                'product_usage' => 'Adults: Take 1–2 tablets every 4 to 6 hours as needed. Do not exceed 8 tablets (4000mg) in 24 hours.',
                'ingredients'   => 'Paracetamol 500mg, Corn Starch, Stearic Acid, Povidone',
                'warnings'      => 'Do not exceed recommended dose. Avoid alcohol while taking this medication. Consult a doctor if pain or fever persists beyond 3 days.',
                'sort_order'    => 4,
            ],
            [
                'name'          => 'Flanax 275mg Tablet (Promo Pack)',
                'price'         => 105.75,
                'image'         => $ph.'Flanax+275',
                'category_id'   => $catPainRelief->id,
                'description'   => 'Flanax (Naproxen Sodium 275mg) is a nonsteroidal anti-inflammatory drug (NSAID) used to relieve mild to moderate pain, fever, and inflammation. Fast-acting formula for targeted relief.',
                'origin'        => 'Mexico',
                'product_usage' => 'Adults: Take 1 tablet every 8 to 12 hours as needed. Do not exceed 3 tablets in 24 hours. Take with food or milk to reduce stomach upset.',
                'ingredients'   => 'Naproxen Sodium 275mg, Magnesium Oxide, Microcrystalline Cellulose, Povidone, Talc',
                'warnings'      => 'Not for patients with peptic ulcer, aspirin allergy, or kidney impairment. Avoid long-term use without medical supervision.',
                'sort_order'    => 5,
            ],
            [
                'name'          => 'Rogin-E Softgel Capsule (12s Pack)',
                'price'         => 280.00,
                'image'         => $ph.'Rogin-E+12',
                'category_id'   => $catMultivit->id,
                'description'   => 'Rogin-E 12s Pack is a value pack of the popular ginseng-enriched multivitamin supplement. Ideal for those who want sustained daily nutritional support over a 12-day period.',
                'origin'        => 'Philippines',
                'product_usage' => 'Take 1 softgel capsule daily after a meal. Best taken consistently at the same time each day.',
                'ingredients'   => 'Vitamin A, Vitamin B Complex, Vitamin C, Vitamin D, Vitamin E, Ginseng Extract, Zinc, Iron, Calcium',
                'sort_order'    => 6,
            ],
        ]);

        // ── Guardian Special Deals ────────────────────────────────────────────
        $secGuardian->products()->createMany([
            [
                'name'          => 'Guardian Comfort & Assurance Pads',
                'price'         => 449.00,
                'image'         => $ps.'Comfort+Pads',
                'brand_id'      => $bGuardian->id,
                'description'   => 'Guardian Comfort & Assurance Pads offer superior protection with ultra-absorbent technology. Designed for maximum comfort and discretion throughout the day and night.',
                'origin'        => 'Malaysia',
                'product_usage' => 'Place pad in underwear with adhesive side facing down. Change as needed throughout the day. Dispose of used pads hygienically.',
                'ingredients'   => 'Super-absorbent polymer, Non-woven cover, Adhesive strips, Breathable backsheet',
                'width'         => 20.00,
                'height'        => 28.00,
                'depth'         => 8.00,
                'sort_order'    => 1,
            ],
            [
                'name'          => 'Guardian Box Tissue (100 sheets)',
                'price'         => 69.00,
                'image'         => $ps.'Box+Tissue',
                'brand_id'      => $bGuardian->id,
                'description'   => 'Guardian Box Tissue is made from premium soft paper for gentle use on skin. Each box contains 100 two-ply sheets, ideal for the home, office, or on-the-go.',
                'origin'        => 'Malaysia',
                'product_usage' => 'Pull one tissue at a time from the box opening. Dispose of used tissue in a waste bin.',
                'ingredients'   => '100% Virgin Pulp Paper, Fragrance-Free',
                'width'         => 24.00,
                'height'        => 12.00,
                'depth'         => 12.00,
                'sort_order'    => 2,
            ],
            [
                'name'          => 'Guardian Antibacterial Hand Wash',
                'price'         => 199.00,
                'image'         => $ps.'Antibacterial',
                'brand_id'      => $bGuardian->id,
                'description'   => 'Guardian Antibacterial Hand Wash effectively eliminates 99.9% of germs while being gentle on your hands. Enriched with moisturizing agents to keep skin soft and hydrated.',
                'origin'        => 'Malaysia',
                'product_usage' => 'Wet hands with water. Apply a small amount of hand wash and lather for at least 20 seconds. Rinse thoroughly and dry with a clean towel.',
                'ingredients'   => 'Triclosan 0.3%, Sodium Laureth Sulfate, Cocamidopropyl Betaine, Glycerin, Aloe Vera Extract, Fragrance',
                'warnings'      => 'For external use only. Avoid contact with eyes. Keep out of reach of children.',
                'width'         => 7.00,
                'height'        => 18.00,
                'depth'         => 5.00,
                'sort_order'    => 3,
            ],
            [
                'name'          => 'Guardian Kids Strawberry Toothpaste',
                'price'         => 189.00,
                'image'         => $ps.'Kids+Strawb',
                'brand_id'      => $bGuardian->id,
                'category_id'   => $catBabyCare->id,
                'description'   => "Guardian Kids Strawberry Toothpaste is specially formulated for children's delicate teeth and gums. The fun strawberry flavor makes brushing enjoyable while providing fluoride protection against cavities.",
                'origin'        => 'Malaysia',
                'product_usage' => 'Children 3–6 years: Use a pea-sized amount with parental supervision. Children above 6: Use a small amount and brush teeth thoroughly twice daily.',
                'ingredients'   => 'Sodium Monofluorophosphate 1000ppm, Hydrated Silica, Sorbitol, Glycerin, Strawberry Flavor, Sodium Lauryl Sulfate',
                'warnings'      => 'Do not swallow. Keep out of reach of children under 3 years. If accidentally swallowed, seek medical help immediately.',
                'width'         => 4.00,
                'height'        => 16.00,
                'depth'         => 4.00,
                'sort_order'    => 4,
            ],
        ]);

        // ── GG Pharmacy Generics ──────────────────────────────────────────────
        $secGenerics->products()->createMany([
            [
                'name'          => 'Cetirizine 10mg Tablet (Ritemed)',
                'price'         => 115.00,
                'image'         => $ps.'Cetirizine',
                'category_id'   => $catAllergy->id,
                'brand_id'      => $bRitemed->id,
                'description'   => 'Ritemed Cetirizine 10mg is a second-generation antihistamine that provides 24-hour non-drowsy relief from allergy symptoms such as hay fever, allergic rhinitis, and urticaria (hives).',
                'origin'        => 'Philippines',
                'product_usage' => 'Adults and children 12 years and above: Take 1 tablet once daily. May be taken with or without food.',
                'ingredients'   => 'Cetirizine Hydrochloride 10mg, Lactose, Corn Starch, Microcrystalline Cellulose, Magnesium Stearate',
                'warnings'      => 'May cause drowsiness in some patients. Avoid alcohol and driving if affected. Consult a doctor for use during pregnancy.',
                'sort_order'    => 1,
            ],
            [
                'name'          => 'Paracetamol 500mg Tablet (Ritemed)',
                'price'         => 27.50,
                'image'         => $ps.'Paracetamol+500',
                'category_id'   => $catPainRelief->id,
                'brand_id'      => $bRitemed->id,
                'description'   => 'Ritemed Paracetamol 500mg is a widely trusted analgesic and antipyretic for the relief of mild to moderate pain and fever. Safe and effective for adults and children.',
                'origin'        => 'Philippines',
                'product_usage' => 'Adults: 1–2 tablets every 4–6 hours as needed. Maximum dose: 4000mg per day. Take with a full glass of water.',
                'ingredients'   => 'Paracetamol 500mg, Corn Starch, Stearic Acid, Povidone',
                'warnings'      => 'Do not take with other paracetamol-containing medicines. Avoid excessive alcohol intake. Consult a doctor if symptoms persist.',
                'sort_order'    => 2,
            ],
            [
                'name'          => 'Clopidogrel 75mg Tablet (Ritemed)',
                'price'         => 18.50,
                'image'         => $ps.'Clopidogrel',
                'category_id'   => $catCardio->id,
                'brand_id'      => $bRitemed->id,
                'description'   => 'Ritemed Clopidogrel 75mg is an antiplatelet medication that prevents blood clots in patients with heart disease, recent heart attack, stroke, or peripheral arterial disease.',
                'origin'        => 'Philippines',
                'product_usage' => 'Take 1 tablet once daily with or without food, or as directed by your physician. Do not stop taking without consulting your doctor.',
                'ingredients'   => 'Clopidogrel Bisulfate 97.875mg (equivalent to 75mg base), Microcrystalline Cellulose, Mannitol, Hydroxypropyl Cellulose',
                'warnings'      => 'May increase risk of bleeding. Inform your doctor before any surgical procedures. Not for use in patients with active bleeding disorders.',
                'sort_order'    => 3,
            ],
            [
                'name'          => 'D-Alpha Vitamin E 400 IU (Ritemed)',
                'price'         => 90.00,
                'image'         => $ps.'Vitamin+E',
                'category_id'   => $catVitaminE->id,
                'brand_id'      => $bRitemed->id,
                'description'   => 'Ritemed D-Alpha Vitamin E 400 IU is a natural-source antioxidant vitamin supplement that helps protect cells from oxidative stress, supports immune function, and promotes healthy skin.',
                'origin'        => 'Philippines',
                'product_usage' => 'Take 1 softgel capsule daily after a meal, or as directed by your physician.',
                'ingredients'   => 'D-Alpha-Tocopherol 400 IU (natural source), Soybean Oil, Gelatin, Glycerin',
                'warnings'      => 'Consult a doctor before use if taking blood thinners or other fat-soluble vitamins. Do not exceed recommended dose.',
                'sort_order'    => 4,
            ],
            [
                'name'          => 'Losartan 50mg Tablet (Ritemed)',
                'price'         => 11.75,
                'image'         => $pi.'Losartan',
                'category_id'   => $catCardio->id,
                'brand_id'      => $bRitemed->id,
                'description'   => 'Ritemed Losartan 50mg is an angiotensin II receptor blocker (ARB) used for the treatment of hypertension (high blood pressure) and to protect the kidneys in patients with type 2 diabetes.',
                'origin'        => 'Philippines',
                'product_usage' => 'Take 1 tablet once daily with or without food. The usual dose is 50mg once daily. Your doctor may adjust the dose based on your condition.',
                'ingredients'   => 'Losartan Potassium 50mg, Microcrystalline Cellulose, Lactose Monohydrate, Pregelatinized Starch, Magnesium Stearate',
                'warnings'      => 'Do not use if pregnant or planning pregnancy. Monitor blood pressure regularly. Do not stop without medical advice.',
                'sort_order'    => 5,
            ],
            [
                'name'          => 'Amlodipine 10mg Tablet (Ritemed)',
                'price'         => 8.25,
                'image'         => $pi.'Amlodipine+10',
                'category_id'   => $catCardio->id,
                'brand_id'      => $bRitemed->id,
                'description'   => 'Ritemed Amlodipine 10mg is a calcium channel blocker used in the management of hypertension and angina (chest pain). It helps relax blood vessels and improves blood flow.',
                'origin'        => 'Philippines',
                'product_usage' => 'Take 1 tablet once daily. May be taken with or without food. Take at the same time each day for best results.',
                'ingredients'   => 'Amlodipine Besylate 13.87mg (equivalent to 10mg base), Microcrystalline Cellulose, Dibasic Calcium Phosphate, Sodium Starch Glycolate, Magnesium Stearate',
                'warnings'      => 'May cause dizziness or swelling of the ankles. Do not stop taking abruptly. Inform your doctor of all other medications being taken.',
                'sort_order'    => 6,
            ],
            [
                'name'          => 'Simvastatin 20mg Tablet (Ritemed)',
                'price'         => 10.50,
                'image'         => $pi.'Simvastatin',
                'category_id'   => $catCardio->id,
                'brand_id'      => $bRitemed->id,
                'description'   => 'Ritemed Simvastatin 20mg is a cholesterol-lowering medication (statin) used alongside a healthy diet to reduce LDL (bad) cholesterol and triglycerides and increase HDL (good) cholesterol.',
                'origin'        => 'Philippines',
                'product_usage' => 'Take 1 tablet once daily in the evening, with or without food. Avoid grapefruit juice while on this medication.',
                'ingredients'   => 'Simvastatin 20mg, Ascorbic Acid, Butylated Hydroxyanisole, Citric Acid Anhydrous, Microcrystalline Cellulose, Magnesium Stearate',
                'warnings'      => 'Report any unexplained muscle pain or weakness to your doctor immediately. Avoid excessive alcohol. Not for use during pregnancy.',
                'sort_order'    => 7,
            ],
            [
                'name'          => 'Amlodipine 5mg Tablet (Ritemed)',
                'price'         => 6.00,
                'image'         => $pi.'Amlodipine+5',
                'category_id'   => $catCardio->id,
                'brand_id'      => $bRitemed->id,
                'description'   => 'Ritemed Amlodipine 5mg is a lower-dose calcium channel blocker for patients with mild to moderate hypertension or those requiring initial titration of blood pressure therapy.',
                'origin'        => 'Philippines',
                'product_usage' => 'Take 1 tablet once daily with or without food. Your doctor may increase the dose to 10mg after 7–14 days if blood pressure is not adequately controlled.',
                'ingredients'   => 'Amlodipine Besylate 6.94mg (equivalent to 5mg base), Microcrystalline Cellulose, Dibasic Calcium Phosphate, Sodium Starch Glycolate, Magnesium Stearate',
                'warnings'      => 'Do not stop taking abruptly. Inform your doctor of all medications being taken. May cause dizziness on standing up.',
                'sort_order'    => 8,
            ],
        ]);

        // ── Featured Products ─────────────────────────────────────────────────
        $secFeatured->products()->createMany([
            [
                'name'          => 'Guardian Face & Body SPF 50 Sunscreen',
                'price'         => 299.00,
                'badge'         => 'MOST SOLD',
                'badge_type'    => 'most-sold',
                'image'         => $ph.'Guardian+SPF50',
                'brand_id'      => $bGuardian->id,
                'description'   => 'Guardian Face & Body SPF 50 Sunscreen provides high broad-spectrum UV protection for both face and body. Lightweight, water-resistant formula keeps skin protected and moisturized all day.',
                'origin'        => 'Malaysia',
                'product_usage' => 'Apply generously to face and all exposed body areas 15 minutes before sun exposure. Reapply every 2 hours during prolonged sun exposure or after swimming.',
                'ingredients'   => 'Octinoxate, Octocrylene, Butyl Methoxydibenzoylmethane, Titanium Dioxide, Glycerin, Vitamin E, Aloe Vera',
                'warnings'      => 'For external use only. Avoid contact with eyes. Discontinue use if irritation occurs.',
                'width'         => 6.50,
                'height'        => 18.00,
                'depth'         => 4.00,
                'sort_order'    => 1,
            ],
            [
                'name'          => 'Guardian Kids Strawberry Toothpaste',
                'price'         => 189.00,
                'badge'         => 'MOST SOLD',
                'badge_type'    => 'most-sold',
                'image'         => $ph.'Kids+Strawb',
                'brand_id'      => $bGuardian->id,
                'category_id'   => $catBabyCare->id,
                'description'   => "Guardian Kids Strawberry Toothpaste is specially formulated for children's delicate teeth and gums. The fun strawberry flavor encourages regular brushing while providing effective cavity protection.",
                'origin'        => 'Malaysia',
                'product_usage' => 'Children 3–6 years: Use a pea-sized amount twice daily with parental supervision. Children above 6: Use a small amount and brush teeth thoroughly twice daily.',
                'ingredients'   => 'Sodium Monofluorophosphate 1000ppm, Hydrated Silica, Sorbitol, Glycerin, Strawberry Flavor, Sodium Lauryl Sulfate',
                'warnings'      => 'Do not swallow. Keep out of reach of children under 3 years.',
                'sort_order'    => 2,
            ],
            [
                'name'          => 'ABBOTT | Ensure Gold 850g',
                'price'         => 3782.00,
                'badge'         => 'MOST SOLD',
                'badge_type'    => 'most-sold',
                'image'         => $ph.'Ensure+Gold',
                'brand_id'      => $bAbbott->id,
                'category_id'   => $catMilk->id,
                'description'   => 'Ensure Gold is a complete and balanced nutritional supplement by Abbott, specially formulated with HMB (Beta-hydroxy-beta-methylbutyrate) to help build and maintain muscle strength for adults.',
                'origin'        => 'Singapore',
                'product_usage' => 'Add 6 level scoops (57.5g) to 200ml of water. Stir until fully dissolved. Can be consumed as a meal supplement or snack.',
                'ingredients'   => 'Corn Syrup Solids, Sucrose, Sodium Caseinate, Soy Protein Isolate, HMB, Vitamins (A, C, D, E, K, B Complex), Minerals (Calcium, Phosphorus, Iron, Zinc)',
                'warnings'      => 'Not for parenteral use. Not suitable as a sole source of nutrition unless under medical supervision. Consult a doctor if lactose intolerant.',
                'width'         => 13.00,
                'height'        => 20.00,
                'depth'         => 13.00,
                'sort_order'    => 3,
            ],
            [
                'name'          => 'Berocca Performance Effervescent Tablet',
                'price'         => 651.00,
                'badge'         => 'MOST SOLD',
                'badge_type'    => 'most-sold',
                'image'         => $ph.'Berocca+Perf',
                'brand_id'      => $bBayer->id,
                'category_id'   => $catSupplement->id,
                'description'   => 'Berocca Performance is a high-potency B-vitamin and mineral effervescent tablet by Bayer that supports mental performance, energy metabolism, and reduces fatigue during stressful periods.',
                'origin'        => 'Germany',
                'product_usage' => 'Dissolve 1 tablet in a glass of water (200–250ml) and drink immediately. Take once daily, preferably in the morning.',
                'ingredients'   => 'Vitamin C 500mg, Vitamin B1 15mg, Vitamin B2 15mg, Vitamin B3 50mg, Vitamin B5 23mg, Vitamin B6 10mg, Vitamin B7 150mcg, Vitamin B9 400mcg, Vitamin B12 10mcg, Magnesium 100mg, Zinc 10mg',
                'warnings'      => 'Contains aspartame — not suitable for phenylketonurics. May cause urine to turn bright yellow, which is harmless.',
                'width'         => 5.00,
                'height'        => 19.00,
                'depth'         => 5.00,
                'sort_order'    => 4,
            ],
            [
                'name'          => 'Fern-C 568.18mg Capsule',
                'price'         => 581.75,
                'badge'         => 'MOST SOLD',
                'badge_type'    => 'most-sold',
                'image'         => $ph.'Fern-C+Caps',
                'category_id'   => $catVitaminC->id,
                'description'   => 'Fern-C is a non-acidic Vitamin C supplement using Sodium Ascorbate for better absorption and gentler effect on the stomach. Supports immune health, collagen synthesis, and antioxidant protection.',
                'origin'        => 'Philippines',
                'product_usage' => 'Adults: Take 1 capsule once or twice daily after meals. For therapeutic use, take as directed by your physician.',
                'ingredients'   => 'Sodium Ascorbate 568.18mg (equivalent to Ascorbic Acid 500mg), Microcrystalline Cellulose, Magnesium Stearate',
                'warnings'      => 'Consult a doctor for doses above 2000mg per day. Patients on sodium-restricted diets should consult a physician.',
                'sort_order'    => 5,
            ],
            [
                'name'          => 'Centrum Silver Advance 50+ Multivitamin',
                'price'         => 378.25,
                'badge'         => 'MOST SOLD',
                'badge_type'    => 'most-sold',
                'image'         => $ph.'Centrum+Silver',
                'category_id'   => $catMultivit->id,
                'description'   => 'Centrum Silver Advance is a complete multivitamin and mineral supplement specifically formulated for adults 50 and above. Supports eye health, bone strength, heart health, and immune function.',
                'origin'        => 'USA',
                'product_usage' => 'Take 1 tablet daily with a meal. Swallow whole with a full glass of water.',
                'ingredients'   => 'Vitamin A, Vitamin C, Vitamin D3, Vitamin E, Vitamin K, B-Complex Vitamins, Calcium, Magnesium, Zinc, Selenium, Lutein, Lycopene',
                'warnings'      => 'Do not exceed recommended dose. Keep out of reach of children. Consult a doctor if taking other medications or supplements.',
                'width'         => 5.50,
                'height'        => 11.00,
                'depth'         => 5.50,
                'sort_order'    => 6,
            ],
        ]);

        // ── 7. Sliders ────────────────────────────────────────────────────────
        Slider::truncate();
        foreach ([
            ['image'=>'images/RiteMed.jpg',       'alt'=>'Pharmacy Products',   'sort_order'=>1],
            ['image'=>'images/DUROTUSS.jpg',       'alt'=>'Healthcare Services', 'sort_order'=>2],
            ['image'=>'images/webshare-min.jpg',   'alt'=>'Wellness Products',   'sort_order'=>3],
            ['image'=>'images/312018592_2217127531788714_5400026205529255685_n.jpg','alt'=>'Medicine',      'sort_order'=>4],
            ['image'=>'images/75580347_3169877399707875_2163426682566868992_n.jpg', 'alt'=>'Health Banner', 'sort_order'=>5],
        ] as $row) Slider::create($row);

        // ── 8. Promo Banners ─────────────────────────────────────────────────
        PromoBanner::truncate();
        PromoBanner::create(['image'=>'https://via.placeholder.com/400x200/C01A1A/fff?text=GET+50+OFF',    'alt'=>'Promo',        'type'=>'red',  'sort_order'=>1]);
        PromoBanner::create(['image'=>'https://via.placeholder.com/400x200/fff3e0/333?text=FREE+DELIVERY','alt'=>'Free Delivery','type'=>'beige','sort_order'=>2]);

        // ── 9. Full-Width Banners ────────────────────────────────────────────
        FullWidthBanner::truncate();
        FullWidthBanner::create(['section_key'=>'omron',   'image'=>'https://via.placeholder.com/800x220/0d47a1/fff?text=OMRON+Banner',         'alt'=>'OMRON']);
        FullWidthBanner::create(['section_key'=>'ritemed', 'image'=>'https://via.placeholder.com/800x220/f5f5f5/1a237e?text=RiteMED+Banner',    'alt'=>'RiteMED']);
        FullWidthBanner::create(['section_key'=>'alaxan',  'image'=>'https://via.placeholder.com/800x200/e65100/ffd600?text=ALAXAN+XTRA+Banner','alt'=>'ALAXAN XTRA']);

        // ── 10. Blog Posts ───────────────────────────────────────────────────
        Blog::truncate();
        Blog::create(['title'=>'New Branch Opening Sogod City',        'excerpt'=>'GG Pharmacy is pleased to announce the opening of its newest branch located at Zone 2, Sogod, Southern Leyte...', 'day'=>7,  'month'=>'SEP','comment_count'=>0]);
        Blog::create(['title'=>'GG Pharmacy Community Health Day',      'excerpt'=>'GG Pharmacy is pleased to announce a free health check-up event for residents of Sogod and surrounding areas...',  'day'=>7,  'month'=>'SEP','comment_count'=>0]);
        Blog::create(['title'=>'Sprains and Strains: What You Need to Know', 'excerpt'=>'What is the difference between sprains and strains? Sprains and muscle strains are common injuries which share similar signs...','icon_class'=>'fas fa-running','icon_color'=>'#2E7D32','icon_bg'=>'#e8f5e9','day'=>19,'month'=>'OCT','comment_count'=>0,'category_id'=>$catHealthcare->id]);
        Blog::create(['title'=>'All You Need To Know About Allergies',   'excerpt'=>'"Eeew, go away, I\'m allergic to you!" – does this sound familiar? A term often used when teasing...','icon_class'=>'fas fa-allergies','icon_color'=>'#6a1b9a','icon_bg'=>'#f3e5f5','day'=>19,'month'=>'OCT','comment_count'=>0,'category_id'=>$catAllergy->id]);

        // Re-enable FK checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}