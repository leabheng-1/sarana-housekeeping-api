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
        <h1>Yearly Report</h1>
        <div>
        </div>
        <?php
    $currentDate = date('Y-m-d');

?>    

        </div>
    <div class="end-positioned-div">

    Print Date : {{$currentDate}}
</div>
    <table border="1">
    <thead>
    <tr>
        <th>Week</th>
        <th>Check-In</th>
        <th>Check-Out</th>
        <th>Single Room</th>
        <th>Twin Room</th>
        <th>payment</th>
    </tr>
</thead>

<tbody>

@foreach ($dataForView as $week)
    <tr>
        <td>{{ $week->month }}</td>
        <td>{{ $week->check_in }}</td>
        <td>{{ $week->check_out }}</td>
        <td>{{ $week->single_room }}</td>
        <td>{{ $week->twin_room }}</td>
        <td>{{ $week->summary_sum_payment }}</td>
    </tr>
@endforeach

   
</tbody>

    </table>
<div>

</div>

</body>
</html>
