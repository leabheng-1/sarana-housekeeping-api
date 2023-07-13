<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Payment;
use App\Http\Controllers\API\FunctionValidatorAndInsert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends BaseController
{
    /**
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    
     public function insert(Request $request)
     {
         $payment_input = new FunctionValidatorAndInsert(); 
         $validator = $payment_input->paymentValidator($request);
         if ($validator->fails()) {
             return $this->sendError('Validation Error', $validator->errors(), 400);
         }
         $operation = 'insert';
         $payment = $payment_input->paymentInsert($request, $operation);
         return $this->sendResponse($payment, 'payment inserted successfully');
     }
    public function selectAllPayment()
    {
        $payments = payment::all();
        return $this->sendResponse($payments, 'payments retrieved successfully');
    }
    public function delete($id)
    {
        $payment = payments::findOrFail($id);
        $payment->delete();
        return $this->sendResponse(null, 'payment deleted successfully');
    }
    public function update(Request $request, $id)
    {
        $payment_update = new FunctionValidatorAndInsert(); 
        $request->merge(['id' =>$id]);
        $operation = 'update';
        $payment = $payment_update->paymentInsert($request, $operation );
        return $this->sendResponse($payment, 'payment updated successfully');
    }
    public function find($keyword)
    {
        $payments = payment::where('id', 'LIKE', '%' . $keyword . '%')
            ->orWhere('name', 'LIKE', '%' . $keyword . '%')
            ->get();

        if ($payments->isNotEmpty()) {
            return $this->sendResponse($payments, 'ture');
        } else {
            return $this->sendResponse($payments, 'Record not found');
        }
    }
}
