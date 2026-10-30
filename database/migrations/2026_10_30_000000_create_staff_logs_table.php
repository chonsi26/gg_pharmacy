<?php

use App\Models\Staff;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Activity log for the staff portal: one row per action a staff member performs.
     * Mirrors the admin `logs` table, keyed on staff instead of admin.
     */
    public function up(): void
    {
        Schema::create('staff_logs', function (Blueprint $table) {
            $table->id();

            // Who did it. The name is a snapshot so the entry still reads
            // correctly after the staff member is renamed or deleted.
            // (Table name is read from the Staff model, so it works whether
            // yours is `staffs` or `staff`.)
            $table->foreignId('staff_id')->nullable()
                ->constrained((new Staff)->getTable())->nullOnDelete();
            $table->string('staff_name')->nullable();

            // What happened.
            $table->string('module', 50);                 // e.g. Inventory, Orders, Stocks
            $table->string('action', 30);                 // created, updated, deleted, login, ...
            $table->text('description');                  // human-readable sentence (without the staff's name)

            // What it happened to (optional).
            $table->string('subject_type')->nullable();   // model class, e.g. App\Models\Product
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->string('subject_label')->nullable();  // e.g. "Paracetamol 500mg", "ORD-1001"

            // Request context.
            $table->string('route_name')->nullable();
            $table->string('method', 10)->nullable();
            $table->string('url', 500)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();

            // Sanitized request input + result message (never passwords/tokens).
            $table->json('properties')->nullable();

            $table->timestamps();

            $table->index('created_at');
            $table->index('module');
            $table->index('action');
            $table->index(['subject_type', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_logs');
    }
};
