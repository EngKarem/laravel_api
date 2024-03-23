<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car_image extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_number',
        'image_path'
    ];
}
