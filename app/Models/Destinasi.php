<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destinasi extends Model
{
    protected $table = 'destinasi';

    protected $fillable = [
        'nama_destinasi',
        'lokasi',
        'deskripsi',
        'harga_tiket',
    ];

    protected $casts = [
        'harga_tiket' => 'float',
    ];

    // Relasi ke Booking
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'destinasi_id');
    }
}