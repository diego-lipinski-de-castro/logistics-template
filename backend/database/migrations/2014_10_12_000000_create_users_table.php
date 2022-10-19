<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->uuid('pub_id')->unique();

            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            // $table->foreignId('current_team_id')->nullable();
            // $table->string('profile_photo_path', 2048)->nullable();

            $table->string('phone')->nullable();
            $table->text('info')->nullable();

            $table->foreignId('city_id');

            $table->polygon('area')->nullable();
            $table->jsonb('radiuses')->nullable();

            $table->boolean('receipt_required')->default(false);
            $table->enum('delivery_constraint', ['OPEN', 'PARTIAL', 'BLOCK'])->default('OPEN');
            $table->integer('return_fee')->default(0);

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
        Schema::dropIfExists('users');
    }
}
