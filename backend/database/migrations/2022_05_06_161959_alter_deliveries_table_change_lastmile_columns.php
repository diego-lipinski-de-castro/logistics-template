<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->integer('rad')->nullable()->change();
            $table->integer('time')->nullable()->change();
            $table->integer('paid')->nullable()->change();
            $table->integer('charged')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->integer('rad')->nullable(false)->change();
            $table->integer('time')->nullable(false)->change();
            $table->integer('paid')->nullable(false)->change();
            $table->integer('charged')->nullable(false)->change();
        });
    }
};
