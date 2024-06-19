## About

# Laravel Filament Bike Repair App

A hobby project created to get comfortable with [Filament](https://filamentphp.com). It includes an Admin Panel for managing companies and service points, and a Mechanic Panel for handling assets and schedules.

![Admin Panel](https://github.com/minuut/laravel-filament-bike-repair-app/assets/70378641/a87e2771-b7dd-44f3-b36e-363d620957ce)
![Mechanic Panel](https://github.com/minuut/laravel-filament-bike-repair-app/assets/70378641/dac03529-4d18-4cb3-b529-c0e7fc8492ee)

## Features

- **Admin Panel:** Manage companies and service points.
- **Mechanic Panel:** Manage personal appointments, schedules, and assets.
- **Multi-Service Point Management:** Switch between service points.

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/minuut/laravel-filament-bike-repair-app.git
   cd laravel-filament-bike-repair-app
   ```
   
2. Install dependencies:
   ```bash
   composer install
   npm install && npm run build
   ```

3. Setup environment:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

## Key Dependencies

- PHP: ^8.1
- Laravel: ^10.10
- Filament: ^3.2

## License

This project is licensed under the MIT License.

## Disclaimer

This README is a work in progress and will be updated as the project evolves.



