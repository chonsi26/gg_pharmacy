<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Google Maps embed URL of the pharmacy (the `src` of the iframe from
        // Google Maps > Share > Embed a map). Stored as a normal key/value row
        // like every other setting, seeded empty so it exists on fresh installs.
        DB::table('settings')->insert([
            'key'        => 'map_link',
            'value'      => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void { Schema::dropIfExists('settings'); }
};