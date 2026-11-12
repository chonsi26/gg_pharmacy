<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Existing databases: drop the foreign key + column first, then the
        // table it pointed at. Fresh installs no longer create either one
        // (the create_sections migration was removed and the create_products
        // migration has no section_id), so every step is guarded.
        if (Schema::hasColumn('products', 'section_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropForeign(['section_id']);
                $table->dropColumn('section_id');
            });
        }

        Schema::dropIfExists('sections');
    }

    public function down(): void
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('label');
            $table->string('description')->nullable();
            $table->string('see_all_url')->nullable();
            $table->string('heading_color')->default('red');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Nullable on rollback: the original section assignments are gone.
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('section_id')
                  ->nullable()
                  ->after('brand_id')
                  ->constrained('sections')
                  ->cascadeOnDelete();
        });
    }
};
