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
            DB::table('users')->insert([
                'name' => fake()->name() . $i,
                'email' => fake()->unique()->safeEmail(),
                'address' => Str::random(10),
                'password' => Hash::make('password'),
                'contact_info' => Str::random(20),
                'membership_status' => 'pending',
                'membership_expiry_date' => date("Y-m-d H:i:s", rand(1262055681,1262055681)),
            ]);
        }
    }
}
