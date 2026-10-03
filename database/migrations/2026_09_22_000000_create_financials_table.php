<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('financials', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->enum('type', ['Income', 'Expense']);
            $table->string('category');
            $table->string('description');
            $table->decimal('amount', 12, 2);
            $table->timestamps();

            // Helpful for the filters on the page (type, category, date).
            $table->index('date');
            $table->index('type');
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financials');
    }
};
