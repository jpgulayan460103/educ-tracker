<?php

namespace Database\Seeders;

use App\Models\SwadOffice;
use Illuminate\Database\Seeder;

class SwadOfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $swad_offices = [
            'Province of Davao de Oro',
            'Province of Davao del Norte',
            'Province of Davao del Sur',
            'Province of Davao Occidental',
            'Province of Davao Oriental',
            'Regional Office',
        ];

        foreach ($swad_offices as $swad_office) {
            SwadOffice::create([
                'name' => $swad_office
            ]);
        }
    }
}
