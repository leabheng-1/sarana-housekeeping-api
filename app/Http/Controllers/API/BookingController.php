<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Guest;
use Illuminate\Http\Request;
use App\Http\Controllers\API\FunctionValidatorAndInsert;
use Illuminate\Support\Facades\Validator;

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
        $guest_input = new FunctionValidatorAndInsert(); 
        $validator = $guest_input->bookingValidator($request);
        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors(), 400);
        }
        $operation = 'insert';
        $booking = $guest_input->bookingInsert($request, $operation);
        return $this->sendResponse($booking, 'Booking inserted successfully');
    }
    // 
    public function selectAllBooking()
    {
        $booking = Booking::join('rooms', 'bookings.room_id', '=', 'rooms.id')
        ->join('payments', 'bookings.payment_id', '=', 'payments.id')
        ->join('guests', 'bookings.guest_id', '=', 'guests.id')
        ->leftJoin('housekeeping', function ($join) {
            $join->on('bookings.room_id', '=', 'housekeeping.room_id')
                 ->whereRaw('housekeeping.id = (select max(id) from housekeeping where room_id = bookings.room_id)');
        })
        ->select('bookings.id as booking_id', 'rooms.*', 'payments.*', 'guests.*', 'housekeeping.*')
        ->get();
    
        return $this->sendResponse($booking, 'Booking retrieved successfully');
    }
    public function checkIn($bookingId)
{
    $booking = Booking::findOrFail($bookingId);
    
    if ($booking->booking_status === 'checked_in') {
        return $this->sendResponse(null, 'Guest is already checked in.');
    }
    
    // Get the current date
    $currentDate = now()->toDateString();

    // Compare the current date with the check-in date
    if ($booking->checkin_date >= $currentDate) {
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
        
        if ($booking->booking_status === 'checked_out') {
            $data = [
                'sus' => false,
            ];
            return $this->sendResponse($data, 'Guest is already checked out.');
        }
        
        $booking->booking_status = 'checked_out';
        $booking->checkout_date = now();
        $currentDate = now()->toDateString();
        if ($booking->checkout_date == $currentDate) {

            $data = [
                'sus' => false,
            ];
            return $this->sendResponse($data, 'Cannot check out on a different date.');
        }
        
        $booking->save();
        $booking['sus'] = true;
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
        $guest_input = new FunctionValidatorAndInsert();
        $validator = $guest_input->bookingValidator($request); 
        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors(), 400);
        }
        $request->merge(['id' =>$id]);
        $operation = 'update';
        $booking = $guest_input->bookingInsert($request, $operation);
        return $this->sendResponse($booking, 'Booking updated successfully');
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
}
