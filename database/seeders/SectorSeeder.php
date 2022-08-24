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
            "Solo Parent",
            "Indigenous People",
            "Recovering Person who used Drugs",
            "4PS DSWD Beneficiary",
            "Street Dwellers",
            "Psychosocial/Mental/Learning Disability",
            "Stateless Persons/Asylum Seekers/Refugees",
            "Others",
        ];

        foreach ($sectors as $sector) {
            Sector::create([
                'name' => $sector
            ]);
        }
    }
}
