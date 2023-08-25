<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Housekeeping;
use App\Http\Controllers\API\FunctionValidatorAndInsert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HousekeepingController extends BaseController
{
    public function insert(Request $request)
    {
        $housekeeping_input = new FunctionValidatorAndInsert();
        $validator = $housekeeping_input->housekeepingValidator($request);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors(), 400);
        }

        $operation = 'insert';
        $housekeeping = $housekeeping_input->housekeepingInsert($request, $operation);

        return $this->sendResponse($housekeeping, 'Housekeeping record inserted successfully');
    }

    public function selectAllHousekeeping()
    {
        $housekeeping = Housekeeping::all();
        return $this->sendResponse($housekeeping, 'Housekeeping records retrieved successfully');
    }

    public function delete($id)
    {
        $housekeeping = Housekeeping::findOrFail($id);
        $housekeeping->delete();

        return $this->sendResponse(null, 'Housekeeping record deleted successfully');
    }

    public function update(Request $request, $id)
    {
        $housekeeping_update = new FunctionValidatorAndInsert();
        $request->merge(['id' => $id]);
        $operation = 'update';
        $housekeeping = $housekeeping_update->housekeepingInsert($request, $operation);

        return $this->sendResponse($housekeeping, 'Housekeeping record updated successfully');
    }

    public function find($keyword)
    {
        $housekeeping = Housekeeping::where('id', 'LIKE', '%' . $keyword . '%')
            ->orWhere('housekeeper', 'LIKE', '%' . $keyword . '%')
            ->orWhere('housekeeping_status', 'LIKE', '%' . $keyword . '%')
            ->orWhere('date', 'LIKE', '%' . $keyword . '%')
            ->get();

        if ($housekeeping->isNotEmpty()) {
            return $this->sendResponse($housekeeping, 'Housekeeping records found');
        } else {
            return $this->sendResponse($housekeeping, 'No housekeeping records found');
        }
    }
}
