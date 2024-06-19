# Laravel Filament Bike Repair App

A hobby project built to get comfortable with [Filament](https://filamentphp.com). This multi-panel bike repair management app supports multi-tenancy, allowing employees to work across multiple locations. It features an Admin Panel for managing companies and service points, and a Mechanic Panel for handling assets and schedules.

- NOTE: ***This project is not production ready, and I use it for testing plugins and new features***

## Features

- **Admin Panel:** Manage companies and service points.
- **Mechanic Panel:** Manage personal appointments, schedules, and assets.
- **Multi-Service Point Management:** Supports employees working across multiple locations.

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/y0f/laravel-filament-bike-repair-app.git
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



