<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Guest;
use App\Http\Controllers\API\FunctionValidatorAndInsert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
         return $this->sendResponse($guest, 'Guest inserted successfully');
     }
    public function selectAllGuests()
    {
        $guests = Guest::all();
        return $this->sendResponse($guests, 'Guests retrieved successfully');
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
