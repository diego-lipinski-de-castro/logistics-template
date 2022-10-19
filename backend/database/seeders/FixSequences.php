<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FixSequences extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all the tables from your database
        $tables = DB::select('SELECT table_name FROM information_schema.tables WHERE table_schema = \'public\' ORDER BY table_name;');

        // Set the tables in the database you would like to ignore
        $ignores = [
            'audits',
            'geography_columns',
            'geometry_columns',
            'spatial_ref_sys',
            'addresses',
            'driver_metadata',
            'documents',
            'job_batches',
            'password_resets',
            'sessions',
            'telescope_entries',
            'telescope_entries_tags',
            'telescope_monitoring',
        ];

        //loop through the tables
        foreach ($tables as $table) {
            try {
                // if the table is not to be ignored then:
                if (! in_array($table->table_name, $ignores)) {

                    //Get the max id from that table and add 1 to it
                    $seq = DB::table($table->table_name)->max('id') + 1;

                    // alter the sequence to now RESTART WITH the new sequence index from above
                    DB::select('ALTER SEQUENCE ' . $table->table_name . '_id_seq RESTART WITH ' . $seq);
                }
            } catch (\Exception $e) {
                Log::debug(get_class($this));
                Log::debug($table);
                Log::debug($e);
            }
        }
    }
}
