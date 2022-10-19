<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDriverMetadataTableAddBatteryColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('driver_metadata', function (Blueprint $table) {
            $table->string('battery_level')->nullable();
            $table->jsonb('device')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('driver_metadata', function (Blueprint $table) {
            $table->dropColumn(['battery_level', 'device']);
        });
    }
}
