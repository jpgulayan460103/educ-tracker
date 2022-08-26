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
            [
                'name' => 'Province of Davao de Oro',
                'code' => 'DDO',
            ],
            [
                'name' => 'Province of Davao del Norte',
                'code' => 'DDN',
            ],
            [
                'name' => 'Province of Davao del Sur',
                'code' => 'DDS',
            ],
            [
                'name' => 'Province of Davao Occidental',
                'code' => 'DOc',
            ],
            [
                'name' => 'Province of Davao Oriental',
                'code' => 'DOr',
            ],
            [
                'name' => 'Regional Office',
                'code' => 'RO',
            ],
        ];

        foreach ($swad_offices as $swad_office) {
            SwadOffice::create($swad_office);
        }
    }
}
