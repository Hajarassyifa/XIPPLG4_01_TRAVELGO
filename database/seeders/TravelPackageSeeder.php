<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TravelPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('travel_packages')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('travel_packages')->insert([
            [
                'id' => 1,
                'name' => 'Pantai Kuta Bali Tour',
                'type' => 'Domestik',
                'destination' => 'Bali',
                'price' => 50000.00,
                'duration_days' => 3,
                'departure_date' => '2026-06-15',
                'description' => 'Pantai indah di Bali dengan pasir putih dan ombak yang menantang bagi para peselancar.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Eksplorasi Gunung Bromo',
                'type' => 'Domestik',
                'destination' => 'Jawa Timur',
                'price' => 250000.00,
                'duration_days' => 2,
                'departure_date' => '2026-06-20',
                'description' => 'Gunung berapi aktif yang terkenal dengan pemandangan matahari terbit yang magis.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Historical Candi Borobudur',
                'type' => 'Domestik',
                'destination' => 'Jawa Tengah',
                'price' => 100000.00,
                'duration_days' => 1,
                'departure_date' => '2026-07-05',
                'description' => 'Candi Buddha terbesar di dunia yang dibangun pada abad ke-9, kaya akan sejarah.',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}