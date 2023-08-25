<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Housekeeping extends Model
{
    use HasFactory;

    protected $table = 'housekeeping'; // Update with your actual table name

    protected $fillable = [
        'room_id', 'housekeeper', 'housekeeping_status', 'date'
    ];

    protected $dateFormat = 'Y-m-d';
}
