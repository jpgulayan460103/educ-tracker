<?php

namespace Database\Seeders;

use App\Models\Payout;
use Illuminate\Database\Seeder;

class PayoutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dates = [
            '2022-08-20',
            '2022-08-27',
            '2022-09-03',
            '2022-09-10',
            '2022-09-17',
            '2022-09-24',
        ];

        foreach ($dates as $date) {
            Payout::create([
                'payout_date' => $date,
                'is_active' => 1,
            ]);
        }
    }
}
