<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cctvs', function (Blueprint $table) {
            $table->timestamp('ai_last_seen_at')->nullable()->after('jumlah_kendaraan');
        });
    }

    public function down(): void
    {
        Schema::table('cctvs', function (Blueprint $table) {
            $table->dropColumn('ai_last_seen_at');
        });
    }
};
