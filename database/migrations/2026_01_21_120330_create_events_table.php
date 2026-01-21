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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->constrained('venues')->onDelete('cascade'); // Mekan ID foreign key
            $table->string('name'); // Etkinlik adı
            $table->text('description')->nullable(); // Etkinlik açıklaması
            $table->dateTime('start_date'); // Başlangıç zamanı
            $table->dateTime('end_date'); // Bitiş zamanı
            $table->string('status')->default('active'); // Etkinlik durumu (active, cancelled, completed)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
