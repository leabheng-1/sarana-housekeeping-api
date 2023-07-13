<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Rooms;
use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FunctionValidatorAndInsert
{
    // Insert Guest
    public function guestInsert(Request $request, $operation)
    {
        if ($operation === 'update' && $request->has('id')) {
            $guest = Guest::findOrFail($request->input('id'));
        } else  {
            $guest = new Guest();
        }

        $guest->name = $request->input('name');
        $guest->gender = $request->input('gender');
        $guest->phone_number = $request->input('phone_number');
        $guest->email = $request->input('email');
        $guest->country = $request->input('country');
        $guest->dob = $request->input('dob');
        $guest->passport_number = $request->input('passport_number');
        $guest->card_id = $request->input('card_id');
        $guest->other_information = $request->input('other_information');
        $guest->save();

        return $guest;
    }

    // Guest Validator
    public function guestValidator(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'gender' => 'required',
            'phone_number' => 'required',
            'email' => 'required|email',
            'country' => 'required',
            'dob' => 'required|date',
            'passport_number' => 'nullable',
            'card_id' => 'nullable',
            'other_information' => 'nullable',
        ]);

        return $validator;
    }

    public function bookingStayValidator($request)
    {
        $validator = Validator::make($request->all(), [
            'room_id' => 'required',
            'is_cancel' => 'nullable',
            'cancel_date' => 'nullable|date',
            'arrival_date' => 'nullable|date',
            'departure_date' => 'nullable|date',
            'checkin_date' => 'nullable|date',
            'checkout_date' => 'nullable|date',
            'adults' => 'nullable',
            'child' => 'nullable',
            'created_by' => 'nullable',
            'note' => 'nullable',
            'fan' => 'nullable',
            'air_conditioner' => 'nullable',
            
        ]);

        return $validator;
    }

    public function paymentValidator($request)
    {
        $validator = Validator::make($request->all(), [
            'payment_percent' => 'nullable',
            'payment_status' => 'nullable',
            'extra_charge' => 'nullable',
            'item_extra_charge' => 'nullable',
        ]);

        return $validator;
    }
    public function roomValidator($request)
    {
        $validator = Validator::make($request->all(), [
            'room_number' => 'nullable',
            'room_status' => 'nullable',
            'housekeeping_status' => 'nullable',
            'roomtype' => 'nullable',
            'floor' => 'nullable',
            'room_rate' => 'nullable',
            'note' => 'nullable',
            'fan' => 'nullable',
            'air_conditioner' => 'nullable',
            'housekeeper' => 'nullable',
        ]);

        return $validator;
    }
    public function roomInsert(Request $request, $operation)
    {
        if ($operation === 'update' && $request->has('id')) {
            $rooms = rooms::findOrFail($request->input('id'));
        } else {
            $rooms = new rooms();
        }
        
        $rooms->room_number = $request->input('room_number');
        $rooms->room_status = $request->input('room_status');
        $rooms->housekeeping_status = $request->input('housekeeping_status');
        $rooms->roomtype = $request->input('roomtype');
        $rooms->floor = $request->input('floor');
        $rooms->room_rate = $request->input('room_rate');
        $rooms->note = $request->input('note');
        $rooms->fan = $request->input('fan');
        $rooms->air_conditioner = $request->input('air_conditioner');
        $rooms->housekeeper = $request->input('housekeeper');
        $rooms->save();
        return $rooms;
    }
    public function paymentInsert(Request $request, $operation)
    {
        if ($operation === 'update' && $request->has('id')) {
            $payment = Payment::findOrFail($request->input('id'));
        } else {
            $payment = new Payment();
        }
        
        $payment->payment_percent = $request->input('payment_percent');
        $payment->payment_status = $request->input('payment_status');
        $payment->extra_charge = $request->input('extra_charge');
        $payment->item_extra_charge = $request->input('item_extra_charge');
        $payment->save();
        return $payment;
    }
    public function bookingInsert(Request $request, $operation)
    {

       
        
        // Assign the guest and payment objects to variables
    
        
        if ($operation === 'update' && $request->has('id')) {
            $booking = Booking::findOrFail($request->input('id'));
            $request->merge(['id' =>$booking->guest_id ]);
            $guestInsert = $this->guestInsert($request, $operation);
            $request->merge(['id' =>$booking->payment_id ]);
            $paymentInsert = $this->paymentInsert($request, $operation);
        } else {
            $booking = new Booking();
            $guestInsert = $this->guestInsert($request, $operation);
            $paymentInsert = $this->paymentInsert($request, $operation);
        }
        $guest = $guestInsert;
        $payment = $paymentInsert;
        $booking->room_id = $request->input('room_id');
        $booking->is_cancel = $request->input('is_cancel') ?? false;
        $booking->cancel_date = $request->input('cancel_date');
        $booking->arrival_date = $request->input('arrival_date');
        $booking->checkin_date = $request->input('checkin_date');
        $booking->checkout_date = $request->input('checkout_date');
        $booking->adults = $request->input('adults') ?? 1;
        $booking->child = $request->input('child') ?? 0;
        $booking->created_by = $request->input('created_by') ?? 'null';
        $booking->note = $request->input('note') ?? 'null';
    
        // Assign payment and guest IDs
        $booking->payment_id = $payment->id;
        $booking->guest_id = $guest->id;
    
        $booking->save(); // Save the booking
    
        return $booking;        
    }
    
    // bookingValidator
    public function bookingValidator(Request $request)
    {
        $guestValidator = $this->guestValidator($request);
        $bookingStayValidator = $this->bookingStayValidator($request);
        $paymentValidator = $this->paymentValidator($request);
    
        $rules = array_merge(
            $guestValidator->getMessageBag()->toArray(),
            $bookingStayValidator->getMessageBag()->toArray(),
            $paymentValidator->getMessageBag()->toArray()
        );
    
        $validator = Validator::make($request->all(), $rules);
        return $validator;
    }
}
