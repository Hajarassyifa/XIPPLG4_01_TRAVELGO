<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings';

    protected $fillable = [
        'user_id',
        'destinasi_id',
        'booking_code',
        'tanggal_berangkat',
        'jumlah_tiket',
        'total_harga',
        'status',
        'payment_status',
        'payment_method',
        'customer_name',
        'customer_email',
        'customer_phone',
        'special_requests',
        'qr_code',
    ];

    protected $casts = [
        'total_harga'      => 'float',
        'jumlah_tiket'     => 'integer',
        'tanggal_berangkat'=> 'date:Y-m-d',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function destinasi()
    {
        return $this->belongsTo(Destinasi::class, 'destinasi_id');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'booking_id');
    }
}