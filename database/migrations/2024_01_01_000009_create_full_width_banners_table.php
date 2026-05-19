<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('full_width_banners', function (Blueprint $table) {
            $table->id();
            $table->string('section_key')->unique();  // omron | ritemed | alaxan
            $table->string('image')->nullable();
            $table->string('alt')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('full_width_banners'); }
};
