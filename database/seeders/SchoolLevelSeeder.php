<?php

namespace Database\Seeders;

use App\Models\SchoolLevel;
use Illuminate\Database\Seeder;

class SchoolLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SchoolLevel::create([
            'name' => 'Elementary',
            'amount' => 1000,            
        ]);
        SchoolLevel::create([
            'name' => 'High School',
            'amount' => 2000,            
        ]);
        SchoolLevel::create([
            'name' => 'Senior High School',
            'amount' => 3000,            
        ]);
        SchoolLevel::create([
            'name' => 'College',
            'amount' => 4000,            
        ]);
        SchoolLevel::create([
            'name' => 'Vocational',
            'amount' => 4000,            
        ]);
    }
}
