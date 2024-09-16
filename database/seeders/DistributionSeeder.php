<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class DistributionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i=0; $i<=19; $i++) {

            $startDate = strtotime('2023-01-01');  
            $endDate = strtotime('2024-12-31');    
            $randomTimestamp = rand($startDate, $endDate);

            DB::table('distributions')->insert([
                'scheduled_time' => date('Y-m-d H', $randomTimestamp),
                'route' => Str::random(5) . "," . Str::random(5) . "," . Str::random(5),
                'distribution_status' => "Scheduled",
                'volunteers_count' => $i,
            ]);
        }
    }
}
