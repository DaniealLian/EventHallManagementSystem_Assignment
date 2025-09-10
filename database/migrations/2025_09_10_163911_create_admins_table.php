<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone_number')->nullable();
            $table->boolean('is_super_admin')->default(false);
            $table->boolean('is_active')->default(true);
            $table->json('permissions')->nullable(); // Store permissions as JSON array
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->index('is_super_admin');
            $table->index('is_active');
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
