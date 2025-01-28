# TicketLokal

TicketLokal is a web-based system for managing ticket reservations.

## Features
- Admin dashboard for managing tickets.
- User-friendly interface for booking tickets.
- Responsive design.

## Setup Instructions
1. Clone the repository:
   ```bash
   git clone https://github.com/Amrrzn/ticketlokal.git

## Database Setup

To set up the database for this project:

1. **Download the `tiketlokal.sql` file**:
   - The file is located in the root of this repository.

2. **Import the Database**:
   - Open [phpMyAdmin](http://localhost/phpmyadmin) on your local server (e.g., XAMPP).
   - Create a new database (e.g., `tiketlokal`).
   - Click the **Import** tab in phpMyAdmin.
   - Select the `tiketlokal.sql` file and click **Go**.

3. **Update the Database Connection**:
   - Edit the database connection settings in your `config.php` file:
     ```php
     $host = 'localhost';
     $username = 'root';
     $password = ''; // Leave empty for XAMPP
     $database = 'eventdetail';
     ```
4. **Run the Project**:
   - Open your project in the browser (e.g., `http://localhost/ticketlokal`).
