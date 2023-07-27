<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Rooms;
class DashboardController extends BaseController
{
    /**
     * Get today's check-in and check-out bookings.
     *
     * @return \Illuminate\Http\Response
     */
    public function todayBooking()
    {
        // Get today's date
        $today = Carbon::today()->setTimezone('Asia/Phnom_Penh');

        // Get bookings with check-in date equal to today
        $checkInBookings = Booking::whereDate('checkin_date', $today)->get();
        $checkOutBookings = Booking::whereDate('checkout_date', $today)->get();

        $data = [
            'check_in_bookings' => $checkInBookings,
            'check_out_bookings' => $checkOutBookings,
            'today' =>  $today
        ];

        return $this->sendResponse($data, 'Today\'s check-in and check-out bookings retrieved successfully');
    }
    public function todayStatus(){
        $today = Carbon::today()->setTimezone('Asia/Phnom_Penh');
        $totalRoom = Rooms::count();
        $availableRooms = Rooms::where('room_status', '=', 'Vacant')->count();
        $clean = Rooms::where('housekeeping_status', '=','Clean')->count();
        $cleaning = Rooms::where('housekeeping_status', '=','Cleaning')->count();
        $dirty = Rooms::where('housekeeping_status', '=','Dirty')->count();
        $occupied = Rooms::where('room_status', '=','Occupied')->count();
        $block = Rooms::where('room_status', '=','Block')->count();
        $data = [
            'totalRoom' => $totalRoom,
            'available_rooms' => $availableRooms,
            'clean' => $clean,
            'cleaning' => $cleaning,
            'dirty' => $dirty,
            'occupied' => $occupied,
            'block' => $block
        ]; 

        return $this->sendResponse($data , '');
              
    }
    public function bookingsCountByDayOfWeek()
{
    $startOfWeek = Carbon::now()->startOfWeek();
    $endOfWeek = Carbon::now()->endOfWeek();

    $bookings = Booking::whereBetween('checkin_date', [$startOfWeek, $endOfWeek])->get();
    // Create an array to store bookings count for each day of the week
    $bookingsByDay = [
        'Sunday' => 0,
        'Monday' => 0,
        'Tuesday' => 0,
        'Wednesday' => 0,
        'Thursday' => 0,
        'Friday' => 0,
        'Saturday' => 0,
    ];
    foreach ($bookings as $booking) {
        // Ensure the booking has a valid check_in_date
        if ($booking->checkin_date) {
            $dayOfWeek = Carbon::parse($booking->checkin_date)->format('l'); 
            $bookingsByDay[$dayOfWeek]++;
        }
    }
    // Loop through bookings and count them for each day of the week
    return $this->sendResponse($bookingsByDay , '');
}
}
