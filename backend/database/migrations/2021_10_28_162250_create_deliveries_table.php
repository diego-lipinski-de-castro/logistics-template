<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->uuid('pub_id')->unique();
            $table->string('cid')->unique();
            
            $table->enum('status', ['WAITING', 'PENDING', 'ACCEPTED', 'COLLECTING', 'DELIVERING', 'CONFIRMED', 'COMPLETED', 'RETURNING', 'CANCELED', 'NOT_DELIVERED'])->default('PENDING');
            
            $table->foreignId('user_id')->nullable()->index();
            $table->foreignId('driver_id')->nullable()->index();
        
            $table->integer('rad');
            $table->integer('time');
            $table->integer('paid');
            $table->integer('charged');
            
            $table->integer('return_fee')->nullable();

            $table->boolean('receipt_required')->default(false);
            
            $table->text('private_text')->nullable();
            $table->text('public_text')->nullable();

            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            
            $table->timestamps();
            $table->timestamp('pending_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->time('elapsed_time')->nullable();

            $table->boolean('visible')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deliveries');
    }
}
