# Cabinet Médical OMNA Santé 

A comprehensive web application for a single-doctor medical cabinet, featuring online appointment booking, patient management, and a dynamic content management system for the doctor (admin).

## 🚀 Features

### For Patients (Public Side)
- **Responsive Design**: Modern and clean interface compatible with mobile and desktop.
- **Information Pages**: Home, About, Services, Contact with Google Maps integration.
- **User Account**: Secure registration and login for patients.
- **Appointment Booking**: 
  - View doctor's availability (implied).
  - Book appointments online (requires login).
  - View appointment history and status.
- **Contact Form**: Direct messaging to the doctor/admin.

### For Doctor (Admin Dashboard)
- **Dashboard Overview**: Quick view of upcoming appointments.
- **Appointment Management**: Accept, Refuse, or Cancel appointments.
- **Services Management**: Add, Edit, or Delete medical services offered.
- **Content Management**:
  - Update website texts (Hero, About, Contact info).
  - **Image Management**: Upload and change Homepage and About section images directly from the dashboard.
- **Secure Authentication**: Role-based access control.

## 🛠️ Tech Stack

- **Backend**: PHP 8.x (Native, PDO for Database)
- **Frontend**: HTML5, CSS3, Bootstrap 5, FontAwesome 6
- **Database**: MySQL 8.x
- **Server**: Apache (via XAMPP/WAMP/MAMP)

## ⚙️ Installation

### 1. Prerequisites
- Install [XAMPP](https://www.apachefriends.org/) (or WAMP/MAMP) to get Apache and MySQL running.

### 2. Setup
1. **Download/Clone** the project.
2. Move the project folder to your server's root directory:
   - **XAMPP**: `C:\xampp\htdocs\cabinet_medical`
   - **MAMP**: `/Applications/MAMP/htdocs/cabinet_medical`

### 3. Database Configuration
1. Open **phpMyAdmin** (usually at `http://localhost/phpmyadmin`).
2. Create a new database named `cabinet_medical`.
3. Import the `database.sql` file located in the project root:
   - Select the `cabinet_medical` database.
   - Click **Import** tab.
   - Choose `database.sql` and click **Go**.

### 4. Configuration Check
- Ensure `includes/db.php` has the correct credentials (default is set for XAMPP):
  ```php
  $host = 'localhost';
  $dbname = 'cabinet_medical';
  $username = 'root';
  $password = ''; // Default XAMPP password is empty
  ```

## 🖥️ Usage

1. **Start Apache and MySQL** in XAMPP Control Panel.
2. Open your browser and go to:
   [http://localhost/cabinet_medical](http://localhost/cabinet_medical)

### Default Admin Credentials
To access the Admin Dashboard:
- **Login URL**: [http://localhost/cabinet_medical/login.php](http://localhost/cabinet_medical/login.php)
- **Email**: `admin@santeplus.fr`
- **Password**: `admin123`

## 📂 Directory Structure

```
cabinet_medical/
├── admin/              # Admin dashboard files (secured)
│   ├── dashboard.php   # Appointment management
│   ├── services.php    # Services CRUD
│   └── settings.php    # Site content & image settings
├── assets/             # Static assets
│   ├── css/            # Stylesheets
│   ├── img/            # Images
│   └── uploads/        # User uploaded images
├── includes/           # Reusable PHP components
│   ├── db.php          # Database connection
│   ├── header.php      # Navigation
│   └── footer.php      # Footer
├── database.sql        # Database schema import file
├── index.php           # Homepage
├── appointment.php     # Booking page
├── login.php           # User login
├── register.php        # User registration
└── ...
```

## 🛡️ Security
- **Passwords**: Hashed using `password_hash()` (Bcrypt).
- **Database**: Uses `PDO` with prepared statements to prevent SQL Injection.
- **Sessions**: Protected admin routes via session verification.
- **Uploads**: Validated for file type (images only) and renamed to prevent overwrites/execution.

---
**Note**: This project is intended for educational and demonstration purposes.
