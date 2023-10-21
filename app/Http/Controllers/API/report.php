<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Guest;
use App\Models\Rooms;
use Illuminate\Http\Request;
use App\Http\Controllers\API\FunctionValidatorAndInsert;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PDF;
use App\Exports\BookingsExport;
use Maatwebsite\Excel\Facades\Excel;
class report extends BaseController
{

    public function weekly(Request $request) {
    // Calculate the start and end of the current week using Carbon.
    $startOfWeek = Carbon::now()->startOfWeek(); // Monday
    $endOfWeek = Carbon::now()->endOfWeek(); // Sunday

    $roomType = $request->input('room_type');
    $roomNumber = $request->input('room_number');
    $roomStatus = $request->input('room_status');

    // Initialize an array to store the daily check-in, check-out, payment, and total payment counts.
    $weeklyReport = [];

    // Loop through each day of the week (Monday to Sunday) and calculate check-ins, check-outs, payments, and total payments.
    $currentDate = $startOfWeek;
    while ($currentDate <= $endOfWeek) {
        $checkInCount = DB::table('bookings')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->whereDate('bookings.checkin_date', $currentDate)
            ->when($roomType, function ($query) use ($roomType) {
                return $query->where('rooms.room_type', $roomType);
            })
            ->when($roomNumber, function ($query) use ($roomNumber) {
                return $query->where('rooms.room_number', $roomNumber);
            })
            ->when($roomStatus, function ($query) use ($roomStatus) {
                return $query->where('rooms.room_status', $roomStatus);
            })
            ->count();

        $checkOutCount = DB::table('bookings')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->whereDate('bookings.checkout_date', $currentDate)
            ->when($roomType, function ($query) use ($roomType) {
                return $query->where('rooms.room_type', $roomType);
            })
            ->when($roomNumber, function ($query) use ($roomNumber) {
                return $query->where('rooms.room_number', $roomNumber);
            })
            ->when($roomStatus, function ($query) use ($roomStatus) {
                return $query->where('rooms.room_status', $roomStatus);
            })
            ->count();

        // Calculate payments and total payments based on your application's logic.

        $payment = DB::table('payments')
            ->join('bookings', 'payments.id', '=', 'bookings.payment_id')
            ->whereDate('bookings.checkout_date', $currentDate)
            ->when($roomType, function ($query) use ($roomType) {
                return $query->join('rooms', 'bookings.room_id', '=', 'rooms.id')
                             ->where('rooms.room_type', $roomType);
            })
            ->when($roomNumber, function ($query) use ($roomNumber) {
                return $query->join('rooms', 'bookings.room_id', '=', 'rooms.id')
                             ->where('rooms.room_number', $roomNumber);
            })
            ->when($roomStatus, function ($query) use ($roomStatus) {
                return $query->join('rooms', 'bookings.room_id', '=', 'rooms.id')
                             ->where('rooms.room_status', $roomStatus);
            })
            ->sum('payments.payment');

        $weeklyReport[date('l', strtotime($currentDate))] = [
            'date' => $currentDate->format('Y-m-d'),
            'check_in' => $checkInCount,
            'check_out' => $checkOutCount,
            'payment' => $payment,
        ];
        $totalPayment = $payment + $payment ;
        // Move to the next day.
        $currentDate->addDay();
        
    }

    return $this->sendResponse($weeklyReport, 'Weekly report retrieved successfully');
}

public function monthly(Request $request) {
    // Calculate the start and end of the current month using Carbon.
    $startOfMonth = Carbon::now()->startOfMonth(); // First day of the month
    $endOfMonth = Carbon::now()->endOfMonth();     // Last day of the month

    $roomType = $request->input('room_type');
    $roomNumber = $request->input('room_number');
    $roomStatus = $request->input('room_status');

    // Initialize an array to store the weekly check-in, check-out, payment, and total payment counts.
    $monthlyReport = [];

    // Loop through each day of the month and calculate check-ins, check-outs, payments, and total payments.
    $currentDate = $startOfMonth;
    $currentWeek = 1; // Initialize the week of the month to 1.

    while ($currentDate <= $endOfMonth) {
        // Calculate the week of the month.
        $weekOfMonth = ceil($currentDate->day / 7);

        if ($weekOfMonth > $currentWeek) {
            // We've moved to a new week of the month. Initialize counts for the new week.
            $currentWeek = $weekOfMonth;
            $checkInCount = 0;
            $checkOutCount = 0;
        }

        $checkInCount = DB::table('bookings')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->whereYear('bookings.checkin_date', $currentDate->year)
            ->whereMonth('bookings.checkin_date', $currentDate->month)
            ->when($roomType, function ($query) use ($roomType) {
                return $query->where('rooms.room_type', $roomType);
            })
            ->when($roomNumber, function ($query) use ($roomNumber) {
                return $query->where('rooms.room_number', $roomNumber);
            })
            ->when($roomStatus, function ($query) use ($roomStatus) {
                return $query->where('rooms.room_status', $roomStatus);
            })
            ->count();

        $checkOutCount = DB::table('bookings')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->whereYear('bookings.checkout_date', $currentDate->year)
            ->whereMonth('bookings.checkout_date', $currentDate->month)
            ->when($roomType, function ($query) use ($roomType) {
                return $query->where('rooms.room_type', $roomType);
            })
            ->when($roomNumber, function ($query) use ($roomNumber) {
                return $query->where('rooms.room_number', $roomNumber);
            })
            ->when($roomStatus, function ($query) use ($roomStatus) {
                return $query->where('rooms.room_status', $roomStatus);
            })
            ->count();

        // Calculate payments and total payments based on your application's logic.

        $payment = DB::table('payments')
            ->join('bookings', 'payments.id', '=', 'bookings.payment_id')
            ->whereYear('bookings.checkout_date', $currentDate->year)
            ->whereMonth('bookings.checkout_date', $currentDate->month)
            ->when($roomType, function ($query) use ($roomType) {
                return $query->join('rooms', 'bookings.room_id', '=', 'rooms.id')
                             ->where('rooms.room_type', $roomType);
            })
            ->when($roomNumber, function ($query) use ($roomNumber) {
                return $query->join('rooms', 'bookings.room_id', '=', 'rooms.id')
                             ->where('rooms.room_number', $roomNumber);
            })
            ->when($roomStatus, function ($query) use ($roomStatus) {
                return $query->join('rooms', 'bookings.room_id', '=', 'rooms.id')
                             ->where('rooms.room_status', $roomStatus);
            })
            ->sum('payments.payment');

        // Store the counts in the weekly report under the current week.
        $monthlyReport[$currentWeek] = [
            'start_date' => $currentDate->format('Y-m-d'),
            'end_date' => $currentDate->format('Y-m-d'),
            'check_in' => $checkInCount,
            'check_out' => $checkOutCount,
            'payment' => $payment,
        ];

        // Move to the next day.
        $currentDate->addDay();
    }

    return $this->sendResponse($monthlyReport, 'Monthly report retrieved successfully');
}
 

    
 public function daily(Request $request){
    $query = DB::table('bookings')
        ->join('guests', 'bookings.guest_id', '=', 'guests.id')
        ->join('payments', 'bookings.payment_id', '=', 'payments.id')
        ->join('rooms', 'bookings.room_id', '=', 'rooms.id');

    if ($request->has('room_status')) {
        $roomStatus = $request->input('room_status');
        $query->where('rooms.room_status', $roomStatus);
    }

    if (($request->has('start_date') && $request->has('end_date'))&&($request->input('start_date') != '' && $request->input('end_date') != '')) {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $query->whereBetween('bookings.checkin_date', [$startDate, $endDate]);
    }

    if ($request->has('room_type') && $request->input('room_type') != 'All' ) {
        $roomType = $request->input('room_type');
        $query->where('bookings.room_type', $roomType);
    }

    if ($request->has('room_number') && $request->input('room_number') != 'All' ) {
        $roomNumber = $request->input('room_number');
        $query->where('rooms.room_number', $roomNumber);
    }
    
    


if ($request->has('format')) {
    if ($request->input('format') == 'PDF') {
        $daily = $query->select('bookings.*', 'rooms.*', 'guests.*', 'payments.*')->get();
        $currentDate = date('Y-m-d'); // Format the current date as needed
        $filename = $currentDate . '_report.pdf';
        $pdf = PDF::loadView('pdf', compact('daily'));
   
    return $pdf->download($filename);
    }elseif ($request->input('format') == 'excel'){
        $daily = $query->select('bookings.*', 'rooms.*', 'guests.*', 'payments.*')->get();
        return Excel::download(new BookingsExport($daily), 'bookings.xlsx');
    }elseif ($request->input('format') == 'sql'){
        $daily = $query->select('bookings.*', 'rooms.*', 'guests.*', 'payments.*')->get();
        
// Get the SQL query
$sql = $query->toSql();

// Optionally, you can bind the query parameters to the SQL
$bindings = $query->getBindings();

// Create a text file containing the SQL query
$sqlContent = $sql . PHP_EOL . 'Bindings: ' . json_encode($bindings);
$fileName = 'query.sql';

// Provide the SQL file as a downloadable response
return response($sqlContent)
    ->header('Content-Disposition', "attachment; filename=$fileName")
    ->header('Content-Type', 'text/plain');
    }
  
    
}
else{
    $daily = $query->select('bookings.*', 'rooms.*', 'guests.*', 'payments.*')->get();
    return $this->sendResponse($daily, 'Booking(s) retrieved successfully');
}

    
}

public function monthlyCharge(Request $request) {
    $year = date('Y'); // Get the current year

    // Create a list of all months
    $months = [];
    for ($month = 1; $month <= 12; $month++) {
        $months[] = [
            'month' => $month,
            'month_name' => date('M', mktime(0, 0, 0, $month, 1, $year)),
        ];
    }

    // Retrieve charges for the current year
    $charges = DB::table('bookings')
        ->join('guests', 'bookings.guest_id', '=', 'guests.id')
        ->join('payments', 'bookings.payment_id', '=', 'payments.id')
        ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
        ->whereYear('bookings.checkin_date', $year)
        ->groupBy(DB::raw('MONTH(bookings.checkin_date)'))
        ->selectRaw('MONTH(bookings.checkin_date) as month, COALESCE(SUM(payments.charges), 0) as total_charge')
        ->get(); // Execute the query here, not before

    // Merge charges with the list of months, filling in 0 for months with no charges
    $result = [];
    foreach ($months as $month) {
        $charge = $charges->where('month', $month['month'])->first();
        $result[] = [
            'month_name' => $month['month_name'],
            'total_charge' => $charge ? $charge->total_charge : 0,
        ];
    }

    return $this->sendResponse($result, 'Monthly charges for the current year retrieved successfully');
}
public function monthlyGuestCountByRoomType(Request $request) {
    $year = date('Y'); // Get the current year

    // Define your room types
    $roomTypes = ['Single Room', 'Twin Room'];

    // Create an empty array to store the results
    $result = [];

    foreach ($roomTypes as $roomType) {
        // Retrieve guest count (excluding no-show and cancel) for each room type for the entire year
        $guestCount = DB::table('bookings')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->whereYear('bookings.checkin_date', $year)
            ->where('bookings.room_type', $roomType)
            ->selectRaw('COUNT(bookings.guest_id) as guest_count')
            ->first(); // Use first() to get a single result
        // Add the room type and guest count to the result array
        $result[] = [
            $roomType => $guestCount->guest_count,
        ];
    }

    $noShowCount = DB::table('bookings')
        ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
        ->whereYear('bookings.checkin_date', $year)
        ->where('bookings.booking_status', 'No Show')
        ->count();

    // Retrieve cancel count for the entire year
    $cancelCount = DB::table('bookings')
        ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
        ->whereYear('bookings.checkin_date', $year)
        ->where('bookings.booking_status', 'Cancel')
        ->count();
    $booking[] = [
        'guest' => $result,
        'noShowCount' => $noShowCount,
        'cancelCount' => $cancelCount
    ];

    return $this->sendResponse($booking, 'Monthly guest counts (excluding no-show and cancel) for the current year by room type retrieved successfully');
}

}
