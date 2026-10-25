<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('cctvs', function (Blueprint $table) {
        $table->id();
        // Menghubungkan CCTV dengan jalan tertentu (Relasi)
        $table->foreignId('jalan_id')->constrained('jalans')->onDelete('cascade');
        $table->string('gambar')->nullable(); 
        $table->string('link_akses')->nullable(); 
        
        // Kolom Data AI (Kepadatan Lalu Lintas)
        $table->string('kondisi_terkini')->default('Aman');
        $table->integer('jumlah_kendaraan')->default(0);
        
        // Kolom Data IoT (Sensor Genangan/Banjir)
        $table->string('status_banjir')->default('Aman');
        $table->integer('ketinggian_air_cm')->default(0);
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cctvs');
    }
};
