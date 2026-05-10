<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destinasi extends Model
{
    protected $table = 'destinasi';
    protected $fillable = ['nama_destinasi', 'lokasi', 'deskripsi', 'harga_tiket'];
}