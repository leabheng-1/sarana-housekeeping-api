<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unique Palm AngKor Villa Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
     
            margin: 0;
            padding: 0;
        }
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
        .container {      
            text-align: center;
            max-width: 800px;
            line-height: 1.4;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 20px;
        }
        .hotel-name {
            font-size: 24px;
            font-weight: bold;
        }
        .end-positioned-div {
            width: 100%;
            text-align: right;
            padding: 10px;
            margin-right:10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <img class="logo" src="https://i.postimg.cc/yYHG4rxG/logo.jpg" alt="Hotel Logo">
        <h1 class="hotel-name">Unique Palm AngKor Villa</h1>
        <h1>Report</h1>
        <div>

        </div>
        <?php
    $totalGuests = 0; // Initialize the total guests variable
    $totalBalance = 0; 
    $currentDate = date('Y-m-d');
    foreach ($daily as $booking) {
        $totalGuests += $booking->adults + $booking->child;
        $totalBalance += $booking->charges;
    }
?>    

        </div>
    <div class="end-positioned-div">

    Print Date : {{$currentDate}}
</div>
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
            <td>{{ $booking->charges }} $</td>
        </tr>
    @endforeach
    <tr style="background-color:Gray;">
<td colspan="8">Total</td>
<td>  {{$totalGuests}} </td>
<td>  {{$totalBalance}} $ </td>
</tr>
</tbody>

    </table>
<div>

</div>

</body>
</html>
