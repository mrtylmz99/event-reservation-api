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
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->constrained('venues')->onDelete('cascade'); // Mekan ID
            $table->string('section')->nullable(); // Bölüm (A Blok vb.)
            $table->string('row')->nullable(); // Sıra
            $table->string('number'); // Koltuk numarası
            $table->decimal('price', 10, 2); // Koltuk ücreti
            $table->string('status')->default('available'); // Koltuk durumu (available, booked, maintenance)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
