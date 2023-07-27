<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments'; // Update with your actual table name
    protected $fillable = [
        'payment', 'payment_status', 'charges' ,'balance' , 'extra_charge', 'item_extra_charge'
    ];
}