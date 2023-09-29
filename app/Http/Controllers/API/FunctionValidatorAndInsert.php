<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Rooms;
use App\Models\Guest;
use App\Models\Housekeeping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FunctionValidatorAndInsert
{
    // Insert Guest
    public function guestInsert(Request $request, $operation)
    {
        if ($operation === 'update' && $request->has('id')) {
            $guest = Guest::findOrFail($request->input('id'));
        } else {
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
            'phone_number' => 'required',
        ]);

        return $validator;
    }

    public function bookingStayValidator($request)
    {
        $validator = Validator::make($request->all(), [
            'room_type' => 'required',
        ]);

        return $validator;
    }

    public function paymentValidator($request)
    {
        $validator = Validator::make($request->all(), [
            'payment' => 'nullable',
        ]);

        return $validator;
    }
    public function roomValidator($request)
    {
        $validator = Validator::make($request->all(), [
            'note' => 'nullable',
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

    // Define an array of fields and their corresponding request input names
    $fields = [
        'room_number' => 'room_number',
        'room_status' => 'room_status',
        'roomtype' => 'roomtype',
        'floor' => 'floor',
        'room_rate' => 'room_rate',
        'note' => 'note',
        'air_method' => 'air_method',
    ];

    // Loop through the fields and update the room object if the input is not null
    foreach ($fields as $field => $inputName) {
        $inputValue = $request->input($inputName);
        if ($inputValue !== null) {
            $rooms->$field = $inputValue;
        }
    }

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

        $payment->payment = $request->input('payment');
        $payment->charges = $request->input('charges');
        $payment->balance = $request->input('balance');
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
            $request->merge(['id' => $booking->guest_id]);
            $guestInsert = $this->guestInsert($request, $operation);
            $request->merge(['id' => $booking->payment_id]);
            $paymentInsert = $this->paymentInsert($request, $operation);
        } else {
            $booking = new Booking();
            $guestInsert = $this->guestInsert($request, $operation);
            $paymentInsert = $this->paymentInsert($request, $operation);
        }
        $guest = $guestInsert;
        $payment = $paymentInsert;
        $room_id_from_request = $request->input('room_id');
        if ($room_id_from_request !== null) {
            $booking->room_id = $room_id_from_request;
        }
        $booking->room_type = $request->input('room_type');
        $booking->booking_status = $request->input('booking_status') ?? false;
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
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone_number' => 'required',
            'room_type' => 'required',
            'checkin_date' => 'required|date',
            'checkout_date' => 'required|date',

        ]);

        return $validator;
    }

    // 
    public function housekeepingInsert(Request $request, $operation)
    {
        if ($operation === 'update' && $request->has('id')) {
            $housekeeping = Housekeeping::findOrFail($request->input('id'));
        } else {
            $housekeeping = new Housekeeping();
        }

        $room_id_from_request = $request->input('room_id');
        if ($room_id_from_request !== null) {
            $housekeeping->room_id = $request->input('room_id');
        }
        $housekeeping->housekeeper = $request->input('housekeeper');
        $housekeeping_status = $request->input('room_id');
        if($housekeeping_status != null){
            
        }
        $housekeeping->housekeeping_status = $request->input('housekeeping_status');
        $housekeeping->date = $request->input('date', now()->format('Y-m-d'));
        $housekeeping->save();

        return $housekeeping;
    }

    // Housekeeping Validator
    public function housekeepingValidator(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_id' => 'required|exists:rooms,id',
            'housekeeper' => 'required',
            'housekeeping_status' => 'required',

        ]);

        return $validator;
    }
}