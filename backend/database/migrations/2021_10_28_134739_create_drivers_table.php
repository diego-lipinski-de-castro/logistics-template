<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->uuid('pub_id')->unique();

            $table->foreignId('city_id')->nullable();

            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();

            $table->string('full_name')->nullable();

            $table->string('email')->unique()->nullable();
            $table->string('phone')->unique();

            $table->string('birthday')->nullable();
            $table->string('cpf')->nullable();

            $table->string('cnh')->nullable();
            $table->string('vehicle_name')->nullable();
            $table->string('vehicle_plate')->nullable();

            $table->string('pix')->nullable();

            $table->boolean('registered')->default(false);

            $table->enum('status', ['PENDING', 'APPROVED', 'REJECTED'])->default('PENDING');
            
            $table->timestamps();
            $table->timestamp('banned_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('drivers');
    }
}
