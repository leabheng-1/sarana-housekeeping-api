# My Project

...

## Getting Started

To get started with this project, follow these steps:

1. Clone the repository to your local machine using the following command:
   ```bash
   git clone https://github.com/leabheng-1/sarana-housekeeping-api.git

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


<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
