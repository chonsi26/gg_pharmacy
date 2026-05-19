<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('excerpt');
            $table->string('image')->nullable();
            $table->string('icon_class')->nullable();   // FontAwesome class when no image
            $table->string('icon_color')->nullable();
            $table->string('icon_bg')->nullable();
            $table->integer('day');
            $table->string('month', 3);                 // SEP, OCT …
            $table->integer('comment_count')->default(0);
            // ── Relationship ───────────────────────────────────────────────
            $table->foreignId('category_id')
                  ->nullable()
                  ->constrained('categories')
                  ->nullOnDelete();
            // ──────────────────────────────────────────────────────────────
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('blogs'); }
};
