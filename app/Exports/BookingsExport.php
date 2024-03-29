<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Carbon;

class BookingsExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data)->map(function ($booking) {
            $checkIn = Carbon::createFromFormat('Y-m-d', $booking->checkin_date); // Assuming date format is 'Y-m-d'
            $checkOut = Carbon::createFromFormat('Y-m-d', $booking->checkout_date); // Assuming date format is 'Y-m-d'
            
            
            // Check if both dates are valid Carbon instances
            if ($checkIn && $checkOut) {
                $night = $checkOut->diffInDays($checkIn);
            } else {
                $night = ''; // Set night to empty if dates are not valid
            }

            return [
                $booking->id,
                $booking->name,
                $booking->room_number,
                $booking->room_type,
                $booking->room_rate,
                $booking->checkin_date,
                $booking->checkout_date,
                $night,
                $booking->charges,
                $booking->payment_type

            ];
        });
    }

    public function headings(): array
    {
        return [
            'Booking ID',
            'Guest Name',
            'Room Number',
            'Room Type',
            'Room Rate',
            'Check In',
            'Check Out',
            'Night',
            'Amount',
            'payment_type',
            
            
        ];
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getStyle('A1:J1')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F4B084'], // Change the color code as needed
                    ],
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'], // Change the font color as needed
                    ],
                ]);
            },
        ];
    }
}
