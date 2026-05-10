<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DestinasiSeeder extends Seeder
{
    public function run()
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