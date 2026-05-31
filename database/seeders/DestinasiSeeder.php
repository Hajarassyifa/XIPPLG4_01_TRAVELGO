<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Destinasi;

class DestinasiSeeder extends Seeder
{
    public function run(): void
    {
        $destinasi = [
            [
                'nama_destinasi' => 'Pantai Kuta',
                'lokasi'         => 'Bali',
                'deskripsi'      => 'Pantai indah di Bali dengan pasir putih dan ombak yang cocok untuk surfing. Salah satu destinasi wisata paling populer di Indonesia.',
                'harga_tiket'    => 50000,
            ],
            [
                'nama_destinasi' => 'Gunung Bromo',
                'lokasi'         => 'Jawa Timur',
                'deskripsi'      => 'Gunung berapi aktif yang terkenal dengan pemandangan sunrise yang spektakuler dan lautan pasirnya yang luas.',
                'harga_tiket'    => 250000,
            ],
            [
                'nama_destinasi' => 'Candi Borobudur',
                'lokasi'         => 'Jawa Tengah',
                'deskripsi'      => 'Candi Buddha terbesar di dunia yang dibangun pada abad ke-8. Merupakan situs warisan dunia UNESCO.',
                'harga_tiket'    => 100000,
            ],
            [
                'nama_destinasi' => 'Raja Ampat',
                'lokasi'         => 'Papua Barat',
                'deskripsi'      => 'Surga bawah laut dengan keanekaragaman hayati laut terkaya di dunia. Cocok untuk snorkeling dan diving.',
                'harga_tiket'    => 500000,
            ],
            [
                'nama_destinasi' => 'Danau Toba',
                'lokasi'         => 'Sumatera Utara',
                'deskripsi'      => 'Danau vulkanik terbesar di dunia dengan Pulau Samosir di tengahnya. Pemandangan alam yang memukau.',
                'harga_tiket'    => 75000,
            ],
            [
                'nama_destinasi' => 'Labuan Bajo',
                'lokasi'         => 'Nusa Tenggara Timur',
                'deskripsi'      => 'Pintu masuk menuju Taman Nasional Komodo. Terkenal dengan komodo, pantai pink, dan keindahan bawah lautnya.',
                'harga_tiket'    => 350000,
            ],
            [
                'nama_destinasi' => 'Tanah Lot',
                'lokasi'         => 'Bali',
                'deskripsi'      => 'Pura Hindu yang berdiri di atas batu karang di tepi laut. Spot terbaik untuk menikmati sunset di Bali.',
                'harga_tiket'    => 60000,
            ],
            [
                'nama_destinasi' => 'Kawah Ijen',
                'lokasi'         => 'Jawa Timur',
                'deskripsi'      => 'Kawah vulkanik dengan fenomena api biru yang unik di dunia. Penghasil belerang terbesar di Indonesia.',
                'harga_tiket'    => 150000,
            ],
        ];

        foreach ($destinasi as $item) {
            Destinasi::create($item);
        }
    }
}