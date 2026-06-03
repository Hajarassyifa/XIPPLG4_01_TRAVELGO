<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArtikelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mengisi data tiruan langsung spesifik ke tabel artikels
        DB::table('artikels')->insert([
            [
                'judul' => 'Tips Traveling Hemat Musim Liburan',
                'isi' => 'Traveling hemat bisa disiasati dengan memesan tiket pesawat dari jauh-jauh hari, memilih penginapan lokal jenis homestay, serta membawa botol minum sendiri untuk mengurangi pengeluaran harian Anda.',
                'kategori' => 'Tips',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Rekomendasi Destinasi Keren di Indonesia',
                'isi' => 'Selain Bali, Indonesia memiliki banyak surga tersembunyi yang wajib dikunjungi seperti indahnya Labuan Bajo, sirkuit internasional Mandalika, dan pesona bawah laut Raja Ampat yang mendunia.',
                'kategori' => 'Rekomendasi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => '5 Barang yang Wajib Dibawa Saat Solo Traveling',
                'isi' => 'Saat memutuskan bepergian sendiri, pastikan Anda tidak melupakan barang krusial ini: Powerbank berkapasitas besar, dompet obat-obatan pribadi, botol air lipat, peta offline, dan dokumen identitas fisik.',
                'kategori' => 'Tips',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Menjelajahi Kuliner Tradisional yang Menggugah Selera',
                'isi' => 'Setiap daerah punya cita rasa unik. Jangan lewatkan berburu Gudeg otentik saat di Yogyakarta, mencicipi pedasnya Ayam Betutu di Bali, atau menikmati kehangatan semangkuk Coto di Makassar langsung dari warung lokal.',
                'kategori' => 'Kuliner',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Aplikasi Penting Pendukung Perjalanan Luar Negeri',
                'isi' => 'Agar perjalanan antarnegara berjalan mulus tanpa hambatan, unduh aplikasi penting penunjang liburan seperti Google Translate untuk menerjemahkan papan nama jalan, Currency Converter untuk konversi kurs uang, dan aplikasi navigasi lokal setempat.',
                'kategori' => 'Edukasi',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}