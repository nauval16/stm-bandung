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
    Schema::create('traffic_logs', function (Blueprint $table) {
        $table->id();
        // Relasi ke tabel cctv
        $table->foreignId('cctv_id')->constrained('cctvs')->onDelete('cascade');
        
        // Membedakan jalur Atas/Bawah (Normal / Opposite)
        $table->enum('arah', ['Normal', 'Opposite']);
        
        // Jumlah per klasifikasi kendaraan
        $table->integer('sepeda_motor')->default(0);
        $table->integer('mobil_penumpang')->default(0);
        $table->integer('kendaraan_sedang')->default(0);
        $table->integer('bus_besar')->default(0);
        $table->integer('truk_barang')->default(0);
        
        // created_at akan menjadi acuan waktu (jam/menit) otomatis
        $table->timestamps(); 
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('traffic_logs');
    }
};
