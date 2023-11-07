<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Guest;
use App\Models\Rooms;
use Illuminate\Http\Request;
use App\Http\Controllers\API\FunctionValidatorAndInsert;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
class BookingController extends BaseController
{
    /**
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */

    // 
    public function insert(Request $request)
    {

        $input = new FunctionValidatorAndInsert();
        $validator = $input->bookingValidator($request);
        if ($validator->fails()) {
            return $this->sendError('bookingValidator.', ['error' => 'Unauthorised']);
        }
        $operation = 'insert';
        $booking = $input->bookingInsert($request, $operation);
        return $this->sendResponse($booking, 'Booking inserted successfully');
    }
    // 
    public function selectAllBooking(Request $request)
    {
        $dateParam = $request->input('date');
        $today = now()->format('Y-m-d');
        $today_date = Carbon::today()->setTimezone('Asia/Phnom_Penh');


        $dateToCompare = $dateParam ?? $today;

        $query = Rooms::leftJoin('bookings', function ($join) use ($dateToCompare) {
            $join->on('rooms.id', '=', 'bookings.room_id')
            ->whereNotIn('bookings.booking_status', ['Cancel', 'Void', 'Checked Out'])
                ->whereDate('bookings.checkin_date', '<=', $dateToCompare)
                ->whereDate('bookings.checkout_date', '>=', $dateToCompare);
        })
            ->leftJoin('payments', 'bookings.payment_id', '=', 'payments.id')
            ->leftJoin('guests', 'bookings.guest_id', '=', 'guests.id')
            ->leftJoin('housekeeping', function ($join) {
                $join->on('rooms.id', '=', 'housekeeping.room_id')
                    ->whereRaw('housekeeping.id = (select max(id) from housekeeping where room_id = rooms.id)');
            })
            ->select('bookings.id as booking_id','bookings.*','rooms.id as roomId', 'rooms.*', 'payments.*', 'guests.*', 'housekeeping.*');

        // Check if room_status and booking_status parameters are provided
        if ($request->has('booking_status') && $request->input('booking_status') != 'All' && $request->input('booking_status') != ''  ) {
            $roomStatus = $request->input('booking_status');
            if ($roomStatus == 'Varaible' ) {
                $query->WhereNull('bookings.id');
            }
           else{
            $query->where('bookings.booking_status', $roomStatus);

           }
        }
          if ($request->has('housekeeping_status') && $request->input('housekeeping_status') != 'All') {
            $bookingStatus = $request->input('housekeeping_status');
   
            $query->where('housekeeping.housekeeping_status', $bookingStatus);
     
            
        }
        if ($request->has('housekeeping_status') && $request->input('housekeeping_status') != 'All') {
            $bookingStatus = $request->input('housekeeping_status');
   
            $query->where('housekeeping.housekeeping_status', $bookingStatus);
     
            
        }
        if ($request->has('guest_name') && $request->input('guest_name') != 'All') {
            $guestName = $request->input('guest_name');
            $query->where('guests.name', 'LIKE', '%' . $guestName . '%');
        }
        if($request->has('page'))
        {

            $page = $request->input('page', 1); // Default to page 1 if not provided
        $itemsPerPage = $request->input('perPage', 10); // Default to 10 items per page
    
        // Apply pagination to the query
        $bookings = $query->paginate($itemsPerPage, ['*'], 'page', $page);
        }else{
            $bookings = $query->get();  
        }
        
    
        return $this->sendResponse($bookings, 'Booking retrieved successfully');
    }
    public function roomVariable(Request $request)
{
    $checkinDate = $request->input('checkin');
    $checkoutDate = $request->input('checkout');
    $roomType = $request->input('room_type'); // Assuming 'room_type' is the input field name for room type.

    $availableRooms = DB::table('rooms')
        ->leftJoin('bookings', 'rooms.id', '=', 'bookings.room_id')
        ->where(function ($query) use ($checkinDate, $checkoutDate) {
            $query->where(function ($query) use ($checkinDate, $checkoutDate) {
                $query->where('checkin_date', '>', $checkoutDate)
                    ->orWhere('checkout_date', '<', $checkinDate);
            })
            ->orWhereNull('bookings.id');
        })
        ->when($roomType, function ($query) use ($roomType) {
            $query->where('rooms.roomtype', '=', $roomType);
        })
        ->select('rooms.*')
        ->distinct()
        ->get();

    return $this->sendResponse($availableRooms, 'Available rooms retrieved successfully');
}

    public function room_date(Request $request)
    {
        $roomId = $request->input('room_id');
        $selectDate = $request->input('selectDate');

        $checkin = DB::table('bookings')
            ->where('room_id', $roomId)
            ->where('checkin_date', '>=', $selectDate)
            ->orderBy('checkin_date', 'asc')
            ->first();
            $checkout = DB::table('bookings')
            ->where('room_id', $roomId)
            ->where('checkout_date', '<=', $selectDate)
            ->orderBy('checkout_date', 'asc')
            ->first();
            $bookings = [
                'checkin' => $checkin,
                'checkout' => $checkout,
            ];
        
        return $this->sendResponse($bookings, 'Bookings retrieved successfully.');
          }

