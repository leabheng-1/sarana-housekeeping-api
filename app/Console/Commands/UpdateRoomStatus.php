<?php 
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;

class UpdateRoomStatus extends Command
{
    protected $signature = 'update:room-status';
    protected $description = 'Update room statuses based on check-in dates';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $today = now()->format('Y-m-d');
    
    Booking::where('checkin_date', $today)
        ->update([
            'booking_status' => DB::raw("'Check in'"),
        ]);

    $this->info('Room statuses updated successfully.');
    }
}


