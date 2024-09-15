<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User ;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CollectionSeeder extends Seeder
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
            DB::table('collections')->insert([
                'user_ids' => $i + 1 . ',' . $i + 2 . ',' . $i + 3,
                'scheduled_time' => date('Y-m-d H', $randomTimestamp),
                'volunteers_count' => $i,
                'route' => Str::random(5) . "," . Str::random(5) . "," . Str::random(5),
                'collection_status' => "Scheduled",
            ]);
        }
    }
}
