<?php
// app/Exports/BookingsExport.php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;

class BookingsExport implements FromCollection
{
    protected $data; // Define a protected property to store the data

    public function __construct($data)
    {
        $this->data = $data; // Inject the data into the class via the constructor
    }

    public function collection()
    {
        return collect($this->data); // Return the data to be exported as a collection
    }
}
