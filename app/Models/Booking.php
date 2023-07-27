<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings'; // Update with your actual table name

    protected $fillable = [
        'guest_id', 'room_id','room_type', 'payment_id', 'is_cancel', 'cancel_date', 'arrival_date', 'departure_date', 'checkin_date', 'checkout_date',
        'adults','child','created_by','note'
    ];
    protected $dateFormat = 'Y-m-d'; 
}