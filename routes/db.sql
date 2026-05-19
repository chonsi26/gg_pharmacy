-- ============================================================
-- GG Pharmacy Database
-- MySQL 8.0 | Database: gg_pharmacy
-- ============================================================

CREATE DATABASE IF NOT EXISTS `gg_pharmacy`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `gg_pharmacy`;

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
SET NAMES utf8mb4;

-- ------------------------------------------------------------
-- Table: migrations
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` VALUES
  (1,  '2019_12_14_000001_create_personal_access_tokens_table', 1),
  (2,  '2024_01_01_000001_create_settings_table',               1),
  (3,  '2024_01_01_000001_create_users_table',                  1),
  (4,  '2024_01_01_000002_create_nav_items_table',              1),
  (5,  '2024_01_01_000003_create_categories_table',             1),
  (6,  '2024_01_01_000004_create_brands_table',                 1),
  (7,  '2024_01_01_000005_create_sections_table',               1),
  (8,  '2024_01_01_000006_create_products_table',               1),
  (9,  '2024_01_01_000007_create_sliders_table',                1),
  (10, '2024_01_01_000008_create_promo_banners_table',          1),
  (11, '2024_01_01_000009_create_full_width_banners_table',     1),
  (12, '2024_01_01_000010_create_blogs_table',                  1);

-- ------------------------------------------------------------
-- Table: settings
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id`         bigint unsigned NOT NULL AUTO_INCREMENT,
  `key`        varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value`      text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `settings` VALUES
  (1,  'site_name',         'GG Pharmacy',                                                                  NOW(), NOW()),
  (2,  'tagline',           'Search for Generic and Branded Medicine',                                      NOW(), NOW()),
  (3,  'phone',             '09188887673',                                                                   NOW(), NOW()),
  (4,  'phone_label',       'Call Us Now',                                                                   NOW(), NOW()),
  (5,  'email',             'onlinesale@ggpharmacy.com.ph',                                                  NOW(), NOW()),
  (6,  'address_line1',     'Zone 2, Sogod',                                                                 NOW(), NOW()),
  (7,  'address_line2',     'Southern Leyte',                                                                NOW(), NOW()),
  (8,  'address_line3',     'Philippines',                                                                   NOW(), NOW()),
  (9,  'working_hours',     'Mon - Sun / 8:00 AM - 9:00 PM',                                                NOW(), NOW()),
  (10, 'location_strip',    '? Zone 2, Sogod, Southern Leyte',                                              NOW(), NOW()),
  (11, 'facebook_url',      '#',                                                                             NOW(), NOW()),
  (12, 'instagram_url',     '#',                                                                             NOW(), NOW()),
  (13, 'logo',              'images/logo.png',                                                               NOW(), NOW()),
  (14, 'shipping_message',  'Free shipping for orders over ₱1499 (For Sogod, Southern Leyte Only)',         NOW(), NOW()),
  (15, 'copyright',         '© 2024 GG Pharmacy. All Rights Reserved. | Zone 2, Sogod, Southern Leyte',    NOW(), NOW()),
  (16, 'newsletter_intro',  'Get all the latest information on events, sales and offers. Sign up for newsletter:', NOW(), NOW()),
  (17, 'newsletter_note',   'By subscribing, you agree to receive our newsletter and accept our privacy policy.', NOW(), NOW()),
  (18, 'payment_methods',   'VISA,MC,GCash,PayMaya',                                                        NOW(), NOW()),
  (19, 'footer_top_text',   'Your Trusted Pharmacy',                                                        NOW(), NOW()),
  (20, 'chatbase_id',       'eDdta8JbEpQawG9yqfKqb',                                                        NOW(), NOW());

-- ------------------------------------------------------------
-- Table: users
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id`                bigint unsigned NOT NULL AUTO_INCREMENT,
  `first_name`        varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name`         varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email`             varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_number`    varchar(20)  COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address`           text         COLLATE utf8mb4_unicode_ci,
  `profile_picture`   varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password`          varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role`              enum('customer','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `is_active`         tinyint(1) NOT NULL DEFAULT '1',
  `remember_token`    varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at`        timestamp NULL DEFAULT NULL,
  `updated_at`        timestamp NULL DEFAULT NULL,
  `deleted_at`        timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_email_index` (`email`),
  KEY `users_role_index` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- (no seed data for users)

-- ------------------------------------------------------------
-- Table: personal_access_tokens
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (
  `id`             bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id`   bigint unsigned NOT NULL,
  `name`           varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token`          varchar(64)  COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities`      text         COLLATE utf8mb4_unicode_ci,
  `last_used_at`   timestamp NULL DEFAULT NULL,
  `expires_at`     timestamp NULL DEFAULT NULL,
  `created_at`     timestamp NULL DEFAULT NULL,
  `updated_at`     timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`, `tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table: nav_items
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `nav_items`;
CREATE TABLE `nav_items` (
  `id`         bigint unsigned NOT NULL AUTO_INCREMENT,
  `parent_id`  bigint unsigned DEFAULT NULL,
  `label`      varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `href`       varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#',
  `is_active`  tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nav_items_parent_id_foreign` (`parent_id`),
  CONSTRAINT `nav_items_parent_id_foreign`
    FOREIGN KEY (`parent_id`) REFERENCES `nav_items` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `nav_items` VALUES
  (1,  NULL, 'HOME',                   '/', 1, 1,  NOW(), NOW()),
  (2,  NULL, 'GUARDIAN',               '#', 0, 2,  NOW(), NOW()),
  (3,  NULL, 'GG PHARMACY GENERICS',   '#', 0, 3,  NOW(), NOW()),
  (4,  NULL, 'HEALTHCARE',             '#', 0, 4,  NOW(), NOW()),
  (5,  NULL, 'BEAUTY AND SELF CARE',   '#', 0, 5,  NOW(), NOW()),
  (6,  NULL, 'BABY AND FAMILY NEEDS',  '#', 0, 6,  NOW(), NOW()),
  (7,  NULL, 'SALE',                   '#', 0, 7,  NOW(), NOW()),
  (8,  2,    'Guardian Personal Care', '#', 0, 1,  NOW(), NOW()),
  (9,  2,    'Guardian Baby Products', '#', 0, 2,  NOW(), NOW()),
  (10, 2,    'Guardian Health Devices','#', 0, 3,  NOW(), NOW()),
  (11, 3,    'Pain Relief',            '#', 0, 1,  NOW(), NOW()),
  (12, 3,    'Vitamins',               '#', 0, 2,  NOW(), NOW()),
  (13, 3,    'Cardiovascular',         '#', 0, 3,  NOW(), NOW()),
  (14, 4,    'Medical Devices',        '#', 0, 1,  NOW(), NOW()),
  (15, 4,    'Health Supplements',     '#', 0, 2,  NOW(), NOW()),
  (16, 5,    'Skin Care',              '#', 0, 1,  NOW(), NOW()),
  (17, 5,    'Hair Care',              '#', 0, 2,  NOW(), NOW()),
  (18, 6,    'Baby Milk',              '#', 0, 1,  NOW(), NOW()),
  (19, 6,    'Diapers',                '#', 0, 2,  NOW(), NOW()),
  (20, 6,    'Baby Accessories',       '#', 0, 3,  NOW(), NOW());

-- ------------------------------------------------------------
-- Table: categories
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id`         bigint unsigned NOT NULL AUTO_INCREMENT,
  `name`       varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon_class` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon_color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bg_color`   varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active`  tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categories` VALUES
  (1,  'Healthcare',        'fas fa-pills',                    '#e65100', '#fff3e0', 1,  1, NOW(), NOW()),
  (2,  'Health Supplement', 'fas fa-capsules',                 '#2E7D32', '#e8f5e9', 2,  1, NOW(), NOW()),
  (3,  'Medical Device',    'fas fa-heartbeat',                '#1565c0', '#e3f2fd', 3,  1, NOW(), NOW()),
  (4,  'Milk',              'fas fa-baby-carriage',            '#880e4f', '#fce4ec', 4,  1, NOW(), NOW()),
  (5,  'Baby Care',         'fas fa-baby',                     '#e91e63', '#fce4ec', 5,  1, NOW(), NOW()),
  (6,  'Personal Care',     'fas fa-pump-soap',                '#6a1b9a', '#f3e5f5', 6,  1, NOW(), NOW()),
  (7,  'OTC',               'fas fa-prescription-bottle-alt',  '#f57f17', '#fff8e1', 7,  1, NOW(), NOW()),
  (8,  'Allergy',           'fas fa-allergies',                '#6a1b9a', '#f3e5f5', 8,  0, NOW(), NOW()),
  (9,  'Vitamin C',         'fas fa-capsules',                 '#f57f17', '#fff8e1', 9,  0, NOW(), NOW()),
  (10, 'Adult Pain Relief', 'fas fa-pills',                    '#c62828', '#ffebee', 10, 0, NOW(), NOW()),
  (11, 'Cardiovascular',    'fas fa-heartbeat',                '#b71c1c', '#ffebee', 11, 0, NOW(), NOW()),
  (12, 'Vitamin E',         'fas fa-capsules',                 '#4e342e', '#efebe9', 12, 0, NOW(), NOW()),
  (13, 'Cough',             'fas fa-lungs',                    '#0277bd', '#e1f5fe', 13, 0, NOW(), NOW()),
  (14, 'Multivitamin Adult','fas fa-capsules',                 '#558b2f', '#f1f8e9', 14, 0, NOW(), NOW());

-- ------------------------------------------------------------
-- Table: brands
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `brands`;
CREATE TABLE `brands` (
  `id`              bigint unsigned NOT NULL AUTO_INCREMENT,
  `name`            varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ticker_image`    varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `featured_image`  varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `featured_color`  varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `show_in_ticker`  tinyint(1) NOT NULL DEFAULT '1',
  `show_in_featured`tinyint(1) NOT NULL DEFAULT '0',
  `sort_order`      int NOT NULL DEFAULT '0',
  `is_active`       tinyint(1) NOT NULL DEFAULT '1',
  `created_at`      timestamp NULL DEFAULT NULL,
  `updated_at`      timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `brands` VALUES
  (1,  'Philcare',         'logos/philcare.png',         NULL,                          NULL,      1, 0,  1,  1, NOW(), NOW()),
  (2,  'Immuni+',          'logos/immuni-plus.png',      'logos/immuniplus_featured.png','darkred', 1, 1,  2,  1, NOW(), NOW()),
  (3,  'Efficasent',       'logos/efficasent.png',       NULL,                          NULL,      1, 0,  3,  1, NOW(), NOW()),
  (4,  'Neurobion',        'logos/neurobion.png',        'logos/neurobion_featured.png','orange',  1, 1,  4,  1, NOW(), NOW()),
  (5,  'IPI',              'logos/ipi.png',              'logos/ipi_featured.png',      'white',   1, 1,  5,  1, NOW(), NOW()),
  (6,  'Alaxan',           'logos/alaxan.webp',          'logos/alaxan-featured.jfif',  'white',   1, 1,  6,  1, NOW(), NOW()),
  (7,  'Enervon',          'logos/enervon.jpg',          NULL,                          NULL,      1, 0,  7,  1, NOW(), NOW()),
  (8,  'Ladys Choice',     'logos/ladyschoice.png',      NULL,                          NULL,      1, 0,  8,  1, NOW(), NOW()),
  (9,  'Organica',         'logos/organica.webp',        NULL,                          NULL,      1, 0,  9,  1, NOW(), NOW()),
  (10, 'Nestlé',           'logos/nestle.png',           NULL,                          NULL,      1, 0,  10, 1, NOW(), NOW()),
  (11, 'Ritemed',          'logos/ritemed.png',          'logos/ritemed_featured.jpg',  'white',   1, 1,  11, 1, NOW(), NOW()),
  (12, 'Unilab',           'logos/unilab.png',           'logos/unilab_featured.png',   'darkgreen',1,1, 12, 1, NOW(), NOW()),
  (13, 'Nescafe',          'logos/nescafe.png',          NULL,                          NULL,      1, 0,  13, 1, NOW(), NOW()),
  (14, 'Pedigree',         'logos/pedigree.png',         NULL,                          NULL,      1, 0,  14, 1, NOW(), NOW()),
  (15, 'Propan TLC',       'logos/Propan-TLC.jpg',       NULL,                          NULL,      1, 0,  15, 1, NOW(), NOW()),
  (16, 'Myra',             'logos/myra.webp',            NULL,                          NULL,      1, 0,  16, 1, NOW(), NOW()),
  (17, 'Safeguard',        'logos/safeguard.png',        NULL,                          NULL,      1, 0,  17, 1, NOW(), NOW()),
  (18, 'Head And Shoulders','logos/head&shoulders.png',  NULL,                          NULL,      1, 0,  18, 1, NOW(), NOW()),
  (19, 'Guardian',         NULL,                         NULL,                          NULL,      0, 0,  19, 1, NOW(), NOW()),
  (20, 'Abbott',           NULL,                         NULL,                          NULL,      0, 0,  20, 1, NOW(), NOW()),
  (21, 'Bayer',            NULL,                         NULL,                          NULL,      0, 0,  21, 1, NOW(), NOW());

-- ------------------------------------------------------------
-- Table: sections
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `sections`;
CREATE TABLE `sections` (
  `id`            bigint unsigned NOT NULL AUTO_INCREMENT,
  `key`           varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label`         varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description`   varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `see_all_url`   varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `heading_color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'red',
  `sort_order`    int NOT NULL DEFAULT '0',
  `is_active`     tinyint(1) NOT NULL DEFAULT '1',
  `created_at`    timestamp NULL DEFAULT NULL,
  `updated_at`    timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sections_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `sections` VALUES
  (1, 'hot_deals', 'Hot Deals of the Month',    'View Our Hot Deals Products of the Month',                  NULL, 'red',  1, 1, NOW(), NOW()),
  (2, 'sale',      'SALE',                       'Great Deals, Healthy Savings – Shop & Save at GG Pharmacy!', NULL, 'red',  2, 1, NOW(), NOW()),
  (3, 'promo_packs','PROMO PACKS',               'Exclusive Value Packs – Bundled Savings on Customer Favorites', NULL, 'text', 3, 1, NOW(), NOW()),
  (4, 'guardian',  'Guardian Special Deals',     'All our new arrivals in an exclusive brand selection',       NULL, 'red',  4, 1, NOW(), NOW()),
  (5, 'generics',  'GG Pharmacy Generics',       'All our new arrivals in a exclusive brand selection',        NULL, 'red',  5, 1, NOW(), NOW()),
  (6, 'featured',  'FEATURED PRODUCTS',          '',                                                           NULL, 'red',  6, 1, NOW(), NOW());

-- ------------------------------------------------------------
-- Table: products
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id`         bigint unsigned NOT NULL AUTO_INCREMENT,
  `name`       varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price`      decimal(10,2) NOT NULL,
  `old_price`  decimal(10,2) DEFAULT NULL,
  `image`      varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `badge`      varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `badge_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id`bigint unsigned DEFAULT NULL,
  `brand_id`   bigint unsigned DEFAULT NULL,
  `section_id` bigint unsigned NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active`  tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_category_id_foreign` (`category_id`),
  KEY `products_brand_id_foreign` (`brand_id`),
  KEY `products_section_id_foreign` (`section_id`),
  CONSTRAINT `products_brand_id_foreign`
    FOREIGN KEY (`brand_id`)    REFERENCES `brands` (`id`)     ON DELETE SET NULL,
  CONSTRAINT `products_category_id_foreign`
    FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `products_section_id_foreign`
    FOREIGN KEY (`section_id`)  REFERENCES `sections` (`id`)   ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `products` VALUES
  (1,  'Casino Alcohol 500Ml',               98.00,   NULL,   'https://via.placeholder.com/140x140/eee/999?text=Casino+Alcohol',  NULL,        NULL,         7,    NULL, 1, 1, 1, NOW(), NOW()),
  (2,  'KOOLFEVER GEL CHILDREN 2...',        52.00,   NULL,   'https://via.placeholder.com/140x140/eee/999?text=Koolfever',       NULL,        NULL,         1,    NULL, 1, 2, 1, NOW(), NOW()),
  (3,  'Dr.Wong S Sulfur Soap W/...',        67.50,   NULL,   'https://via.placeholder.com/140x140/eee/999?text=Sulfur+Soap',     NULL,        NULL,         6,    NULL, 1, 3, 1, NOW(), NOW()),
  (4,  'White Flower No. 3 5Ml',             96.75,   NULL,   'https://via.placeholder.com/140x140/eee/999?text=White+Flower',    NULL,        NULL,         7,    NULL, 1, 4, 1, NOW(), NOW()),
  (5,  'KOOLFEVER ADULT EXTRA C...',         55.75,   NULL,   'https://via.placeholder.com/140x140/eee/999?text=Koolfever+Extra', NULL,        NULL,         1,    NULL, 1, 5, 1, NOW(), NOW()),
  (6,  'Livity Prime',                       885.00,  NULL,   'https://via.placeholder.com/140x140/eee/999?text=Livity',          'MOST SOLD', 'most-sold',  NULL, NULL, 2, 1, 1, NOW(), NOW()),
  (7,  'Livity Prime 2',                     1850.00, NULL,   'https://via.placeholder.com/140x140/eee/999?text=Livity+2',        'MOST SOLD', 'most-sold',  NULL, NULL, 2, 2, 1, NOW(), NOW()),
  (8,  'Arthricin 9+1 Tipid Pack',           333.00,  NULL,   'https://via.placeholder.com/140x140/eee/999?text=Arthricin+9%2B1', 'MOST SOLD', 'most-sold',  7,    NULL, 2, 3, 1, NOW(), NOW()),
  (9,  'Liveraide 9+1 Tipid Pack',           189.00,  NULL,   'https://via.placeholder.com/140x140/eee/999?text=Liveraide+9%2B1', 'MOST SOLD', 'most-sold',  2,    NULL, 2, 4, 1, NOW(), NOW()),
  (10, 'Optein 9+1 Tipid Pack',             281.25,  NULL,   'https://via.placeholder.com/140x140/eee/999?text=Optein+9%2B1',   'MOST SOLD', 'most-sold',  2,    NULL, 2, 5, 1, NOW(), NOW()),
  (11, 'Dermplus Sunscreen',                445.50,  495.00, 'https://via.placeholder.com/140x140/eee/999?text=Dermplus',        '-10%',      'sale-badge', 6,    NULL, 2, 6, 1, NOW(), NOW()),
  (12, 'Claritin Tablet 10Mg A...',          152.00,  NULL,   'https://via.placeholder.com/140x140/eee/999?text=Claritin+4%2B1', NULL,        NULL,         8,    NULL, 3, 1, 1, NOW(), NOW()),
  (13, 'Rogin-E Soft Gel Caps...',           173.25,  NULL,   'https://via.placeholder.com/140x140/eee/999?text=Rogin-E',        NULL,        NULL,         14,   NULL, 3, 2, 1, NOW(), NOW()),
  (14, 'Sinecod Forte (7+1) Ti...',          155.00,  NULL,   'https://via.placeholder.com/140x140/eee/999?text=Sinecod+7%2B1',  NULL,        NULL,         13,   NULL, 3, 3, 1, NOW(), NOW()),
  (15, 'Tempra Forte 500mg ...',             35.75,   NULL,   'https://via.placeholder.com/140x140/eee/999?text=Tempra+Forte',   NULL,        NULL,         10,   NULL, 3, 4, 1, NOW(), NOW()),
  (16, 'Flanax 275 mg Tablet ...',           105.75,  NULL,   'https://via.placeholder.com/140x140/eee/999?text=Flanax+275',     NULL,        NULL,         10,   NULL, 3, 5, 1, NOW(), NOW()),
  (17, 'Rogin-E Softgel Caps...',            280.00,  NULL,   'https://via.placeholder.com/140x140/eee/999?text=Rogin-E+12',     NULL,        NULL,         14,   NULL, 3, 6, 1, NOW(), NOW()),
  (18, 'Guardian Comfort & A...',            449.00,  NULL,   'https://via.placeholder.com/120x120/eee/999?text=Comfort+Pads',   NULL,        NULL,         NULL, 19,   4, 1, 1, NOW(), NOW()),
  (19, 'Guardian Box Tissue 1...',           69.00,   NULL,   'https://via.placeholder.com/120x120/eee/999?text=Box+Tissue',     NULL,        NULL,         NULL, 19,   4, 2, 1, NOW(), NOW()),
  (20, 'Guardian Antibacteri...',            199.00,  NULL,   'https://via.placeholder.com/120x120/eee/999?text=Antibacterial',  NULL,        NULL,         NULL, 19,   4, 3, 1, NOW(), NOW()),
  (21, 'Guardian Kids Strawb...',            189.00,  NULL,   'https://via.placeholder.com/120x120/eee/999?text=Kids+Strawb',    NULL,        NULL,         5,    19,   4, 4, 1, NOW(), NOW()),
  (22, 'Cetirizine 10mg Tablet...',          115.00,  NULL,   'https://via.placeholder.com/120x120/eee/999?text=Cetirizine',     NULL,        NULL,         8,    11,   5, 1, 1, NOW(), NOW()),
  (23, 'Paracetamol 500 mg ...',             27.50,   NULL,   'https://via.placeholder.com/120x120/eee/999?text=Paracetamol+500',NULL,        NULL,         10,   11,   5, 2, 1, NOW(), NOW()),
  (24, 'Clopidogrel 75Mg Tab...',            18.50,   NULL,   'https://via.placeholder.com/120x120/eee/999?text=Clopidogrel',    NULL,        NULL,         11,   11,   5, 3, 1, NOW(), NOW()),
  (25, 'D-Alpha Vitamin E 40...',            90.00,   NULL,   'https://via.placeholder.com/120x120/eee/999?text=Vitamin+E',      NULL,        NULL,         12,   11,   5, 4, 1, NOW(), NOW()),
  (26, 'Losartan 50Mg Tablet ...',           11.75,   NULL,   'https://via.placeholder.com/110x110/eee/999?text=Losartan',       NULL,        NULL,         11,   11,   5, 5, 1, NOW(), NOW()),
  (27, 'Amlodipine 10Mg Tab...',             8.25,    NULL,   'https://via.placeholder.com/110x110/eee/999?text=Amlodipine+10',  NULL,        NULL,         11,   11,   5, 6, 1, NOW(), NOW()),
  (28, 'Simvastatin 20Mg Ta...',             10.50,   NULL,   'https://via.placeholder.com/110x110/eee/999?text=Simvastatin',    NULL,        NULL,         11,   11,   5, 7, 1, NOW(), NOW()),
  (29, 'Amlodipine 5Mg Tabl...',             6.00,    NULL,   'https://via.placeholder.com/110x110/eee/999?text=Amlodipine+5',   NULL,        NULL,         11,   11,   5, 8, 1, NOW(), NOW()),
  (30, 'Guardian Face & Bod...',             299.00,  NULL,   'https://via.placeholder.com/140x140/eee/999?text=Guardian+SPF50', 'MOST SOLD', 'most-sold',  NULL, 19,   6, 1, 1, NOW(), NOW()),
  (31, 'Guardian Kids Strawb...',            189.00,  NULL,   'https://via.placeholder.com/140x140/eee/999?text=Kids+Strawb',    'MOST SOLD', 'most-sold',  5,    19,   6, 2, 1, NOW(), NOW()),
  (32, 'ABBOTT | Ensure Gold ...',           3782.00, NULL,   'https://via.placeholder.com/140x140/eee/999?text=Ensure+Gold',    'MOST SOLD', 'most-sold',  4,    20,   6, 3, 1, NOW(), NOW()),
  (33, 'Berocca Performance...',             651.00,  NULL,   'https://via.placeholder.com/140x140/eee/999?text=Berocca+Perf',   'MOST SOLD', 'most-sold',  2,    21,   6, 4, 1, NOW(), NOW()),
  (34, 'Fern-C 568.18Mg Cap...',             581.75,  NULL,   'https://via.placeholder.com/140x140/eee/999?text=Fern-C+Caps',    'MOST SOLD', 'most-sold',  9,    NULL, 6, 5, 1, NOW(), NOW()),
  (35, 'Centrum Silver Advan...',            378.25,  NULL,   'https://via.placeholder.com/140x140/eee/999?text=Centrum+Silver', 'MOST SOLD', 'most-sold',  14,   NULL, 6, 6, 1, NOW(), NOW());

-- ------------------------------------------------------------
-- Table: sliders
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `sliders`;
CREATE TABLE `sliders` (
  `id`         bigint unsigned NOT NULL AUTO_INCREMENT,
  `image`      varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alt`        varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active`  tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `sliders` VALUES
  (1, 'images/RiteMed.jpg',                                          'Pharmacy Products',  1, 1, NOW(), NOW()),
  (2, 'images/DUROTUSS.jpg',                                         'Healthcare Services',2, 1, NOW(), NOW()),
  (3, 'images/webshare-min.jpg',                                     'Wellness Products',  3, 1, NOW(), NOW()),
  (4, 'images/312018592_2217127531788714_5400026205529255685_n.jpg', 'Medicine',           4, 1, NOW(), NOW()),
  (5, 'images/75580347_3169877399707875_2163426682566868992_n.jpg',  'Health Banner',      5, 1, NOW(), NOW());

-- ------------------------------------------------------------
-- Table: promo_banners
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `promo_banners`;
CREATE TABLE `promo_banners` (
  `id`         bigint unsigned NOT NULL AUTO_INCREMENT,
  `image`      varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alt`        varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type`       varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'red',
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active`  tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `promo_banners` VALUES
  (1, 'https://via.placeholder.com/400x200/C01A1A/fff?text=GET+50+OFF', 'Promo',         'red',   1, 1, NOW(), NOW()),
  (2, 'https://via.placeholder.com/400x200/fff3e0/333?text=FREE+DELIVERY','Free Delivery','beige', 2, 1, NOW(), NOW());

-- ------------------------------------------------------------
-- Table: full_width_banners
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `full_width_banners`;
CREATE TABLE `full_width_banners` (
  `id`          bigint unsigned NOT NULL AUTO_INCREMENT,
  `section_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image`       varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alt`         varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active`   tinyint(1) NOT NULL DEFAULT '1',
  `created_at`  timestamp NULL DEFAULT NULL,
  `updated_at`  timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `full_width_banners_section_key_unique` (`section_key`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `full_width_banners` VALUES
  (1, 'omron',   'https://via.placeholder.com/800x220/0d47a1/fff?text=OMRON+Banner',        'OMRON',      1, NOW(), NOW()),
  (2, 'ritemed', 'https://via.placeholder.com/800x220/f5f5f5/1a237e?text=RiteMED+Banner',   'RiteMED',    1, NOW(), NOW()),
  (3, 'alaxan',  'https://via.placeholder.com/800x200/e65100/ffd600?text=ALAXAN+XTRA+Banner','ALAXAN XTRA',1, NOW(), NOW());

-- ------------------------------------------------------------
-- Table: blogs
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `blogs`;
CREATE TABLE `blogs` (
  `id`            bigint unsigned NOT NULL AUTO_INCREMENT,
  `title`         varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt`       text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image`         varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon_class`    varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon_color`    varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon_bg`       varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `day`           int NOT NULL,
  `month`         varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment_count` int NOT NULL DEFAULT '0',
  `category_id`   bigint unsigned DEFAULT NULL,
  `is_active`     tinyint(1) NOT NULL DEFAULT '1',
  `created_at`    timestamp NULL DEFAULT NULL,
  `updated_at`    timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `blogs_category_id_foreign` (`category_id`),
  CONSTRAINT `blogs_category_id_foreign`
    FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `blogs` VALUES
  (1, 'New Branch Opening Sogod City ...',
      'GG Pharmacy is pleased to announce the opening of its newest branch located at Zone 2, Sogod, Southern Leyte...',
      NULL, NULL, NULL, NULL, 7, 'SEP', 0, NULL, 1, NOW(), NOW()),
  (2, 'GG Pharmacy Community Health Day',
      'GG Pharmacy is pleased to announce a free health check-up event for residents of Sogod and surrounding areas...',
      NULL, NULL, NULL, NULL, 7, 'SEP', 0, NULL, 1, NOW(), NOW()),
  (3, 'Sprains and strains',
      'What is the difference between sprains and strains? Sprains and muscle strains are common injuries which share similar signs...',
      NULL, 'fas fa-running', '#2E7D32', '#e8f5e9', 19, 'OCT', 0, 1, 1, NOW(), NOW()),
  (4, 'All You Need To Know About ...',
      '"Eeew, go away, I\'m allergic to you!" – does this sound familiar? A term often used when teasing...',
      NULL, 'fas fa-allergies', '#6a1b9a', '#f3e5f5', 19, 'OCT', 0, 8, 1, NOW(), NOW());

-- ------------------------------------------------------------
SET FOREIGN_KEY_CHECKS = 1;
-- Done! All tables created and seeded for gg_pharmacy.
-- ------------------------------------------------------------