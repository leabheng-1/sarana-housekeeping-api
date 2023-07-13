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
            ->select('bookings.*', 'rooms.*', 'payments.*','guests.*' )
            ->get();
        return $this->sendResponse($booking, 'Booking retrieved successfully');
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
