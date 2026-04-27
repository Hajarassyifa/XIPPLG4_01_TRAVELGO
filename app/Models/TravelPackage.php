<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelPackage extends Model
{
    protected $fillable = [
        'name',
        'type',
        'destination',
        'price',
        'duration_days',
        'departure_date',
        'description',
    ];
}
