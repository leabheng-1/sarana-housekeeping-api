<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Rooms;
use App\Models\Guest;
use App\Models\Housekeeping;
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
        $checkInBookings = Booking::whereDate('arrival_date', $today)
        ->join('rooms', 'rooms.id', '=', 'bookings.room_id')
        ->leftJoin('payments', 'bookings.payment_id', '=', 'payments.id')
        ->leftJoin('guests', 'bookings.guest_id', '=', 'guests.id')
        ->leftJoin('housekeeping', function ($join) {
            $join->on('bookings.room_id', '=', 'housekeeping.room_id')
                 ->whereRaw('housekeeping.id = (select max(id) from housekeeping where room_id = bookings.room_id)');
        })->select('bookings.id as booking_id','bookings.*','rooms.id as roomId', 'rooms.*', 'payments.*', 'guests.*', 'housekeeping.*')
        ->where('bookings.booking_status', '!=', 'In House') // Add this line to filter by booking status
        ->get();
    

        $checkOutBookings = Booking::whereDate('departure_date', $today)->join('rooms', 'rooms.id', '=', 'bookings.room_id')
        ->leftJoin('payments', 'bookings.payment_id', '=', 'payments.id')
        ->leftJoin('guests', 'bookings.guest_id', '=', 'guests.id')
        ->leftJoin('housekeeping', function ($join) {
            $join->on('bookings.room_id', '=', 'housekeeping.room_id')
                 ->whereRaw('housekeeping.id = (select max(id) from housekeeping where room_id = bookings.room_id)');
        })->select('bookings.id as booking_id','bookings.*','rooms.id as roomId', 'rooms.*', 'payments.*', 'guests.*', 'housekeeping.*')
        ->where('bookings.booking_status', '!=', 'Check Out') // Add this line to filter by booking status
        ->get();
        $arrival_date = Booking::whereDate('arrival_date', $today)->join('guests', 'bookings.guest_id', '=', 'guests.id')->get();
        $departure_date = Booking::whereDate('departure_date', $today)->join('guests', 'bookings.guest_id', '=', 'guests.id')->get();
        $count_checkin = $checkInBookings->count();
        $count_checkout = $checkOutBookings->count();
        $count_arrival = $arrival_date->count();
        $count_departure = $departure_date->count();
        
        $data = [
            'check_in_bookings' => $checkInBookings,
            'check_out_bookings' => $checkOutBookings,
            'checkin_count'=> $count_checkin ,
            'checkout_count'=> $count_checkout ,
            'departure_date'=>$departure_date,
            'arrival_date'=>$arrival_date,
            'count_arrival'=> $count_arrival ,
            'count_departure'=> $count_departure ,


        ];

        return $this->sendResponse($data, 'Today\'s check-in and check-out bookings retrieved successfully');
    }
    public function getRoomData($st){
        $query = Rooms::leftJoin('housekeeping', function ($join){
                      $join->on('rooms.id', '=', 'housekeeping.room_id')
                          ->whereRaw('housekeeping.id = (select max(id) from housekeeping where room_id = rooms.id)')
                         ;
                  }) ->whereRaw('housekeeping.housekeeping_status = ?', [$st]) ->select('rooms.id');
        $roomDataQuery = Rooms::Join('housekeeping', function($join) use ($st) {
            $join->on('rooms.id', '=', 'housekeeping.room_id')
            ->whereRaw('housekeeping.date = (select max(date) from housekeeping where housekeeping.room_id = rooms.id)')
            ->whereRaw('housekeeping.housekeeping_status = ?', [$st]);
        })
        ->select('rooms.id');
        $roomDataCount = $query->count();
        $roomData = $query->get();
        $data = [
            'data' => $roomData,
            'count' => $roomDataCount,
        ];
        return $data;
    }
    public function todayStatus(){
        $today = Carbon::today()->setTimezone('Asia/Phnom_Penh');
        $totalRoom = Rooms::count();

        $bookingRooms = Rooms::Join('bookings', 'rooms.id', '=', 'bookings.room_id')
        ->where('bookings.arrival_date', '<=', $today)
    ->where('bookings.departure_date', '>=', $today)
    ->count();
    $availableRoomBooking = Rooms::leftJoin('bookings', 'rooms.id', '=', 'bookings.room_id') 
        ->leftJoin('payments', 'bookings.payment_id', '=', 'payments.id')
        ->leftJoin('guests', 'bookings.guest_id', '=', 'guests.id')
        ->leftJoin('housekeeping', function ($join) {
            $join->on('rooms.id', '=', 'housekeeping.room_id')
                ->whereRaw('housekeeping.id = (select max(id) from housekeeping where room_id = rooms.id)');
        })
        ->WhereNull('bookings.id')
        ->select('bookings.id as booking_id','bookings.*','rooms.id as roomId', 'rooms.*', 'payments.*', 'guests.*', 'housekeeping.*')
        ->get()
        ;
    $availableRooms = $totalRoom - $bookingRooms;
        $clean = $this->getRoomData('clean');

$inHouse = Booking::where('booking_status', '=', 'In House')
    ->where('arrival_date', '<=', $today)
    ->where('departure_date', '>=', $today)
    ->count();
        $cleaning = $this->getRoomData('cleaning');
        $dirty = $this->getRoomData('dirty');
        $occupied = $bookingRooms;
        $block = Rooms::where('room_status', '=','Block')->count();
        $checkInBookings = Booking::whereDate('checkin_date', $today)
        ->join('rooms', 'rooms.id', '=', 'bookings.room_id')
        ->leftJoin('payments', 'bookings.payment_id', '=', 'payments.id')
        ->leftJoin('guests', 'bookings.guest_id', '=', 'guests.id')
        ->leftJoin('housekeeping', function ($join) {
            $join->on('bookings.room_id', '=', 'housekeeping.room_id')
                 ->whereRaw('housekeeping.id = (select max(id) from housekeeping where room_id = bookings.room_id)');
        })->select('bookings.id as booking_id','bookings.*','rooms.id as roomId', 'rooms.*', 'payments.*', 'guests.*', 'housekeeping.*')
        // Add this line to filter by booking status
        ->where('bookings.booking_status', 'In House')
        ->get();
        $checkOutBookings = Booking::whereDate('checkout_date', $today)->join('rooms', 'rooms.id', '=', 'bookings.room_id')
        ->leftJoin('payments', 'bookings.payment_id', '=', 'payments.id')
        ->leftJoin('guests', 'bookings.guest_id', '=', 'guests.id')
        ->leftJoin('housekeeping', function ($join) {
            $join->on('bookings.room_id', '=', 'housekeeping.room_id')
                 ->whereRaw('housekeeping.id = (select max(id) from housekeeping where room_id = bookings.room_id)');
        })
        ->where('bookings.booking_status', 'Checked Out')
        ->select('bookings.id as booking_id','bookings.*','rooms.id as roomId', 'rooms.*', 'payments.*', 'guests.*', 'housekeeping.*')
        ->get();
        $InhousetBookings = Booking::join('rooms', 'rooms.id', '=', 'bookings.room_id')
        ->leftJoin('payments', 'bookings.payment_id', '=', 'payments.id')
        ->leftJoin('guests', 'bookings.guest_id', '=', 'guests.id')
        ->leftJoin('housekeeping', function ($join) {
            $join->on('bookings.room_id', '=', 'housekeeping.room_id')
                 ->whereRaw('housekeeping.id = (select max(id) from housekeeping where room_id = bookings.room_id)');
        })
        ->where('bookings.arrival_date', '<=', $today)
        ->where('bookings.departure_date', '>=', $today)
        ->where('bookings.booking_status', 'In House')
        ->select('bookings.id as booking_id','bookings.*','rooms.id as roomId', 'rooms.*', 'payments.*', 'guests.*', 'housekeeping.*')
        ->get();
        $arrival_date = Booking::whereDate('arrival_date', $today)->join('guests', 'bookings.guest_id', '=', 'guests.id')->get();
        $departure_date = Booking::whereDate('departure_date', $today)->join('guests', 'bookings.guest_id', '=', 'guests.id')->get();
        $count_checkin = $checkInBookings->count();
        $count_checkout = $checkOutBookings->count();
        $count_arrival = $arrival_date->count();
        $count_departure = $departure_date->count();
      
       
        $arrivalBookings = Booking::whereDate('arrival_date', $today)->join('rooms', 'rooms.id', '=', 'bookings.room_id')
        ->leftJoin('payments', 'bookings.payment_id', '=', 'payments.id')
        ->leftJoin('guests', 'bookings.guest_id', '=', 'guests.id')
        ->leftJoin('housekeeping', function ($join) {
            $join->on('bookings.room_id', '=', 'housekeeping.room_id')
                 ->whereRaw('housekeeping.id = (select max(id) from housekeeping where room_id = bookings.room_id)');
        })->select('bookings.id as booking_id','bookings.*','rooms.id as roomId', 'rooms.*', 'payments.*', 'guests.*', 'housekeeping.*')
        ->get();
        $departureBookings = Booking::whereDate('departure_date', $today)->join('rooms', 'rooms.id', '=', 'bookings.room_id')
        ->leftJoin('payments', 'bookings.payment_id', '=', 'payments.id')
        ->leftJoin('guests', 'bookings.guest_id', '=', 'guests.id')
        ->leftJoin('housekeeping', function ($join) {
            $join->on('bookings.room_id', '=', 'housekeeping.room_id')
                 ->whereRaw('housekeeping.id = (select max(id) from housekeeping where room_id = bookings.room_id)');
        })->select('bookings.id as booking_id','bookings.*','rooms.id as roomId', 'rooms.*', 'payments.*', 'guests.*', 'housekeeping.*')
        ->get();
        $departureBookings = Booking::whereDate('departure_date', $today)->join('rooms', 'rooms.id', '=', 'bookings.room_id')
        ->leftJoin('payments', 'bookings.payment_id', '=', 'payments.id')
        ->leftJoin('guests', 'bookings.guest_id', '=', 'guests.id')
        ->leftJoin('housekeeping', function ($join) {
            $join->on('bookings.room_id', '=', 'housekeeping.room_id')
                 ->whereRaw('housekeeping.id = (select max(id) from housekeeping where room_id = bookings.room_id)');
        })->select('bookings.id as booking_id','bookings.*','rooms.id as roomId', 'rooms.*', 'payments.*', 'guests.*', 'housekeeping.*')
        ->get();
        $data = [
            'totalRoom' => $totalRoom,
            'available_rooms' => $availableRooms,
            'clean' => $clean,
            'cleaning' => $cleaning,
            'dirty' => $dirty,
            'occupied' => $occupied,
            'block' => $block,
            'checkin_count'=> $count_checkin ,
            'checkout_count'=> $count_checkout ,
            'inHouse'=>$inHouse,
            'count_arrival'=> $count_arrival ,
            'count_departure'=> $count_departure ,
            'checkInBookings'=>$checkInBookings ,
            'checkOutBookings'=>$checkOutBookings,
            'arrivalBookings'=>$arrivalBookings,
            'departureBookings'=>$departureBookings,
            'availableRoomBooking'=>$availableRoomBooking,
            'InhousetBookings'=>$InhousetBookings
        ]; 

        return $this->sendResponse($data , '');
              
    }
    public function bookingsCountByDayOfWeek()
{
    $startOfWeek = Carbon::now()->startOfWeek();
    $endOfWeek = Carbon::now()->endOfWeek();

    $bookings = Booking::whereBetween('arrival_date', [$startOfWeek, $endOfWeek])->get();
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
        if ($booking->arrival_date) {
            $dayOfWeek = Carbon::parse($booking->arrival_date)->format('l'); 
            $bookingsByDay[$dayOfWeek]++;
        }
    }
    // Loop through bookings and count them for each day of the week
    return $this->sendResponse($bookingsByDay , '');
}
}
