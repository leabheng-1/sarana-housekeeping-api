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
    $date = Carbon::now(); // You can replace this with any specific date

    $weekNumber = $date->weekOfYear;
    $roomType = $request->input('room_type');
    $roomNumber = $request->input('room_number');
    $roomStatus = $request->input('room_status');
    if ($roomType =='All') {
        $roomType = '';
    }
    if ($roomNumber =='All') {
        $roomNumber = '';
    }
    // Initialize an array to store the daily check-in, check-out, payment, and total payment counts.
    $weeklyReport = [];

    // Loop through each day of the week (Monday to Sunday) and calculate check-ins, check-outs, payments, and total payments.
    $currentDate = $startOfWeek;
    while ($currentDate <= $endOfWeek) {
        $checkInCount = DB::table('bookings')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->whereDate('bookings.checkin_date', $currentDate)
            ->when($roomType, function ($query) use ($roomType) {
                return $query->where('rooms.roomType', $roomType);
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
                return $query->where('rooms.roomType', $roomType);
            })
            ->when($roomNumber, function ($query) use ($roomNumber) {
                return $query->where('rooms.room_number', $roomNumber);
            })
            ->when($roomStatus, function ($query) use ($roomStatus) {
                return $query->where('rooms.room_status', $roomStatus);
            })
            ->count();
            $Single_Room = DB::table('bookings')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->whereDate('bookings.checkout_date', $currentDate)
            ->where('bookings.room_type', 'Single Room')
            ->when($roomType, function ($query) use ($roomType) {
                return $query->where('rooms.roomType', $roomType);
            })
            ->when($roomNumber, function ($query) use ($roomNumber) {
                return $query->where('rooms.room_number', $roomNumber);
            })
            ->when($roomStatus, function ($query) use ($roomStatus) {
                return $query->where('rooms.room_status', $roomStatus);
            })
            ->count();
            $Twin_Room = DB::table('bookings')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->whereDate('bookings.checkout_date', $currentDate)
            ->where('bookings.room_type', 'Twin Room')
            ->when($roomType, function ($query) use ($roomType) {
                return $query->where('rooms.roomType', $roomType);
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
                             ->where('rooms.roomType', $roomType);
            })
            ->when($roomNumber, function ($query) use ($roomNumber) {
                return $query->join('rooms as rooms1', 'bookings.room_id', '=', 'rooms1.id')
                             ->where('rooms1.room_number', $roomNumber);
            })
            ->when($roomStatus, function ($query) use ($roomStatus) {
                return $query->join('rooms', 'bookings.room_id', '=', 'rooms.id')
                             ->where('rooms.room_status', $roomStatus);
            })
            ->sum('payments.charges');

        $weeklyReport[date('l', strtotime($currentDate))] = [
            'date' => $currentDate->format('Y-m-d'),
            'check_in' => $checkInCount,
            'check_out' => $checkOutCount,
            'payment' => $payment,
            'single_room' =>$Single_Room,
            'twin_room'=>$Twin_Room,
        ];
        
        // $weeklyReport['numofWeek'] = $weekNumber; 
        $totalPayment = $payment + $payment ;
        // Move to the next day.
        $currentDate->addDay();
        

if ($request->has('format')) {
    if ($request->input('format') == 'PDF') {
        $dataForView = $weeklyReport;  // or $weeklyReport, depending on your needs
        $pdf = PDF::loadView('weekly', compact('dataForView'));
    
        // Download the PDF
        return $pdf->download('weekly_report.pdf');
    }elseif ($request->input('format') == 'excel'){
        $daily = $weeklyReport;
        return Excel::download(new BookingsExport($daily), 'bookings.xlsx');
    }elseif ($request->input('format') == 'sql'){
        $daily = $weeklyReport->toSql();
        

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
    }

    return $this->sendResponse($weeklyReport, 'Weekly report retrieved successfully');
}
public function monthlyReport(Request $request)
{
    // Assuming you have these parameters in your request
    $roomType = $request->input('room_type');
    $roomNumber = $request->input('room_number');
    $roomStatus = $request->input('room_status');

    // Array representing all months of the year
    $allMonths = range(1, 12);

    // Query to get the count of check-ins for each month
    $checkInCountsPerMonth = $this->getBookingCountsPerMonth($roomType, $roomNumber, $roomStatus, 'checkin_date');

    // Query to get the count of check-outs for each month
    $checkOutCountsPerMonth = $this->getBookingCountsPerMonth($roomType, $roomNumber, $roomStatus, 'checkout_date');

    // Query to get the sum of payments for each month
    $paymentSumPerMonth = $this->getPaymentSumPerMonth($roomType, $roomNumber, $roomStatus);
    $getBookingTypeCountsPerMonth = $this->getPaymentSumPerMonth($roomType, $roomNumber, $roomStatus);
    // Create an associative array for check-in counts per month
    $checkInCounts = $checkInCountsPerMonth->pluck('check_in_count', 'month')->all();

    // Create an associative array for check-out counts per month
    $checkOutCounts = $checkOutCountsPerMonth->pluck('check_out_count', 'month')->all();
$singleRoomCounts = $this->getBookingTypeCountsPerMonth('Single Room', $roomNumber, $roomStatus, 'checkout_date');
$twinRoomCounts = $this->getBookingTypeCountsPerMonth('Twin Room', $roomNumber, $roomStatus, 'checkout_date');    

    // Create an associative array for payment sum per month
    $paymentSum = $paymentSumPerMonth->pluck('payment_sum', 'month')->all();
    $SigleRoomCountsPerMonth = $singleRoomCounts->pluck('single_room', 'month')->all();
    


    // Merge check-in, check-out, and payment sums with all months, fill 0 for months with no data
    $monthlyReport = array_map(function ($month) use ($checkInCounts, $checkOutCounts, $paymentSum) {
        return [
            'month' => Carbon::createFromDate(null, $month, null)->format('F'),
            'check_in_count' => isset($checkInCounts[$month]) ? $checkInCounts[$month] : 0,
            'check_out_count' => isset($checkOutCounts[$month]) ? $checkOutCounts[$month] : 0,
            'single_room_count' => isset($SigleRoomCountsPerMonth[$month]) ? $SigleRoomCountsPerMonth[$month] : 0,
            'payment_sum' => isset($paymentSum[$month]) ? $paymentSum[$month] : 0,
        ];
    }, $allMonths);

    return $this->sendResponse($monthlyReport, 'Monthly report retrieved successfully');
}

// Helper function to get booking counts per month
private function getBookingCountsPerMonth($roomType, $roomNumber, $roomStatus, $dateColumn)
{
    return DB::table('bookings')
        ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
        ->when($roomType, function ($query) use ($roomType) {
            return $query->where('rooms.roomType', $roomType);
        })
        ->when($roomNumber, function ($query) use ($roomNumber) {
            return $query->where('rooms.room_number', $roomNumber);
        })
        ->when($roomStatus, function ($query) use ($roomStatus) {
            return $query->where('rooms.room_status', $roomStatus);
        })
        ->select(
            DB::raw("MONTH(bookings.$dateColumn) as month"),
            DB::raw('COUNT(*) as check_in_count'),
            DB::raw('COUNT(*) as check_out_count')
        )
        ->groupBy(DB::raw("MONTH(bookings.$dateColumn)"))
        ->get();
}
private function getBookingTypeCountsPerMonth($roomType, $roomNumber, $roomStatus, $dateColumn)
{
    return DB::table('bookings')
        ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
        ->where('bookings.room_type', $roomType)
        ->when($roomType, function ($query) use ($roomType) {
            return $query->where('rooms.roomType', $roomType);
        })
        ->when($roomNumber, function ($query) use ($roomNumber) {
            return $query->where('rooms.room_number', $roomNumber);
        })
        ->when($roomStatus, function ($query) use ($roomStatus) {
            return $query->where('rooms.room_status', $roomStatus);
        })
        ->select(
            DB::raw("MONTH(bookings.checkout_date) as month"),
            DB::raw('COUNT(*) as single_room')
        )
        ->groupBy(DB::raw("MONTH(bookings.checkout_date)"))
        ->get();
}

// Helper function to get payment sum per month
private function getPaymentSumPerMonth($roomType, $roomNumber, $roomStatus)
{
    return DB::table('payments')
        ->join('bookings', 'payments.id', '=', 'bookings.payment_id')
        ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
        ->when($roomType, function ($query) use ($roomType) {
            return $query->where('rooms.roomType', $roomType);
        })
        ->when($roomNumber, function ($query) use ($roomNumber) {
            return $query->where('rooms.room_number', $roomNumber);
        })
        ->when($roomStatus, function ($query) use ($roomStatus) {
            return $query->where('rooms.room_status', $roomStatus);
        })
        ->select(
            DB::raw('MONTH(bookings.checkout_date) as month'),
            DB::raw('SUM(payments.payment) as payment_sum')
        )
        ->groupBy(DB::raw('MONTH(bookings.checkout_date)'))
        ->get();
}

public function monthly(Request $request) {
    // Calculate the start of the current month using Carbon.
    $startOfMonth = Carbon::now()->startOfMonth(); // First day of the month

    $roomType = $request->input('room_type');
    $roomNumber = $request->input('room_number');
    $roomStatus = $request->input('room_status');
    if ($roomType =='All') {
        $roomType = '';
    }
    if ($roomNumber =='All') {
        $roomNumber = '';
    }
    $weeklyReport = DB::table('bookings')
        ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
        ->join('payments', 'bookings.payment_id', '=', 'payments.id')
        ->when($roomType, function ($query) use ($roomType) {
            return $query->where('rooms.roomType', $roomType);
        })
        ->when($roomNumber, function ($query) use ($roomNumber) {
            return $query->where('rooms.room_number', $roomNumber);
        })
        ->when($roomStatus, function ($query) use ($roomStatus) {
            return $query->where('rooms.room_status', $roomStatus);
        })
        ->whereBetween('bookings.checkin_date', [$startOfMonth, Carbon::now()])
        ->groupBy(DB::raw('WEEK(bookings.checkin_date, 1)')) // 1 as the mode to get weeks starting from Monday
        ->selectRaw('WEEK(bookings.checkin_date, 1) as week, 
                     count(*) as check_in, 
                     SUM(CASE WHEN bookings.checkout_date BETWEEN ? AND ? THEN 1 ELSE 0 END) as check_out,
                     SUM(payments.charges) as summary_sum_payment,
                     SUM(CASE WHEN rooms.roomType = "Single Room" THEN 1 ELSE 0 END) as single_room,
                     SUM(CASE WHEN rooms.roomType = "Twin Room" THEN 1 ELSE 0 END) as twin_room', 
                     [$startOfMonth, Carbon::now()])
        ->get();

    // Fill in missing weeks with zero counts
    $allWeeks = [];
    $currentWeek = Carbon::now()->week;

    foreach ($weeklyReport as $report) {
        $allWeeks[$report->week] = $report;
    }

    for ($i = $currentWeek; $i >= $currentWeek - 3; $i--) {
        if (!isset($allWeeks[$i])) {
            $allWeeks[$i] = (object) [
                'week' => $i,
                'check_in' => 0,
                'check_out' => 0,
                'summary_sum_payment' => 0,
                'single_room' => 0,
                'twin_room' => 0,
            ];
        }
    }

    // Sort the results by week
    ksort($allWeeks);

    // Reset keys to start from 
    $allWeeks = array_values($allWeeks);
    if ($request->has('format')) {
        if ($request->input('format') == 'PDF') {
            $dataForView = $allWeeks;  // or $weeklyReport, depending on your needs
            $pdf = PDF::loadView('month', compact('dataForView'));
        
            // Download the PDF
            return $pdf->download('monthlyReport.pdf');
        }elseif ($request->input('format') == 'excel'){
            $daily = $allWeeks;
            return Excel::download(new BookingsExport($daily), 'bookings.xlsx');
        }elseif ($request->input('format') == 'sql'){
            $daily = $weeklyReport->toSql();
            
    
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
    return $this->sendResponse($allWeeks, 'Weekly report retrieved successfully');
}
 
public function yearly(Request $request) {
    // Calculate the start of the current year using Carbon.
    $startOfYear = Carbon::now()->startOfYear(); // First day of the year

    $roomType = $request->input('room_type');
    $roomNumber = $request->input('room_number');
    $roomStatus = $request->input('room_status');
    if ($roomType =='All') {
        $roomType = '';
    }
    if ($roomNumber =='All') {
        $roomNumber = '';
    }
    $yearlyReport = DB::table('bookings')
        ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
        ->join('payments', 'bookings.payment_id', '=', 'payments.id')
        ->when($roomType, function ($query) use ($roomType) {
            return $query->where('rooms.roomType', $roomType);
        })
        ->when($roomNumber, function ($query) use ($roomNumber) {
            return $query->where('rooms.room_number', $roomNumber);
        })
        ->when($roomStatus, function ($query) use ($roomStatus) {
            return $query->where('rooms.room_status', $roomStatus);
        })
        ->whereYear('bookings.checkin_date', '=', Carbon::now()->year)
        ->groupBy(DB::raw('MONTH(bookings.checkin_date)'))
        ->selectRaw('MONTH(bookings.checkin_date) as month_number,
                     COUNT(*) as check_in,
                     SUM(CASE WHEN bookings.checkout_date BETWEEN ? AND ? THEN 1 ELSE 0 END) as check_out,
                     SUM(payments.charges) as summary_sum_payment,
                     SUM(CASE WHEN rooms.roomType = "Single Room" THEN 1 ELSE 0 END) as single_room,
                     SUM(CASE WHEN rooms.roomType = "Twin Room" THEN 1 ELSE 0 END) as twin_room',
                     [$startOfYear, Carbon::now()])
        ->get();

    // Fill in missing months with zero counts and get month names
    $allMonths = [];
    $currentMonth = Carbon::now()->month;

    foreach ($yearlyReport as $report) {
        $monthName = Carbon::create()->month($report->month_number)->format('F');
        $allMonths[$report->month_number] = (object) [
            'month' => $monthName,
            'check_in' => $report->check_in,
            'check_out' => $report->check_out,
            'summary_sum_payment' => $report->summary_sum_payment,
            'single_room' => $report->single_room,
            'twin_room' => $report->twin_room,
        ];
    }

    for ($i = 1; $i <= 12; $i++) {
        if (!isset($allMonths[$i])) {
            $monthName = Carbon::create()->month($i)->format('F');
            $allMonths[$i] = (object) [
                'month' => $monthName,
                'check_in' => 0,
                'check_out' => 0,
                'summary_sum_payment' => 0,
                'single_room' => 0,
                'twin_room' => 0,
            ];
        }
    }

    // Sort the results by month
    ksort($allMonths);

    // Reset keys to start from 0
    $allMonths = array_values($allMonths);
    if ($request->has('format')) {
        if ($request->input('format') == 'PDF') {
            $dataForView = $allMonths;  // or $weeklyReport, depending on your needs
            $pdf = PDF::loadView('yearly', compact('dataForView'));
        
            // Download the PDF
            return $pdf->download('yearlyReport.pdf');
        }elseif ($request->input('format') == 'excel'){
            $daily = $allMonths;
            return Excel::download(new BookingsExport($daily), 'bookings.xlsx');
        }elseif ($request->input('format') == 'sql'){
            $daily = $allMonths->toSql();
            
    
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
    return $this->sendResponse($allMonths, 'Yearly report retrieved successfully');
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
    if ($request->has('booking_id')) {
        $bookingId = $request->input('booking_id');
        $query->where('bookings.id', $bookingId);
    }
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $query->whereBetween('bookings.checkin_date', [$startDate, $endDate])
        ->orWhereDate('bookings.checkout_date', '=', [$startDate, $endDate]);
    } else {
        // If start_date or end_date is not provided or is empty, default to today
        $today = now()->toDateString();
        $query->whereDate('bookings.checkin_date', '=', $today)
        ->orWhereDate('bookings.checkout_date', '=', $today);
        ;
    }
    

    if ($request->has('room_type') && $request->input('room_type') != 'All' ) {
        $roomType = $request->input('room_type');
        $query->where('rooms.roomtype', $roomType);
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
   
        return $pdf->stream('sample.pdf');
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
