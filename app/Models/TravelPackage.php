<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TravelPackage extends Model
{
    // Mengarahkan model agar membaca tabel 'destinasi' sesuai dengan phpMyAdmin kamu
    protected $table = 'destinasi';

    protected $fillable = [
        'nama_destinasi',
        'lokasi',
        'deskripsi',
        'harga_tiket',
        'image',
        'open_time',
        'close_time',
    ];

    public function reviews(): HasMany
    {
        // Relasi mengikat kolom foreign key 'travel_package_id' yang ada di tabel reviews
        return $this->hasMany(Review::class, 'travel_package_id');
    }
}