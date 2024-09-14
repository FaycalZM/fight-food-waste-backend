<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i=0; $i<=19; $i++) {
            DB::table('skills')->insert([
                'name' => 'Skill ' . $i,
                'description' => Str::random(10),
                'conditions' => Str::random(30),
            ]);
        }
    }
}
