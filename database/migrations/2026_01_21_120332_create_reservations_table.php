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
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Kullanıcı ID
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade'); // Etkinlik ID
            $table->dateTime('expires_at'); // Rezervasyon son geçerlilik tarihi
            $table->string('status')->default('pending'); // Durum (pending, confirmed, cancelled, expired)
            $table->decimal('total_amount', 10, 2)->default(0); // Toplam tutar
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
