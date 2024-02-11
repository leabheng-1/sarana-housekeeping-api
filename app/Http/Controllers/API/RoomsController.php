<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Rooms;
use App\Http\Controllers\API\FunctionValidatorAndInsert;
use App\Http\Controllers\API\HousekeepingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class RoomsController extends BaseController
{
  
     public function insert(Request $request)
     {
         $room_input = new FunctionValidatorAndInsert(); 
         $HousekeepingController = new HousekeepingController();
         $validator = $room_input->roomValidator($request);
         if ($validator->fails()) {
             return $this->sendError('Validation Error', $validator->errors(), 400);
         }
         $operation = 'insert';
         $room = $room_input->roomInsert($request, $operation);
         $request->merge(['room_id' => $room->id]);
         $HousekeepingController = $HousekeepingController->insert($request);
         return $this->sendResponse($room, 'room inserted successfully');
     }
     public function selectAllRooms(Request $request)
{
    $roomType = $request->input('roomType'); // Get the 'roomType' parameter from the request
    $id = $request->input('id'); // Get the 'id' parameter from the request
    $room_number = $request->input('room_number');
    $query = Rooms::leftJoin('housekeeping', function ($join) {
        $join->on('rooms.id', '=', 'housekeeping.room_id')
            ->whereRaw('housekeeping.id = (select max(id) from housekeeping where room_id = rooms.id)');
    });

    if ($roomType && $roomType != 'All' ) {
        // Add a where clause to filter by room type
        $query->where('rooms.roomtype', roomType);
    }
    if ($room_number && $room_number != 'All' && $room_number != '' ) {
        // Add a where clause to filter by room type
        $query->where('rooms.room_number', $room_number);
    }

    if ($id) {
        // Select only the 'id' column when an 'id' is provided
        $query->select('rooms.id');
    } else {
        // Select all columns when 'id' is not provided
        $query->select( 'rooms.id as room_id' , 'rooms.*', 'housekeeping.*');
    }

    $rooms = $query->get();

    return $this->sendResponse($rooms, 'Rooms retrieved successfully');
}

    public function delete($id)
    {
        $room = rooms::findOrFail($id);
        $room->delete();
        return $this->sendResponse(null, 'room deleted successfully');
    }
    public function update(Request $request, $id)
    {
        $roomUpdate = new FunctionValidatorAndInsert();
        $HousekeepingController = new HousekeepingController();
        
        // Find the room by ID
        $room = Rooms::find($id);
        
        if (!$room) {
            return $this->sendError('Room not found.', 404);
        }
        
        // Update the room
        $request->merge(['id' => $id]);
        $roomOperation = 'update';
        $room = $roomUpdate->roomInsert($request, $roomOperation);
        $request->merge(['room_id' => $id]);
        $HousekeepingController = $HousekeepingController->insert($request);
        $data = [
            'Room' => $room,
            'Housekeeping' => $HousekeepingController,
        ]; 
        return $this->sendResponse($data, 'Room and housekeeping updated successfully');
        
    }
    public function find($keyword)
    {
        $rooms = Rooms::where('id', 'LIKE', '%' . $keyword . '%')
            ->orWhere('name', 'LIKE', '%' . $keyword . '%')
            ->get();

        if ($rooms->isNotEmpty()) {
            return $this->sendResponse($rooms, 'ture');
        } else {
            return $this->sendResponse($rooms, 'Record not found');
        }
    }
}
