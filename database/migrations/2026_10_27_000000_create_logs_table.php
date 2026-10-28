<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Activity log for the admin portal: one row per action an admin performs.
     */
    public function up(): void
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();

            // Who did it. The name is a snapshot so the entry still reads
            // correctly after the admin is renamed or deleted.
            $table->foreignId('admin_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->string('admin_name')->nullable();

            // What happened.
            $table->string('module', 50);                 // e.g. Inventory, Orders, Staffs
            $table->string('action', 30);                 // created, updated, deleted, login, ...
            $table->text('description');                  // human-readable sentence (without the admin's name)

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
        Schema::dropIfExists('logs');
    }
};
