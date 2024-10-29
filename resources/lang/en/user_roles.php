<?php

return [
    'admin'     => 'Administrator',
    'staff'     => 'Manager',
    'mechanic'  => 'Mechanic',
    'customer'  => 'User',

    'description' => [
        'admin'     => 'Full access to the admin panel and all functionalities. Can manage user accounts, generate reports, and configure system settings.',
        'staff'     => 'Limited access to the admin panel. Can perform daily tasks like updating repair statuses, managing mechanic schedules, and overseeing appointments.',
        'mechanic'  => 'Access to the mechanic panel. Can view work orders, schedule repairs, and update technical details. Optionally, can also plan and create their own schedules.',
        'customer'  => 'Can schedule appointments, check repair statuses, and communicate with the mechanic or staff.',
    ],
];
