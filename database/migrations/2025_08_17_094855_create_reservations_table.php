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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->decimal('total_price', 8, 2) -> default(0);
            $table->dateTime('reserved_date_time');
            $table->dateTime('session_duration')->nullable();
            
            $table->timestamps();
        });

        Schema::create('pricing_tiers', function (Blueprint $table){
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('tier');
            $table->decimal('price', 8, 2);

            $table->timestamps();

        });

        Schema::create('reservation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained()->onDelete('cascade');
            $table->foreignId('pricing_tier_id')->constrained();
            $table->integer('quantity');
            $table->decimal('unit_price', 8, 2);
            
            
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_items');
        Schema::dropIfExists('pricing_tiers');
        Schema::dropIfExists('reservations');
    }
};
