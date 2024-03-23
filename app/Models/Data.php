<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'qr',
        'Kilo1',
        'user',
        'station',
        'owner',
        'plate',
        'model',
        'notes',
        'oldnum',
        'color',
        'ycar',
        'mob',
        'eng',
        'iden',
        'quantity',
        'material',
        'latest_packing',
        'city',
        'num_repeat'
    ];

    public function setUpdatedAtCustom($value)
    {
        $this->attributes['updated_at'] = $value;
    }
}
