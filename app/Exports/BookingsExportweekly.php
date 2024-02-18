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
            return [
                $booking->date,
                $booking->check_in,
                $booking->check_out,
                $booking->payment,
                $booking->single_room,
                $booking->twin_room,

            ];
        });
    }

    public function headings(): array
    {
        return [
            'Day',
            'Check in',
            'Check Out',
            'Amount',
            'Single Room',
            'Twin Room',
            
            
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
