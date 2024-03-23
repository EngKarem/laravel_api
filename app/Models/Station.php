<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    use HasFactory;
    protected $primaryKey = 'number';
    protected $table = 'station';
    protected $fillable = [
        'number',
        'name',
        'address',
        'petrol',
        'diesel',
        'gas',
        'user_id'
    ];
}
