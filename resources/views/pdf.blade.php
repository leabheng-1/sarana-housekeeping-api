<!DOCTYPE html>
<html>
<head>
    <title>Report</title>
</head>
<style>
    body{
        font-size:10px;
    }
    table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>
<body>
<?php
    $totalGuests = 0; // Initialize the total guests variable
    $totalBalance = 0; 

    foreach ($daily as $booking) {
        $totalGuests += $booking->adults + $booking->child;
        $totalBalance += $booking->balance + $booking->extra_charge;
    }
?>    
    <h1>Booking Report</h1>
    <table border="1">
    <thead>
    <tr>
        <th>ID</th>
        <th>Guest ID</th>
        <th>Name</th>
        <th>Adults</th>
        <th>Child</th>
        <th>Check-In Date</th>
        <th>Check-Out Date</th>
        <th>Room Type</th>
        
        <th>Room Number</th>
        
        <th>Balance</th>
    </tr>
</thead>

<tbody>
    @foreach ($daily as $booking)
        <tr>
            <td>{{ $booking->id }}</td>
            <td>{{ $booking->guest_id }}</td>
            <td>{{ $booking->name }}</td>
            <td>{{ $booking->adults }}</td>
            <td>{{ $booking->child }}</td>
            <td>{{ $booking->checkin_date }}</td>
            <td>{{ $booking->checkout_date }}</td>
            <td>{{ $booking->room_type }}</td>
            <td>{{ $booking->room_number }}</td>
            <td>{{ $booking->balance + $booking->extra_charge }}</td>
        </tr>
    @endforeach
    <tr>
<td>  {{$totalGuests}} </td>
<td>  {{$totalBalance}} </td>
</tr>
</tbody>

    </table>
</body>
</html>
