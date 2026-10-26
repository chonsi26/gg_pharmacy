<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Product type / dosage form, e.g. "Medicine", "Vitamins", "Tablet", "Syrup".
            $table->string('form', 100)->nullable()->after('generic_name');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('form');
        });
    }
};
