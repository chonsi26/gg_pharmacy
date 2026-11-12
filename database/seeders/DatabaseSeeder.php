<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\FullWidthBanner;
use App\Models\NavItem;
use App\Models\Product;
use App\Models\PromoBanner;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\Staff;
use App\Models\Stock;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Disable FK checks so TRUNCATE works on tables with foreign keys
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // ── 0. Admins ────────────────────────────────────────────────────────
        DB::table('admins')->truncate();
        DB::table('admins')->insert([
            'id'               => 1,
            'first_name'       => 'Chauncey',
            'middle_name'      => 'U.',
            'last_name'        => 'Edulan',
            'email'            => 'chaunceyedulan2020@gmail.com',
            'username'         => 'cedulan',
            'phone_number'     => '+639973666290',
            'password'         => '$2y$12$MSVt47dkbARniImcC5JeIOoSz8ndlWyfKVgOJ3NC9LX.2cepXv6Se',
            'profile_picture'  => 'storage/admin_profile_pictures/PcA8wFaq08doaK3DNiZ7uWb37LTRAdYjLZUenvUE.jpg',
            'is_active'        => 1,
            'remember_token'   => null,
            'created_at'       => '2026-09-10 10:35:13',
            'updated_at'       => '2026-09-10 10:35:13',
            'deleted_at'       => null,
        ]);

        // ── 0b. Users ────────────────────────────────────────────────────────
        // Note: the users table has no middle_name / username columns, and uses
        // contact_number instead of phone_number, so those are mapped/omitted.
        DB::table('users')->truncate();
        DB::table('users')->insert([
            'id'               => 1,
            'first_name'       => 'Chauncey',
            'last_name'        => 'Edulan',
            'email'            => 'chaunceyedulan2020@gmail.com',
            'contact_number'   => '+639973666290',
            'password'         => '$2y$12$MSVt47dkbARniImcC5JeIOoSz8ndlWyfKVgOJ3NC9LX.2cepXv6Se',
            'profile_picture'  => 'storage/admin_profile_pictures/PcA8wFaq08doaK3DNiZ7uWb37LTRAdYjLZUenvUE.jpg',
            'is_active'        => 1,
            'remember_token'   => null,
            'created_at'       => '2026-09-10 10:35:13',
            'updated_at'       => '2026-09-10 10:35:13',
            'deleted_at'       => null,
        ]);

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
            'logo'             => 'images/logo1.png',
            'logo2'             => 'images/logo2.png',
            'shipping_message' => 'Welcome to GG Pharmacy. If you need help, just ask Tabby',
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

        // ── 5. Products + Stocks (Watsons catalogue) ─────────────────────────
        // Remove ALL previous products and their stock batches first.
        Stock::truncate();
        Product::truncate();

        // Source data lives in database/seeders/data/watsons_products.php (links 1–100),
        // watsons_products_2.php (links 101–200), ... — every watsons_products*.php file
        // is loaded and merged in natural order.
        $catalogueFiles = glob(database_path('seeders/data/watsons_products*.php')) ?: [];
        natsort($catalogueFiles);

        $catalogue = [];
        foreach ($catalogueFiles as $catalogueFile) {
            $catalogue = array_merge($catalogue, require $catalogueFile);
        }

        $this->command?->info('Product files loaded: ' . count($catalogueFiles) . ' (' . count($catalogue) . ' products)');

        $categoryMap = [
            'otc'        => $catOTC,
            'supplement' => $catSupplement,
            'healthcare' => $catHealthcare,   // prescription (Rx) medicines
            'baby'       => $catBabyCare,
            'personal'   => $catPersonalCare,
        ];

        // Existing brands (from step 4) are reused; any brand not found is
        // created on the fly (no ticker/featured placement, no logo).
        $brandIds    = Brand::pluck('id', 'name')->mapWithKeys(fn ($id, $name) => [mb_strtolower($name) => $id])->all();
        $brandOrder  = (int) Brand::max('sort_order');
        $brandAlias  = ['myra e' => 'myra', 'enervon activ' => 'enervon'];

        $resolveBrand = function (?string $name) use (&$brandIds, &$brandOrder, $brandAlias): ?int {
            if (blank($name)) {
                return null;
            }

            $key = mb_strtolower(trim($name));
            $key = $brandAlias[$key] ?? $key;

            if (! isset($brandIds[$key])) {
                $brandIds[$key] = Brand::create([
                    'name'             => trim($name),
                    'show_in_ticker'   => false,
                    'show_in_featured' => false,
                    'sort_order'       => ++$brandOrder,
                ])->id;
            }

            return $brandIds[$key];
        };

        $suppliers = [
            'MedSource Distributors',
            'PharmaLink Supply Co.',
            'HealthPlus Wholesale',
            'Unilab Direct',
            'Metro Drug Distributors',
            'GG Central Warehouse',
        ];

        // Product images: the admin + storefront render `asset('storage/'.$product->image)`,
        // so `image` must be a path on the "public" disk (e.g. products/foo.png), not a
        // remote URL. Each source image is downloaded once into storage/app/public/products.
        // Source order for each image:
        //   1. a local file saved with a browser (Watsons blocks scripted downloads with
        //      HTTP 403 — see download_watsons_images.js). Searched, by original file
        //      name or Watsons product code, in database/seeders/data/images,
        //      storage/app/public/products, storage/app/public and public/products.
        //   2. a direct HTTP download (skipped for the rest of the run after a 403)
        // If neither works the image is left empty (the views already handle that).
        // Requires `php artisan storage:link` to have been run.
        $imageCache = [];

        $imageStats  = ['ok' => 0, 'failed' => 0];
        $httpBlocked = false;

        // Folders searched for the browser-saved images (non-recursive).
        $imageDirs = array_values(array_unique(array_filter([
            database_path('seeders/data/images'),
            storage_path('app/public/products'),
            storage_path('app/public'),
            public_path('storage/products'),
            public_path('products'),
            public_path('images/products'),
        ], 'is_dir')));

        $imageIndex = []; // lower-case file name => full path
        foreach ($imageDirs as $dir) {
            foreach (scandir($dir) ?: [] as $file) {
                $full = $dir . DIRECTORY_SEPARATOR . $file;

                if (is_file($full) && preg_match('/\.(png|jpe?g|webp|gif|avif)$/i', $file)) {
                    $imageIndex[strtolower($file)] ??= $full;
                }
            }
        }

        $this->command?->info('Local images found: ' . count($imageIndex)
            . ' (searched: ' . (implode(', ', $imageDirs) ?: 'no image folders exist') . ')');

        // Finds the local file for a Watsons image name: exact name, then ignoring the
        // browser's " (1)" duplicate suffix, then by the 8-digit Watsons product code
        // inside the name (survives renames).
        $findLocal = function (string $basename) use ($imageIndex): ?string {
            $key = strtolower($basename);

            if (isset($imageIndex[$key])) {
                return $imageIndex[$key];
            }

            $stripped = preg_replace('/ \(\d+\)(\.[a-z0-9]+)$/', '$1', $key);
            if (isset($imageIndex[$stripped])) {
                return $imageIndex[$stripped];
            }

            if (preg_match('/\d{8}/', $basename, $m)) {
                foreach ($imageIndex as $name => $path) {
                    if (str_contains($name, $m[0])) {
                        return $path;
                    }
                }
            }

            return null;
        };

        $storeImage = function (?string $url, string $name) use (&$imageCache, &$imageStats, &$httpBlocked, $findLocal): string {
            if (blank($url)) {
                return '';
            }

            if (isset($imageCache[$url])) {
                return $imageCache[$url];
            }

            $basename = rawurldecode(basename(parse_url($url, PHP_URL_PATH) ?? ''));
            $ext      = strtolower(pathinfo($basename, PATHINFO_EXTENSION)) ?: 'png';
            $target   = 'products/' . Str::slug($name) . '-' . substr(md5($url), 0, 6) . '.' . $ext;

            // 1) Local file saved from the browser.
            $local = $basename !== '' ? $findLocal($basename) : null;

            if ($local && is_file($local) && filesize($local) > 0) {
                $productsDir = realpath(storage_path('app/public/products'));

                if ($productsDir && realpath(dirname($local)) === $productsDir) {
                    // Already inside storage/app/public/products — use it where it is.
                    $target = 'products/' . basename($local);
                } else {
                    $target = 'products/' . Str::slug($name) . '-' . substr(md5($url), 0, 6) . '.'
                        . (strtolower(pathinfo($local, PATHINFO_EXTENSION)) ?: $ext);
                    Storage::disk('public')->put($target, file_get_contents($local));
                }

                $imageStats['ok']++;

                return $imageCache[$url] = $target;
            }

            // 2) Direct download (Watsons usually answers 403 to scripts).
            $reason = $httpBlocked ? 'HTTP 403 (skipped)' : 'unknown error';

            // 1st attempt verifies the SSL certificate. If that fails because the
            // machine has no CA bundle (common on Windows/XAMPP/Laragon: "cURL error 60"),
            // retry once without verification — these are public product images only.
            foreach (($httpBlocked ? [] : [true, false]) as $verify) {
                try {
                    $response = Http::timeout(30)
                        ->retry(2, 500, throw: false)
                        ->withOptions(['verify' => $verify])
                        ->withHeaders([
                            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0 Safari/537.36',
                            'Referer'    => 'https://www.watsons.com.ph/',
                            'Accept'     => 'image/avif,image/webp,image/png,image/*,*/*;q=0.8',
                        ])
                        ->get($url);

                    if ($response->successful() && $response->body() !== '') {
                        Storage::disk('public')->put($target, $response->body());
                        $imageStats['ok']++;

                        return $imageCache[$url] = $target;
                    }

                    // The server answered (e.g. 403/404): retrying without SSL won't help.
                    $reason = 'HTTP ' . $response->status();
                    $httpBlocked = in_array($response->status(), [401, 403], true);
                    break;
                } catch (\Throwable $e) {
                    $reason = Str::limit($e->getMessage(), 220);

                    $isSslProblem = $verify && (
                        str_contains($reason, 'cURL error 60')
                        || str_contains($reason, 'cURL error 77')
                        || stripos($reason, 'SSL certificate') !== false
                    );

                    if (! $isSslProblem) {
                        break;
                    }
                }
            }

            $this->command?->warn("No image for \"{$name}\" — {$reason}. Save \"{$basename}\" into storage/app/public/products or database/seeders/data/images");
            $imageStats['failed']++;

            return $imageCache[$url] = '';
        };

        foreach ($catalogue as $index => $row) {

            // code_name is generated automatically by Product::booted().
            $product = Product::create([
                'name'                  => $row['name'],
                'generic_name'          => $row['generic_name'] ?? null,
                'form'                  => $row['form'] ?? null,
                'price'                 => $row['price'],
                'old_price'             => $row['old_price'] ?? null,
                'image'                 => $storeImage($row['image'] ?? null, $row['name']),
                'badge'                 => $row['badge'] ?? null,
                'badge_type'            => $row['badge_type'] ?? null,
                'description'           => $row['description'] ?? null,
                'origin'                => $row['origin'] ?? null,
                'product_usage'         => $row['product_usage'] ?? null,
                'ingredients'           => $row['ingredients'] ?? null,
                'warnings'              => $row['warnings'] ?? null,
                'requires_prescription' => $row['requires_prescription'] ?? false,
                'width'                 => $row['width'] ?? null,
                'height'                => $row['height'] ?? null,
                'depth'                 => $row['depth'] ?? null,
                'category_id'           => ($categoryMap[$row['category']] ?? null)?->id,
                'brand_id'              => $resolveBrand($row['brand'] ?? null),
                'sort_order'            => $index + 1,
                'is_active'             => true,
            ]);

            // ── Stock: one ongoing active batch per product ──────────────────
            // Tablets/capsules are sold per piece, so they carry bigger quantities.
            $perPiece = $row['per_piece'] ?? (bool) preg_match('/tablet|capsule|softgel|sachet/i', (string) $product->form);
            $quantity = fake()->boolean(8)
                ? fake()->numberBetween(1, 15)                                   // a few low-stock items
                : ($perPiece ? fake()->numberBetween(300, 3000) : fake()->numberBetween(20, 150));

            $manufacturingDate = now()->subMonths(fake()->numberBetween(2, 14))->startOfMonth();
            $expiryDate        = (clone $manufacturingDate)->addYears(fake()->numberBetween(2, 3));
            $unitCost          = round($product->price * (1 - fake()->randomFloat(2, 0.25, 0.40)), 2);

            Stock::create([
                'product_id'         => $product->id,
                'supplier'           => $suppliers[$index % count($suppliers)],
                'quantity'           => $quantity,
                'unit_cost'          => $unitCost,
                'manufacturing_date' => $manufacturingDate,
                'expiry_date'        => $expiryDate,
                'is_active'          => true,
            ]);

            // Some products also have an older, fully sold-out (inactive) batch
            // so the stocks history/filters have something to show.
            if (fake()->boolean(20)) {
                $oldMfg = now()->subMonths(fake()->numberBetween(20, 30))->startOfMonth();

                Stock::create([
                    'product_id'         => $product->id,
                    'supplier'           => $suppliers[($index + 1) % count($suppliers)],
                    'quantity'           => 0,
                    'unit_cost'          => round($unitCost * fake()->randomFloat(2, 0.92, 1.0), 2),
                    'manufacturing_date' => $oldMfg,
                    'expiry_date'        => (clone $oldMfg)->addYears(2),
                    'is_active'          => false,
                ]);
            }
        }


        $this->command?->info("Product images: {$imageStats['ok']} downloaded, {$imageStats['failed']} failed.");

        // ── 6. Sliders ────────────────────────────────────────────────────────
        // Every slide is about a product (sliders.product_id is required), so this
        // runs after the products are seeded. 'match' picks the product by brand
        // and/or name; slides with no match (or no 'match') get the next product
        // that no other slide is using yet. Edit the matches to point at specific products.
        Slider::truncate();

        $sliderProducts = Product::with('brand:id,name')->orderBy('id')->get();
        $usedProductIds = [];

        $pickProduct = function (?array $match) use ($sliderProducts, &$usedProductIds): ?Product {
            if ($match) {
                $found = $sliderProducts->first(function (Product $p) use ($match, $usedProductIds) {
                    if (in_array($p->id, $usedProductIds, true)) {
                        return false;
                    }
                    if (isset($match['brand']) && mb_strtolower((string) $p->brand?->name) !== mb_strtolower($match['brand'])) {
                        return false;
                    }
                    if (isset($match['name']) && ! str_contains(mb_strtolower($p->name), mb_strtolower($match['name']))) {
                        return false;
                    }
                    return true;
                });

                if ($found) {
                    return $found;
                }
            }

            return $sliderProducts->first(fn (Product $p) => ! in_array($p->id, $usedProductIds, true))
                ?? $sliderProducts->first();
        };

        foreach ([
            ['image'=>'images/RiteMed.jpg',       'alt'=>'Pharmacy Products',   'sort_order'=>1, 'match'=>['brand'=>'Ritemed']],
            ['image'=>'images/DUROTUSS.jpg',       'alt'=>'Healthcare Services', 'sort_order'=>2, 'match'=>['name'=>'Durotuss']],
            ['image'=>'images/webshare-min.jpg',   'alt'=>'Wellness Products',   'sort_order'=>3],
            ['image'=>'images/312018592_2217127531788714_5400026205529255685_n.jpg','alt'=>'Medicine',      'sort_order'=>4],
            ['image'=>'images/75580347_3169877399707875_2163426682566868992_n.jpg', 'alt'=>'Health Banner', 'sort_order'=>5],
        ] as $row) {
            $product = $pickProduct($row['match'] ?? null);

            if (! $product) {
                $this->command?->warn("No products were seeded — skipping slider \"{$row['alt']}\" (every slider needs a product).");
                continue;
            }

            $usedProductIds[] = $product->id;
            unset($row['match']);
            Slider::create($row + ['product_id' => $product->id]);
        }

        // ── 7. Promo Banners ─────────────────────────────────────────────────
        PromoBanner::truncate();
        PromoBanner::create(['image'=>'https://via.placeholder.com/400x200/C01A1A/fff?text=GET+50+OFF',    'alt'=>'promo_1',        'type'=>'red',  'sort_order'=>1]);
        PromoBanner::create(['image'=>'https://via.placeholder.com/400x200/fff3e0/333?text=FREE+DELIVERY','alt'=>'promo_2','type'=>'beige','sort_order'=>2]);

        // ── 8. Full-Width Banners ────────────────────────────────────────────
        FullWidthBanner::truncate();
        FullWidthBanner::create(['sort_order'=>1, 'image'=>'https://via.placeholder.com/800x220/0d47a1/fff?text=OMRON+Banner',         'alt'=>'OMRON']);
        FullWidthBanner::create(['sort_order'=>2, 'image'=>'https://via.placeholder.com/800x220/f5f5f5/1a237e?text=RiteMED+Banner',    'alt'=>'RiteMED']);
        FullWidthBanner::create(['sort_order'=>3, 'image'=>'https://via.placeholder.com/800x200/e65100/ffd600?text=ALAXAN+XTRA+Banner','alt'=>'ALAXAN XTRA']);

        // ── 9. Staff ────────────────────────────────────────────────────────
        Staff::truncate();
        foreach ([
            [
                'first_name'     => 'Admin',
                'last_name'      => 'User',
                'email'          => 'admin@ggpharmacy.com.ph',
                'contact_number' => '09188887673',
                'address'        => 'Zone 2, Sogod, Southern Leyte, Philippines',
                'password'       => 'password',
                'is_active'      => true,
            ],
            [
                'first_name'     => 'Maria',
                'last_name'      => 'Santos',
                'email'          => 'maria.santos@ggpharmacy.com.ph',
                'contact_number' => '09171234567',
                'address'        => 'Zone 2, Sogod, Southern Leyte, Philippines',
                'password'       => 'password',
                'is_active'      => true,
            ],
            [
                'first_name'     => 'Juan',
                'last_name'      => 'Dela Cruz',
                'email'          => 'juan.delacruz@ggpharmacy.com.ph',
                'contact_number' => '09192345678',
                'address'        => 'Poblacion, Sogod, Southern Leyte, Philippines',
                'password'       => 'password',
                'is_active'      => true,
            ],
            [
                'first_name'     => 'Angelica',
                'last_name'      => 'Reyes',
                'email'          => 'angelica.reyes@ggpharmacy.com.ph',
                'contact_number' => '09203456789',
                'address'        => 'Zone 5, Sogod, Southern Leyte, Philippines',
                'password'       => 'password',
                'is_active'      => false,
            ],
        ] as $row) {
            Staff::create($row);
        }

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