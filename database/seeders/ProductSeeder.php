<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Stock;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
    


class ProductSeeder extends Seeder
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

            $randomUser = User::inRandomOrder()->first();
            $randomStock = Stock::inRandomOrder()->first();

            DB::table('products')->insert([
                'product_name' => 'Product ' . $i,
                'barcode' => Str::random(20),
                'category' => Str::random(30),
                'expiration_date' => date('Y-m-d', $randomTimestamp),
                'user_id' => $randomUser->id,
                'stock_id' => $randomStock->id,
            ]);
        }
    }
}
