<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
    use HasFactory;

    protected $fillable = [
        'kilo_difference',
        'days',
        'add_image',
        'add_num',
        'repeat_num',
        "maintenance_message",
        "maintenance_mode"
    ];
}
