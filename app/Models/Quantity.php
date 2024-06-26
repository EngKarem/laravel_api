<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quantity extends Model
{
    use HasFactory;
    protected $table = 'quantity';
    protected $fillable = [
        'liter',
        'material_name',
        'status'
    ];
}
