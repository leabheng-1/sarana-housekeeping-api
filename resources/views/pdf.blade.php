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
        <img class="logo" src="https://lh3.googleusercontent.com/pw/ADCreHdTq9J3yXVSXAZn51lCYe7ZWjIct7qpQFRfxrHnAnSmh8DVdY-wKumdRHRkr_ZwOfkIkKba07qN6INRWz1EPJFnD5HLqt8TSsaveAoHLu03Jfi2eOY3fEXEhWK8uy6yU6IXWQzPQlSBukftt6eoJP51DVhhO5GCmp9LsiYwqbKeUynQHThdkfcbO-e9s_KBc4dT1dOHU6_mapTDcfxdZW7Hdj8-nDt7tx43BS3YAhg5z231wxZymrYPq0BFqNnhcfucR9Bgyaxh3FzZ_7Iy_Cy94qh9zjB3jdDq5kXlNKGLSn-jcORNpnaGKKhFjgIsZGXQ7m8nfJuBrg021C0JQl1_cVwPgh-BMotos0AwOdF1_3v1Gj0PYvjqW1bstBiHb84X6I-aW6gmYMYkZOX1X2DezGUl-3G3Ec2BjwCNLMu3MkZUdGIislPwy2QD-zthKCNGKVtrGGeojP4oMOYRuC-8OHfqfRmcwi3vZewfIRrVFz5Cs4SwitcLlIIO-qXZEOCylSE9bEVVikZoSrMESb6Gv4R-41usJU2bPrNRQ3G5Z_FDqTu_vEzMV8m96Opu7j5G2DLhor0q25gE83pUcfYCToC6EJDZfaFUcbfYwP6IeyVr4QZipwKTK_E1NurTGdVSt_b8y11C4uPd953yfH7VWp-9jBAbF1_MmUQ-zBDv5I9PbPXs2cIL-BmUllqd8q-aN46fueJ3kqHBjrv83NZK1p7Sn7txC5d30kGxYpIV5v5doXzduNKLXSIE2FumLkKp0B9O3ZwjRX_WtSy35WCgM0MmTiOA8AJPGdTo4zXXM7h8aWn6DvFKcWqryg2-JitFzrgGp-4KmD7gYJUlf6-lEWB-dndtb5eDifvxXk7y_ATsqWxJ2UakViEIQVGQXec_S0-B9gHOQB7vOTv4CLQeev24IarwdgOEpXQ=w903-h923-s-no-gm?authuser=0" alt="Hotel Logo">
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
        $totalBalance += $booking->payment;
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
            <td>{{ $booking->payment }} $</td>
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
