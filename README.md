# Laravel Filament Bike Repair App

This is a hobby project designed to help familiarize with [Filament](https://filamentphp.com). The application is a multi-panel bike repair management system that supports multi-tenancy, enabling employees to work across different locations. It includes an Admin Panel for managing companies and service points, and a Mechanic Panel for managing assets and schedules.

>**NOTE:** This project was created for learning purposes and is not production-ready. I initially started it to explore Filament, but while building a SaaS at my job using Filament, I lost interest in continuing this personal project.

Admin panel preview:

![Admin Panel](https://github.com/y0f/laravel-filament-bike-repair-app/assets/70378641/1088fcfd-7a7d-4a07-b3bd-e22449f70c96)

## Features

- **Admin Panel:** Manage companies and service points efficiently.
- **Mechanic Panel:** Manage personal appointments, schedules, and assets.
- **Multi-Service Point Management:** Allows employees to work across multiple service locations.
- **Interactive Tour:** With the help of the [Filament Tour Manager](https://github.com/jibaymcs/filament-tour) package, an interactive tour is provided to guide users through the Admin Panel.
- **Custom Themes:** The app incorporates the [Hasnayeen Laravel Themes](https://github.com/hasnayeen/laravel-themes) package for dynamic theme customization.


## Installation Guide

To set up the project locally, follow these steps:

1. **Clone the repository:**
   ```bash
   git clone https://github.com/y0f/bike-app.git
   cd laravel-filament-bike-repair-app
   ```
   
2. Install dependencies:
    ```bash
    composer install
    npm install # or yarn or bun install
    ```

3. **Setup environment variables:**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

    Update the `.env` file with your database credentials and other necessary settings.

4. **Run database migrations and seeders:**

    ```bash
    php artisan migrate --seed
    ```

5. **Start the development server:**

    ```bash
    php artisan serve
    ```

The development server will start at **`http://localhost:8000`**.

- **Admin Panel:** Accessible at **`http://localhost:8000/administratie_portaal/login`**.
- **Mechanic Panel:** Accessible at **`http://localhost:8000/mechanic/login`**.

Dummy user credentials for logging in to these panels are available in the **`Database/Seeders/UserSeeder.php`** file.

## Key Dependencies

- PHP: ^8.1
- Laravel: ^10.10
- Filament: ^3.2
- Filament Tour Manager: ^3.1
- Hasnayeen Laravel Themes: ^3.0

## License

This project is licensed under the MIT License.



