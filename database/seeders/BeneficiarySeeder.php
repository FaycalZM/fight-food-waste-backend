<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BeneficiarySeeder extends Seeder
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

            DB::table('beneficiaries')->insert([
                'name' => fake()->name(),
                'type' => 'Individual',
                'contact_info' => Str::random(10),
                'address' => Str::random(10),
                'individual_needs' => Str::random(20),
            ]);
        }
    }
}
