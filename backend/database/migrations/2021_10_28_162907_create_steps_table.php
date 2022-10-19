<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('steps', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('delivery_id')->index();

            $table->point('location')->nullable();
            
            $table->string('street_number')->nullable();
            $table->string('street_name')->nullable();
            $table->string('district')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();

            $table->string('info')->nullable();
            
            $table->unsignedBigInteger('prev_id')->nullable();
            $table->unsignedBigInteger('next_id')->nullable();

            $table->enum('status', ['PENDING', 'COMPLETED', 'CURRENT'])->default('PENDING');

            $table->foreign('prev_id')->references('id')->on('steps');
            $table->foreign('next_id')->references('id')->on('steps');

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
        Schema::dropIfExists('steps');
    }
}
