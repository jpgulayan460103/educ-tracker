<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // User::factory(10)->create();
        $this->call(SwadOfficeSeeder::class);
        $this->call(PsgcSeeder::class);
        $this->call(OfficeSeeder::class);
        $this->call(SchoolLevelSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(SectorSeeder::class);
        $this->call(SectorOtherSeeder::class);
        $this->call(PayoutSeeder::class);
        $this->call(ClientSectorSeeder::class);
        $this->call(BeneficiaryCsvSeeder::class);
    }
}
