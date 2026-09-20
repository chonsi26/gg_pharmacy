<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();

            $table->string('report_title');

            // Free-form category/label for the report, e.g. "Sales", "Inventory",
            // "Incident", "Audit" — kept as a plain string for flexibility.
            $table->string('report_type');

            $table->date('report_date');

            // pending    → report created, not yet finalized
            // completed  → report has been completed
            // cancelled  → report was cancelled
            $table->enum('status', [
                'pending',
                'completed',
                'cancelled',
            ])->default('pending');

            $table->text('description')->nullable();
            $table->text('remarks')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('report_type');
            $table->index('report_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};