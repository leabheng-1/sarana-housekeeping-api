# My Project

...

## Getting Started

To get started with this project, follow these steps:

1. Clone the repository to your local machine using the following command:
   ```bash
   git clone https://github.com/leabheng-1/sarana-housekeeping-api.git






# API Documentation

Welcome to the API documentation for your application. Below, you'll find a list of available routes along with their descriptions and usage instructions.

**Base URL**: `http://localhost:8000/api`

## Guests

### Insert a Guest
- **Endpoint**: `/guests/insert` (POST)
- **Description**: Insert a new guest record.
- **Usage**: `POST http://localhost:8000/api/guests/insert`

### Register User
- **Endpoint**: `/register` (POST)
- **Description**: Register a new user.
- **Usage**: `POST http://localhost:8000/api/register`

### Log In
- **Endpoint**: `/login` (POST)
- **Description**: Log in a user.
- **Usage**: `POST http://localhost:8000/api/login`

### Select All Guests
- **Endpoint**: `/guests/select_all` (GET)
- **Description**: Retrieve all guest records.
- **Usage**: `GET http://localhost:8000/api/guests/select_all`

### Delete a Guest
- **Endpoint**: `/guests/delete/{id}` (DELETE)
- **Description**: Delete a guest record by ID.
- **Usage**: `DELETE http://localhost:8000/api/guests/delete/{id}`

### Update a Guest
- **Endpoint**: `/guests/update/{id}` (PUT)
- **Description**: Update a guest record by ID.
- **Usage**: `PUT http://localhost:8000/api/guests/update/{id}`

### Find a Guest
- **Endpoint**: `/guests/find/{id}` (GET)
- **Description**: Find a guest record by ID.
- **Usage**: `GET http://localhost:8000/api/guests/find/{id}`

## Booking

### Update a Booking
- **Endpoint**: `/booking/update/{id}` (PUT)
- **Description**: Update a booking by ID.
- **Usage**: `PUT http://localhost:8000/api/booking/update/{id}`

### Cancel a Booking
- **Endpoint**: `/booking/cancel/{id}` (PUT)
- **Description**: Cancel a booking by ID.
- **Usage**: `PUT http://localhost:8000/api/booking/cancel/{id}`

# ... (Continue documenting other routes in a similar format)














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
