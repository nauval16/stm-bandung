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
    Schema::table('cctvs', function (Blueprint $table) {
        $table->string('latitude')->nullable()->after('link_akses');
        $table->string('longitude')->nullable()->after('latitude');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('cctvs', function (Blueprint $table) {
        $table->dropColumn(['latitude', 'longitude']);
    });
}
};