          public function selectBooking(Request $request)
          {
              $query = Rooms::rightJoin('bookings', 'rooms.id', '=', 'bookings.room_id')
                  ->leftJoin('payments', 'bookings.payment_id', '=', 'payments.id')
                  ->leftJoin('guests', 'bookings.guest_id', '=', 'guests.id')
                  ->leftJoin('housekeeping', function ($join) {
                      $join->on('bookings.room_id', '=', 'housekeeping.room_id')
                          ->whereRaw('housekeeping.id = (select max(id) from housekeeping where room_id = bookings.room_id)');
                  })
                  ->select('bookings.*', 'rooms.*', 'payments.*', 'guests.*', 'housekeeping.*');
          
            if($request->has('id')){
            $id = $request->input('id');
                  $query->where('bookings.id', $id );
              };
              if($request->has('page'))
              {
      
                  $page = $request->input('page', 1); // Default to page 1 if not provided
              $itemsPerPage = $request->input('perPage', 10); // Default to 10 items per page
          
              // Apply pagination to the query
              $booking = $query->paginate($itemsPerPage, ['*'], 'page', $page);
              }else{
                  $booking = $query->get();  
              };
              
          
              return $this->sendResponse($booking, 'Booking(s) retrieved successfully');
          }   
    public function checkIn($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        if ($booking->booking_status === 'Checked In') {
            return $this->sendError('Guest is already checked in.', '', 404);
        }

        // Get the current date
        $currentDate = now()->toDateString();

        // Compare the current date with the check-in date
        if ($booking->checkin_date <= $currentDate) {
            $booking->booking_status = 'In house';
            $booking->checkin_date = now();
            // Additional actions
            $booking->save();
            return $this->sendResponse($booking, 'Guest checked in successfully.');

        }


        return $this->sendResponse(null, 'Cannot check in on a different date.');
    }

    public function checkOut($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        if ($booking->booking_status === 'checked out') {
            return $this->sendError('Guest is already checked out.', '', 404);
        }
        $booking->booking_status = 'checked out';
        $booking->checkout_date = now();
        $currentDate = now()->toDateString();
        if ($booking->checkout_date == $currentDate) {
            return $this->sendError('Cannot check out on a different date.', '', 404);
        }
        $room = Rooms::find($booking->room_id);
        $room->room_status = 'variable'; // Assuming you have a relationship set up between Booking and Room models
        $room->save();
        $booking->save();
        return $this->sendResponse([$booking,], 'Guest checked out successfully.');
    }
    public function deleteGuest($id)
    {
        $guest = Guest::findOrFail($id);
        $guest->delete();
        return $this->sendResponse(null, 'Guest deleted successfully');
    }
    public function update(Request $request, $id)
    {
        $updatedBooking = new FunctionValidatorAndInsert();
        $validator = $updatedBooking->bookingValidator($request);


        // Find the booking by ID
        $booking = Booking::find($id);
        if (!$booking) {
            return $this->sendError('Booking Not Found', 'The booking with the given ID was not found.', 404);
        }

        $request->merge(['id' => $id]);
        $operation = 'update';
        $updatedBooking->bookingInsert($request, $operation);
        return $this->sendResponse(null, 'successfully');
    }

    public function find($keyword)
    {
        $Booking = Guest::where('id', 'LIKE', '%' . $keyword . '%')
            ->orWhere('guest_id', 'LIKE', '%' . $keyword . '%')
            ->get();

        if ($Booking->isNotEmpty()) {
            return $this->sendResponse($Booking, 'ture');
        } else {
            return $this->sendResponse($Booking, 'Record not found');
        }
    }
    
    public function updateBookingStatus( $action,$id)
{
    $booking = Booking::find($id);

    if (!$booking) {
        return $this->sendError('Booking Not Found', 'The booking with the given ID was not found.', 404);
    }

    // Update the booking status based on the action
    switch ($action) {
        case 'Void':
            $booking->booking_status = 'Void';
            break;
        case 'cancel':
            $booking->booking_status = 'Cancel';
            break;
            case 'undocheckin':
                $booking->booking_status = 'booking';
                break;
                case 'undocheckout':
                    $booking->booking_status = 'booking';
                    break;
                    
            case 'no_show':
                $booking->booking_status = 'No Show';
                break;  
                case 'checkin':
                    $booking->booking_status = 'In House';
                    break;  
                    case 'checkout':
                        $booking->booking_status = 'Checked Out';
                        break;            
        // Add more cases for other actions if needed
        default:
            return $this->sendError('Invalid Action', 'The provided action is not valid.', 400);
    }
    $room = Rooms::find($booking->room_id);
    // Update the checkout date to today
    $booking->checkout_date = now()->format('Y-m-d');

    // Update the room status
    $room->room_status = 'variable'; // Assuming you have a relationship set up between Booking and Room models

    $booking->save();
    $room->save();

    return $this->sendResponse($booking, 'Booking status and room status updated successfully');
}
public function cancel($id){
    return $this->updateBookingStatus($id,'cancel');
}
public function void($id){
    return $this->updateBookingStatus($id,'void');
}
public function moveStay(Request $request, $id)
{
    $booking = Booking::find($id);

    if (!$booking) {
        return $this->sendError('Booking Not Found', 'The booking with the given ID was not found.', 404);
    }

    $newRoomId = $request->input('new_room_id');

    // Check if the new room_id exists in the rooms table
    $newRoom = Rooms::find($newRoomId);
    if (!$newRoom) {
        return $this->sendError('Invalid Room ID', 'The provided new room ID does not exist.', 400);
    }

    // Update the room_id of the booking
    $booking->room_id = $newRoomId;
    $booking->save();

    return $this->sendResponse($booking, 'Booking status and room ID updated successfully');
}

}