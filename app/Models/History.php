<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'qr',
        'current_kilo',
        'user',
        'station',
        'owner',
        'plate',
        'model',
        'oldnum',
        'color',
        'ycar',
        'quantity',
        'material',
        'latest_packing',
        'city',
        'old_kilo',
    ];

    public function setUpdatedAtCustom($value)
    {
        $this->attributes['updated_at'] = $value;
    }
}
