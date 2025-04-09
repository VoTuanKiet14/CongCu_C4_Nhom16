# Blood Donation System

## Overview
The Blood Donation System is a web application designed to facilitate the management of blood donation events, appointments, and user interactions. This system allows users to register, log in, and manage their appointments for blood donation. It also provides functionalities for managing blood inventory, donation units, health checks, and FAQs.

## Features
- User authentication (registration, login, password reset)
- Appointment management (create, edit, view appointments)
- Blood inventory management (add, edit, view blood inventory)
- Donation unit management (create, edit, view donation units)
- Event management (create, edit, view blood donation events)
- FAQ management (create, edit, view FAQs)
- Health check management (create, edit, view health checks)
- News management (create, edit, view news articles)

## Project Structure
```
blood-donation-system
├── app
│   ├── controllers
│   ├── models
│   ├── views
├── config
│   ├── database.php
│   └── permissions.php
├── public
│   ├── css
│   ├── js
│   ├── images
│   ├── index.php
│   └── .htaccess
├── sql
│   └── blood_donation_system.sql
├── tests
├── .env
├── .gitignore
├── composer.json
├── README.md
└── routes.php
```

## Installation
1. Clone the repository to your local machine.
2. Set up a local server using XAMPP.
3. Import the SQL database structure and data from `sql/blood_donation_system.sql` into your database.
4. Configure your database connection settings in `config/database.php`.
5. Set up environment variables in the `.env` file.
6. Access the application through your web browser at `http://localhost/blood-donation-system/public`.

## Usage
- Navigate to the login page to access your account or register a new account.
- Once logged in, you can manage your appointments, view blood inventory, and participate in donation events.
- Admin users can manage all aspects of the system, including users, events, and FAQs.

## Testing
Unit tests are provided for each model and controller in the `tests` directory. You can run these tests to ensure the functionality of the application.

## Contributing
Contributions are welcome! Please submit a pull request or open an issue for any enhancements or bug fixes.

## License
This project is licensed under the MIT License. See the LICENSE file for more details.