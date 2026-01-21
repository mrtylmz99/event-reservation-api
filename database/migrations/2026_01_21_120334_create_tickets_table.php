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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained('reservations')->onDelete('cascade'); // Rezervasyon ID
            $table->foreignId('seat_id')->constrained('seats')->onDelete('cascade'); // Koltuk ID
            $table->string('ticket_code')->unique(); // Benzersiz bilet kodu
            $table->string('status')->default('valid'); // Bilet durumu (valid, used, cancelled)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
