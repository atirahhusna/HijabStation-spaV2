<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Treatment;

class Booking extends Model
{
    use HasFactory;

    protected $primaryKey = 'BookingID'; // Custom primary key
    public $incrementing = true;
    protected $keyType = 'int';

    // Add 'staffName' to fillable for mass assignment
    protected $fillable = [
        'userID',
        'treatmentID',
        'email',
        'name',
        'phone',
        'slotTime',
        'slotNum',
        'date',
        'staffName', // Optional staff selection
    ];

    /**
     * Relationship: Booking belongs to User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }

    /**
     * Relationship: Booking belongs to Treatment.
     */
    public function treatment()
    {
        return $this->belongsTo(Treatment::class, 'treatmentID', 'treatmentID');
    }

    /**
     * Relationship: Booking has one Order.
     */
    public function order()
    {
        return $this->hasOne(Order::class, 'bookingID', 'BookingID');
    }
}
