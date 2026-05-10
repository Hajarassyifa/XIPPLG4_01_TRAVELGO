<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
{
    DB::table('destinasi')->insert([
        [
            'nama_destinasi' => 'Candi Borobudur',
            'lokasi'         => 'Jawa Tengah',
            'deskripsi'      => 'Candi Buddha terbesar di dunia.',
            'harga_tiket'    => 50000,
        ],
        [
            'nama_destinasi' => 'Pantai Kuta',
            'lokasi'         => 'Bali',
            'deskripsi'      => 'Pantai terkenal di Bali.',
            'harga_tiket'    => 20000,
        ],
    ]);
}
}
