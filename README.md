# Laravel Application Routes and Functionality

This document provides an overview of the routes and functionality implemented in our Laravel application.

# Cloning the Repository
 ```bash
 git clone git@github.com:leabheng-1/sarana-housekeeping-api.git
 ```bash
## API Base URL

You can access the API using the following base URL: [http://localhost:8000/api](http://localhost:8000/api)

## Guest Management

- **Insert Guest**: [POST /api/guests/insert](http://localhost:8000/api/guests/insert)
- **Register User**: [POST /api/register](http://localhost:8000/api/register)
- **User Login**: [POST /api/login](http://localhost:8000/api/login)
- **Select All Guests**: [GET /api/guests/select_all](http://localhost:8000/api/guests/select_all)
- **Delete Guest by ID**: [DELETE /api/guests/delete/{id}](http://localhost:8000/api/guests/delete/{id})
- **Update Guest by ID**: [PUT /api/guests/update/{id}](http://localhost:8000/api/guests/update/{id})
- **Find Guest by ID**: [GET /api/guests/find/{id}](http://localhost:8000/api/guests/find/{id})

## Booking Management

- **Update Booking by ID**: [PUT /api/booking/update/{id}](http://localhost:8000/api/booking/update/{id})
- **Cancel Booking by ID**: [PUT /api/booking/cancel/{id}](http://localhost:8000/api/booking/cancel/{id})
- **Void Booking by ID**: [PUT /api/booking/void/{id}](http://localhost:8000/api/booking/void/{id})
- **Move Stay by ID**: [PUT /api/booking/move/{id}](http://localhost:8000/api/booking/move/{id})
- **Insert Booking**: [POST /api/booking/insert](http://localhost:8000/api/booking/insert)
- **Select All Bookings**: [GET /api/booking/all](http://localhost:8000/api/booking/all)
- **Room Variable**: [GET /api/booking/roomVariable](http://localhost:8000/api/booking/roomVariable)
- **Select Booking by Time**: [GET /api/booking/allTime](http://localhost:8000/api/booking/allTime)
- **Check-in for Booking**: [POST /api/booking/checkin/{bookingId}](http://localhost:8000/api/booking/checkin/{bookingId})
- **Check-out for Booking**: [POST /api/booking/checkout/{bookingId}](http://localhost:8000/api/booking/checkout/{bookingId})

## Room Management

- **Select All Rooms**: [GET /api/room/all](http://localhost:8000/api/room/all)
- **Insert Room**: [POST /api/room/insert](http://localhost:8000/api/room/insert)
- **Update Room by ID**: [PUT /api/room/update/{id}](http://localhost:8000/api/room/update/{id})
- **Delete Room by ID**: [DELETE /api/room/delete/{id}](http://localhost:8000/api/room/delete/{id})

## Payment Management

- **Select All Payments**: [GET /api/payment/all](http://localhost:8000/api/payment/all)
- **Insert Payment**: [POST /api/payment/insert](http://localhost:8000/api/payment/insert)
- **Update Payment by ID**: [PUT /api/payment/update/{id}](http://localhost:8000/api/payment/update/{id})
- **Delete Payment by ID**: [DELETE /api/payment/delete/{id}](http://localhost:8000/api/payment/delete/{id})

## Dashboard

- **Today's Bookings**: [GET /api/dashboard/today_booking](http://localhost:8000/api/dashboard/today_booking)
- **Today's Status**: [GET /api/dashboard/todayStatus](http://localhost:8000/api/dashboard/todayStatus)
- **Guest Count by Day of the Week**: [GET /api/dashboard/guest_today](http://localhost:8000/api/dashboard/guest_today)

## Housekeeping Management

- **Insert Housekeeping Task**: [POST /api/housekeeping/insert](http://localhost:8000/api/housekeeping/insert)
- **Select All Housekeeping Tasks**: [GET /api/housekeeping/all](http://localhost:8000/api/housekeeping/all)
- **Delete Housekeeping Task by ID**: [DELETE /api/housekeeping/{id}](http://localhost:8000/api/housekeeping/{id})
- **Update Housekeeping Task by ID**: [PUT /api/housekeeping/update/{id}](http://localhost:8000/api/housekeeping/update/{id})
- **Find Housekeeping Tasks by Keyword**: [GET /api/housekeeping/find/{keyword}](http://localhost:8000/api/housekeeping/find/{keyword})

## Reports

- **Monthly Report**: [GET /api/report/monthly](http://localhost:8000/api/report/monthly)
- **Weekly Report**: [GET /api/report/weekly](http://localhost:8000/api/report/weekly)
- **Daily Report**: [GET /api/report/daily](http://localhost:8000/api/report/daily)

## Status

- **Bookings by Status Today**: [GET /api/status/bookings](http://localhost:8000/api/status/bookings)

Feel free to provide additional details, explanations, or any other relevant information about your application.

## Installation and Usage

Include instructions on how to install and run your Laravel application locally or on a server. You can also mention any dependencies or configuration steps.

## License

Specify the license under which your project is released.

## Contributors

List the contributors to your project, if any.

## Acknowledgments

If you want to acknowledge any third-party libraries, resources, or inspirations used in your project, you can include them here.

---

This `readme.md` file serves as documentation for the project. You can continuously update it as your project evolves.
