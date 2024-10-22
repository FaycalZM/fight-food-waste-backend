<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User ;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class MerchantSeeder extends Seeder
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

            DB::table('users')->insert([
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'address' => Str::random(10),
                'password' => Hash::make('password'),
                'contact_info' => Str::random(20),
                'membership_status' => 'pending',
                'membership_expiry_date' => date("Y-m-d H:i:s", $randomTimestamp),
            ]);
        }
    }
}
