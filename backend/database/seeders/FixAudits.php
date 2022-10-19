<?php

namespace Database\Seeders;

use App\Models\Audit;
use Illuminate\Database\Seeder;

class FixAudits extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $audits = Audit::all();

        $audits->each(function ($audit) {
            if (! is_array($audit->old_values)) {
                $audit->update([
                    'old_values' => json_decode($audit->old_values),
                ]);
            }

            if (! is_array($audit->new_values)) {
                $audit->update([
                    'new_values' => json_decode($audit->new_values),
                ]);
            }
        });
    }
}
