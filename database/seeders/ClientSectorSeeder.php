<?php

namespace Database\Seeders;

use App\Models\ClientSector;
use Illuminate\Database\Seeder;

class ClientSectorSeeder extends Seeder
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
            ClientSector::create([
                'name' => $sector
            ]);
        }
    }
}
