<?php

use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Fresh installs already get this column from the create_products_table
        // migration, so only add it when it's missing (existing databases).
        if (! Schema::hasColumn('products', 'code_name')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('code_name', 100)->nullable()->unique()->after('name');
            });
        }

        // Backfill every existing product. Saving triggers Product::booted(),
        // which generates the code name from the product's own information.
        Product::whereNull('code_name')->orderBy('id')->chunkById(200, function ($products) {
            foreach ($products as $product) {
                $product->save();
            }
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('products', 'code_name')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropUnique(['code_name']);
                $table->dropColumn('code_name');
            });
        }
    }
};
