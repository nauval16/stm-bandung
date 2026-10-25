<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('traffic_logs', function (Blueprint $table) {
            $table->uuid('batch_id')->nullable()->after('cctv_id');
            $table->unique(['cctv_id', 'batch_id', 'arah']);
        });
    }

    public function down(): void
    {
        Schema::table('traffic_logs', function (Blueprint $table) {
            $table->dropUnique(['cctv_id', 'batch_id', 'arah']);
            $table->dropColumn('batch_id');
        });
    }
};
