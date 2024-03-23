<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_station extends Model
{
    use HasFactory;
    protected $table = 'user_stations';
    protected $fillable = [
        'user_id',
        'station_number'
    ];
}
