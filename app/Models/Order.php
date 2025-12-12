<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Booking;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'userID',
        'bookingID',
        'name',
        'orderStatus',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'bookingID');
    }

    
    
}

