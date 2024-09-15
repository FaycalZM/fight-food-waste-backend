<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(MerchantSeeder::class);
        $this->call(SkillSeeder::class);
        $this->call(VolunteerSeeder::class);
        $this->call(CollectionSeeder::class);
        $this->call(StockSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(BeneficiarySeeder::class);
        $this->call(DistributionSeeder::class);
    }
}
