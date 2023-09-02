<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Rooms;
use App\Models\Guest;
use App\Models\Housekeeping;
use App\Http\Controllers\API\DashboardController;

class StatusController extends BaseController
{
    public function getBookingsByStatusToday()
    {
        $today = Carbon::today();
        
        // Define the valid booking statuses
        $validStatuses = ['checkin', 'checkout', 'arrival', 'departure'];

        $data = [];

        foreach ($validStatuses as $status) {
            // Query the database for bookings with the current status and today's date
            $bookings = Booking::where($status.'_date', $today)->get();

            $bookingCount = $bookings->count();
            $bookingIds = $bookings->pluck('id');
           
            $data[$status] = [
                'count' => $bookingCount,
                'ids' => $bookingIds,
            ];
          
        }
        $status_booking = new DashboardController();
        $status_booking_1 = $status_booking->todayStatus(); 
        $data_status = [
            'room' => $status_booking_1,
            'booking' => $data
        ];
        return $this->sendResponse($data_status , 'Bookings for today retrieved successfully');
    }
}
