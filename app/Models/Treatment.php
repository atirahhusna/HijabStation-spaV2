<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    protected $primaryKey = 'treatmentID'; // Custom primary key

    protected $fillable = [
        't_name',
        't_price',
        't_desc',
        't_pic',
        't_duration',
        'slotNum',      // ✅ Add this
        'slotTime',     // ✅ And this
    ];

    protected $casts = [
        'slotTime' => 'array', // ✅ Optional: auto-decode JSON into array
    ];
}
