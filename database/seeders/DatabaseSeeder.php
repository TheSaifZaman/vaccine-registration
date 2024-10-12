<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\VaccineCenter\Database\Seeders\VaccineCenterSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            VaccineCenterSeeder::class,
        ]);
    }
}
