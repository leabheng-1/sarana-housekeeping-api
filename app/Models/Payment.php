<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments'; // Update with your actual table name
    protected $fillable = [
        'payment_percent', 'payment_status', 'extra_charge', 'item_extra_charge'
    ];
}