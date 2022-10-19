<?php

use App\Models\User;
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
        DB::statement("ALTER TABLE users DROP CONSTRAINT users_charge_style_check");

        $types = ['LINE', 'ROUTE', 'DAILY', 'PACKAGE', 'OPEN'];
        
        $result = join(', ', array_map(function ($value) {
            return sprintf("'%s'::character varying", $value);
        }, $types));

        DB::statement("ALTER TABLE users ADD CONSTRAINT users_charge_style_check CHECK (charge_style::text = ANY (ARRAY[$result]::text[]))");

        Schema::table('users', function (Blueprint $table) {
            $table->jsonb('charge_options')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('charge_options');
        });

        User::query()
            ->where('charge_style', 'DAILY')
            ->orWhere('charge_style', 'PACKAGE')
            ->orWhere('charge_style', 'OPEN')
            ->update([
                'charge_style' => 'LINE',
            ]);

        DB::statement("ALTER TABLE users DROP CONSTRAINT users_charge_style_check");

        $types = ['LINE', 'ROUTE'];
        
        $result = join(', ', array_map(function ($value) {
            return sprintf("'%s'::character varying", $value);
        }, $types));

        DB::statement("ALTER TABLE users ADD CONSTRAINT users_charge_style_check CHECK (charge_style::text = ANY (ARRAY[$result]::text[]))");
    }
};
