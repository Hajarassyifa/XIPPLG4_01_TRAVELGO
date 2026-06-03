<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserPasswordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Memaksa update password budi@travel.com dengan enkripsi Bcrypt resmi Laravel
        DB::table('users')->where('email', 'budi@travel.com')->update([
            'password' => Hash::make('budi123'),
            'updated_at' => now(),
        ]);
    }
}