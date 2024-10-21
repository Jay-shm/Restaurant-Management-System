# Restaurant Management System - DBMS / DMQP Project

## Project Overview
This project is a **Database Management System (DBMS)** or **DMQP** project designed for efficient restaurant management. It features an admin panel for managing orders, staff, and tables. Built with **PHP** and **MySQL**, it handles real-time order processing, staff management, and dynamic table allocation.

## Features

- **Order Management**: Admin can view, select, and update the status of orders.
- **Table Management**: The system automatically updates table statuses based on order completion.
- **Staff Management**: Admin can hire, fire, and update staff details, such as roles and personal information.
- **Dynamic Update with AJAX**: Staff details are loaded dynamically using AJAX when selected in the admin panel.

## Tech Stack

- **Backend**: PHP 7+
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript (AJAX for dynamic staff updates)
- **Libraries**: Poppins (font)
- **Deployment**: Any PHP-supported environment (XAMPP, WAMP, LAMP, etc.)

## Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/your-username/Restaurant-Management-System.git
   ```
2. **Database Setup**:
   - Import the provided SQL file (`database.sql`) into your MySQL database.
   - Update the database connection credentials in the `includes/database.php` file:
     ```php
     $servername = "your_servername";
     $username = "your_username";
     $password = "your_password";
     $dbname = "your_database_name";
     ```
3. **Run on Localhost**:
   - Ensure your local server (XAMPP/WAMP/LAMP) is running.
   - Place the project folder inside your server's root directory (`htdocs` for XAMPP).
   - Access the project by visiting `http://localhost/Restaurant-Management-System`.

## Folder Structure

```
├── includes/               # Contains reusable components like navbar and database connection
├── IMG/                    # Images (e.g., logos, backgrounds)
├── admin.php               # Admin panel for managing orders and staff
├── get_staff_details.php    # AJAX endpoint for fetching staff details
└── README.md               # Project documentation
```

## Usage

### Admin Panel
1. **Order Management**: Admins can mark orders as "Completed" and update table statuses.
2. **Staff Management**:
   - **Hire**: Add new staff members with personal details and roles.
   - **Update**: Modify staff information such as names, roles, email, and mobile number.
   - **Fire**: Remove staff members from the system.
   - **Dynamic Update**: Staff details load dynamically when selected from the dropdown for easy updates.

## Contributing

1. Fork the repository
2. Create your feature branch:
   ```bash
   git checkout -b feature/your-feature
   ```
3. Commit your changes:
   ```bash
   git commit -m 'Add your feature'
   ```
4. Push to the branch:
   ```bash
   git push origin feature/your-feature
   ```
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Contact

- **Author**: [Your Name](https://github.com/your-username)
- **Email**: your.email@example.com

### 4. **Steps to Push to GitHub**:
After filling out the repository information and preparing the README file:

1. **Initialize a Git repository**:
   Inside your project folder, run:
   ```bash
   git init
   ```

2. **Add files to the repository**:
   ```bash
   git add .
   ```

3. **Commit the changes**:
   ```bash
   git commit -m "Initial commit - Restaurant Management System"
   ```

4. **Add the remote repository URL**:
   Replace `your-username` and `your-repository` with your actual GitHub details:
   ```bash
   git remote add origin https://github.com/your-username/Restaurant-Management-System.git
   ```

5. **Push the changes**:
   ```bash
   git push -u origin master
   ```
