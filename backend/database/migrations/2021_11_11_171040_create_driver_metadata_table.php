<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriverMetadataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_metadata', function (Blueprint $table) {
            $table->foreignId('driver_id')->constrained()->unique()->onDelete('cascade');

            $table->boolean('cloud')->default(true);

            $table->enum('status', ['ONLINE', 'OFFLINE', 'BUSY'])->default('OFFLINE');

            $table->point('location')->nullable();

            $table->enum('mode', ['BIKE', 'E_BIKE', 'MOTO', 'CAR', 'VAN', 'TRUCK'])->nullable();
            $table->enum('bag', ['BAG_45', 'BAG_55', 'BAG_89', 'BAG', 'CAR', 'OPEN_VAN', 'CLOSED_VAN', 'TRUCK'])->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('driver_metadata');
    }
}
