# BikeHub

## Project Overview

BikeHub is a Laravel Filament web app tailored for bike repair shops. It features user-friendly interfaces for Admins, Mechanics, and Customers, allowing easy scheduling of bike repairs. Mechanics have control over their schedules and appointments, specific to each service point. The mechanic panel supports multi-tenancy, enabling mechanics to manage appointments and schedules across different service points (locations, branches).

## Current Status

- **Phase:** Active Exploration
- **Progress:** Deep dive into Filament V3
- **Timeframe:** I would say about 35% done with what I had in mind, but it will grow larger overtime.

The application currently uses Dutch language, with plans to implement multi-language support.

## Key Dependencies

- **PHP Version:** ^8.1
- **Laravel Framework:** ^10.10
- **Filament:** ^3.0
- **Filament Actions:** ^3.0
- **Spatie Laravel Translatable Plugin:** ^3.0
- **Laravel Trend:** ^0.1.5

## Additional Packages

- **laravel/debugbar:** ^3.9
- **laravel/telescope:** ^4.17
- **stechstudio/filament-impersonate:** ^3.5

## License

This project is licensed under the MIT License.

**Admin panel preview:**

![image](https://github.com/minuut/BikeHub/assets/70378641/43d374c4-6519-42ca-93be-fcce303ff99d)

**Mechanic panel preview: (While logged in as demo mechanic 'Harrie Fietsers')**

![image](https://github.com/minuut/BikeHub/assets/70378641/606a5207-9ef9-4df2-96e6-e6ef8f959680)

Eventually when the customer selects a timeslot the slot will not be available for other customers to pick. There are duplicates in the screenshots, because it's still in development.

## Disclaimer

This README is a work in progress and will be updated as the project evolves.



