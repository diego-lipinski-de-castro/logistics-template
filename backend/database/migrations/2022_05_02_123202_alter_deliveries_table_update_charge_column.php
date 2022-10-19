<?php

use App\Models\Delivery;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE deliveries DROP CONSTRAINT deliveries_charge_style_check");

        $types = ['LINE', 'ROUTE', 'DAILY', 'PACKAGE', 'OPEN'];
        
        $result = join(', ', array_map(function ($value) {
            return sprintf("'%s'::character varying", $value);
        }, $types));

        DB::statement("ALTER TABLE deliveries ADD CONSTRAINT deliveries_charge_style_check CHECK (charge_style::text = ANY (ARRAY[$result]::text[]))");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Delivery::query()
            ->where('charge_style', 'DAILY')
            ->orWhere('charge_style', 'PACKAGE')
            ->orWhere('charge_style', 'OPEN')
            ->update([
                'charge_style' => 'LINE',
            ]);

        DB::statement("ALTER TABLE deliveries DROP CONSTRAINT deliveries_charge_style_check");

        $types = ['LINE', 'ROUTE'];
        
        $result = join(', ', array_map(function ($value) {
            return sprintf("'%s'::character varying", $value);
        }, $types));

        DB::statement("ALTER TABLE deliveries ADD CONSTRAINT deliveries_charge_style_check CHECK (charge_style::text = ANY (ARRAY[$result]::text[]))");
    }
};
