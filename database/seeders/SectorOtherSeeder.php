<?php

namespace Database\Seeders;

use App\Models\SectorOther;
use Illuminate\Database\Seeder;

class SectorOtherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sector_others = [
            'FHONA',
            'WEDC',
            'YOUTH',
            'PWD',
            'SC',
            'PLHIV'
        ];

        foreach ($sector_others as $sector_other) {
            SectorOther::create([
                'name' => $sector_other
            ]);
        }
    }
}
