<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seat_types', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('event_id');
            $table->foreign('event_id')
                  ->references('id')
                  ->on('events')
                  ->onDelete('cascade');

            $table->string('name'); // VIP, Normal, Economy
            $table->decimal('price', 8, 2);
            $table->integer('capacity'); // available seats

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seat_types');
    }
};