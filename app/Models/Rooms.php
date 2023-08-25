<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rooms extends Model
{
    protected $table = 'rooms'; // Update with your actual table name
    protected $fillable = [
        'room_number', 'room_status', 'roomtype', 'floor','room_rate','note','air_method'
    ];
}