<?php

namespace Database\Seeders;

use App\Models\Sector;
use Illuminate\Database\Seeder;

class SectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sectors = [
            "Working Student",
            "Solo Parent",
            "Distressed OFW",
            "HIV",
            "Orphan or Abandoned",
            "Unemployed parents",
            "VAWC Victim",
            "Calamity Victim",
            "Others",
        ];

        foreach ($sectors as $sector) {
            Sector::create([
                'name' => $sector
            ]);
        }
    }
}
