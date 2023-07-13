<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    protected $table = 'guests'; // Update with your actual table name

    protected $fillable = [
        'name', 'gender', 'phone_number', 'email', 'country', 'dob', 'passport_number', 'card_id', 'other_information',
    ];

    // Define any relationships, such as with other models, here
    // Define any additional functionality or methods you need for the Guest model 
}