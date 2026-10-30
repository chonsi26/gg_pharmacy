<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Staff account status:
     *   pending    – self-registered, waiting for an admin to accept the request
     *   registered – accepted (or created by an admin); may log in
     *
     * Existing rows default to "registered" so current staff can keep logging in.
     */
    public function up(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->string('status', 20)->default('registered')->after('is_active');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropColumn('status');
        });
    }
};
