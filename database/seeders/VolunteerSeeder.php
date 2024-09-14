<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Skill;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class VolunteerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i=0; $i<=19; $i++) {
            $randomSkill = Skill::inRandomOrder()->first();
            DB::table('volunteers')->insert([
                'name' => fake()->name() . $i,
                'email' => fake()->unique()->safeEmail(),
                'address' => Str::random(10),
                'password' => Hash::make('password'),
                'skill_id' => $randomSkill->id,
                'contact_info' => Str::random(20),
                'availability_start' => date('Y-m-d'),
                'availability_end' => date('Y-m-d'),
            ]);
        }
    }
}
