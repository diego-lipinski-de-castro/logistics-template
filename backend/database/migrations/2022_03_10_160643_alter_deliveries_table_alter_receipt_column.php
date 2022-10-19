<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDeliveriesTableAlterReceiptColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->dropColumn('receipt_required');
            $table->enum('receipt_confirmation', ['DISABLED', 'OPTIONAL', 'REQUIRED_PARTIAL', 'REQUIRED'])->default('DISABLED');
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
            $table->dropColumn('receipt_confirmation');
            $table->boolean('receipt_required')->default(false);
        });
    }
}
