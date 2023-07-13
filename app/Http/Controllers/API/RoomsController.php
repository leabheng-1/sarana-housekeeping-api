<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Rooms;
use App\Http\Controllers\API\FunctionValidatorAndInsert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoomsController extends BaseController
{
  
     public function insert(Request $request)
     {
         $room_input = new FunctionValidatorAndInsert(); 
         $validator = $room_input->roomValidator($request);
         if ($validator->fails()) {
             return $this->sendError('Validation Error', $validator->errors(), 400);
         }
         $operation = 'insert';
         $room = $room_input->roomInsert($request, $operation);
         return $this->sendResponse($room, 'room inserted successfully');
     }
    public function selectAllRooms()
    {
        $rooms = rooms::all();
        return $this->sendResponse($rooms, 'rooms retrieved successfully');
    }
    public function delete($id)
    {
        $room = rooms::findOrFail($id);
        $room->delete();
        return $this->sendResponse(null, 'room deleted successfully');
    }
    public function update(Request $request, $id)
    {
        $room_update = new FunctionValidatorAndInsert(); 
        $request->merge(['id' =>$id]);
        $operation = 'update';
        $room = $room_update->roomInsert($request, $operation );
        return $this->sendResponse($room, 'room updated successfully');
    }
    public function find($keyword)
    {
        $rooms = room::where('id', 'LIKE', '%' . $keyword . '%')
            ->orWhere('name', 'LIKE', '%' . $keyword . '%')
            ->get();

        if ($rooms->isNotEmpty()) {
            return $this->sendResponse($rooms, 'ture');
        } else {
            return $this->sendResponse($rooms, 'Record not found');
        }
    }
}
