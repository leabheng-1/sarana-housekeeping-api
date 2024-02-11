<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Guest;
use App\Models\Rooms;
use App\Models\Housekeeping;
use Illuminate\Http\Request;
use App\Http\Controllers\API\FunctionValidatorAndInsert;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\API\HousekeepingController;
class GuestsController extends BaseController
{
    /**
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    
     public function insert(Request $request)
     {
         $guest_input = new FunctionValidatorAndInsert(); 
         $validator = $guest_input->guestValidator($request);
         if ($validator->fails()) {
             return $this->sendError('Validation Error', $validator->errors(), 400);
         }
         $operation = 'insert';
         $guest = $guest_input->guestInsert($request, $operation);
         return $this->sendResponse($guest, 'Guest s inserted successfully');
     }
    public function selectAllGuests(Request $request)
    {
        $guests = Guest::rightJoin('bookings', 'guests.id', '=', 'bookings.guest_id')
        ->leftJoin('payments', 'bookings.payment_id', '=', 'payments.id')
        ->leftJoin('rooms', 'bookings.room_id', '=', 'rooms.id')
        ->leftJoin('housekeeping', function ($join) {
            $join->on('bookings.room_id', '=', 'housekeeping.room_id')
                ->whereRaw('housekeeping.id = (select max(id) from housekeeping where room_id = bookings.room_id)');
        })
        ->select('bookings.id as booking_id', 'bookings.room_rate as booking_room_rate' ,'bookings.*','rooms.id as roomId', 'rooms.*','rooms.room_number as roomnumber', 'payments.*', 'guests.*', 'housekeeping.*');
        if ($request->has('Search') && $request->has('Search') !='' && $request->input('Search') != 'All') {
            $guestName = $request->input('Search');
            $guests->where('guests.name', 'LIKE', '%' . $guestName . '%')
            ->orWhere('rooms.room_number', 'LIKE', '%' . $guestName . '%')
            ->orWhere('rooms.id', 'LIKE', '%' . $guestName . '%');
            ;

        }
        // $guests = Guest::rightJoin('bookings', 'guests.id', '=', 'bookings.guest_id') ;
        if ($request->has('housekeeping_status') && $request->input('housekeeping_status') != 'All') {
            $bookingStatus = $request->input('housekeeping_status');
            $guests->where('housekeeping.housekeeping_status', $bookingStatus);
     
            
        }
        if ($request->has('room_status') && $request->input('room_status') != 'All') {
            
            $room_status = $request->input('room_status');
            $guests-->where('rooms.room_status', $room_status);

            
        }
        if($request->has('page'))
        {

            $page = $request->input('page', 1); // Default to page 1 if not provided
        $itemsPerPage = $request->input('perPage', 10); // Default to 10 items per page
    
        // Apply pagination to the query
        $bookings = $guests->paginate($itemsPerPage, ['*'], 'page', $page);
        }else{
            $bookings = $guests->get();  
        }
        return $this->sendResponse($bookings, 'Guests retrieved successfully');
    }
    public function deleteGuest($id)
    {
        $guest = Guest::findOrFail($id);
        $guest->delete();
        return $this->sendResponse(null, 'Guest deleted successfully');
    }
    public function updateGuest(Request $request, $id)
    {
        $guest_update = new FunctionValidatorAndInsert(); 
        $request->merge(['id' =>$id]);
        $operation = 'update';
        $guest = $guest_update->guestInsert($request, $operation );
        return $this->sendResponse($guest, 'Guest updated successfully');
    }
    public function find($keyword)
    {
        $guests = Guest::where('id', 'LIKE', '%' . $keyword . '%')
            ->orWhere('name', 'LIKE', '%' . $keyword . '%')
            ->get();

        if ($guests->isNotEmpty()) {
            return $this->sendResponse($guests, 'ture');
        } else {
            return $this->sendResponse($guests, 'Record not found');
        }
    }
}
