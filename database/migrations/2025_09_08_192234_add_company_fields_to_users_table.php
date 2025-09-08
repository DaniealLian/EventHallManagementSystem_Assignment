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
        Schema::table('users', function (Blueprint $table) {
            // Add company-related fields for manager applications
            $table->string('company_name')->nullable()->after('manager_status');
            $table->string('company_email')->nullable()->after('company_name');
            $table->text('company_address')->nullable()->after('company_email');
            $table->text('experience')->nullable()->after('company_address');
            $table->timestamp('manager_applied_at')->nullable()->after('experience');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'company_name',
                'company_email',
                'company_address',
                'experience',
                'manager_applied_at'
            ]);
        });
    }
};
