# 🏫 School Management System

A comprehensive web-based school management system built with PHP, MySQL, HTML, CSS, and JavaScript. This system provides role-based dashboards for students, teachers, parents, and administrators with modern UI design inspired by contemporary educational platforms.

## 📋 Table of Contents

- [Features](#features)
- [System Requirements](#system-requirements)
- [Installation](#installation)
- [Database Setup](#database-setup)
- [User Roles & Access](#user-roles--access)
- [Default Login Credentials](#default-login-credentials)
- [Project Structure](#project-structure)
- [API Endpoints](#api-endpoints)
- [Security Features](#security-features)
- [Screenshots](#screenshots)
- [Contributing](#contributing)
- [License](#license)

## ✨ Features

### 🔐 Authentication System

- Secure login system with password hashing (bcrypt)
- Role-based access control (Admin, Teacher, Student, Parent)
- Session management with anti-back-button security
- Automatic logout functionality

### 👨‍💼 Admin Dashboard

- Complete user management (Add/Edit/Delete students, teachers, parents)
- Real-time statistics and analytics
- User profile management
- System configuration

### 👨‍🏫 Teacher Portal

- **Student Management**: Add and delete students
- **Profile Management**: Update personal information and change password
- **Class Management**: View and manage assigned classes
- **Assignment Tracking**: Monitor student progress
- Beautiful, responsive interface

### 👨‍🎓 Student Portal

- **Profile Management**: Update personal information and change password
- **Academic Progress**: View grades and attendance
- **Schedule Management**: Daily class schedule
- **Assignment Tracking**: View pending and submitted assignments

### 👨‍👩‍👧‍👦 Parent Portal

- **Teacher Requests**: Select and request specific teachers for children
- **Profile Management**: Update parent information and change password
- **Student Information**: Update child's details (name, class, roll, email)
- **Progress Monitoring**: Track child's academic performance
- **Communication**: View teacher messages and announcements

### 🎨 Modern UI/UX

- Responsive design that works on all devices
- Clean, modern interface inspired by Daffodil International University
- Smooth animations and hover effects
- Intuitive navigation and user-friendly forms

## 🔧 System Requirements

- **Web Server**: Apache/Nginx
- **PHP**: Version 7.4 or higher
- **Database**: MySQL 5.7 or higher / MariaDB 10.2 or higher
- **Browser**: Modern web browser (Chrome, Firefox, Safari, Edge)

## 🚀 Installation

### 1. Clone/Download the Project

```bash
git clone [repository-url]
# OR download and extract the ZIP file
```

### 2. Place in Web Directory

- Copy the project folder to your web server directory
- For XAMPP: `C:\xampp\htdocs\school-management`
- For WAMP: `C:\wamp64\www\school-management`
- For LAMP: `/var/www/html/school-management`

### 3. Configure Database Connection

Edit `config/db_connect.php` with your database credentials:

```php
<?php
$host = 'localhost';
$username = 'your_db_username';
$password = 'your_db_password';
$database = 'school_management';

$conn = mysqli_connect($host, $username, $password, $database);
?>
```

## 🗄️ Database Setup

### 1. Create Database

```sql
CREATE DATABASE school_management;
```

### 2. Import Database Schema

Import the `school_management.sql` file into your MySQL database:

```bash
mysql -u username -p school_management < school_management.sql
```

### 3. Set Up User Passwords

The system automatically creates proper password hashes. Test users are included with the password `password123` for students, teachers, and parents.

## 👥 User Roles & Access

### 🔴 Administrator

- **Access**: Complete system control
- **Capabilities**: Manage all users, view analytics, system configuration
- **Dashboard**: `admin_dashboard.php`

### 🟡 Teacher

- **Access**: Student management, profile management
- **Capabilities**: Add/delete students, update profile, manage classes
- **Dashboard**: `teacher_dashboard.php`

### 🟢 Student

- **Access**: Personal profile and academic information
- **Capabilities**: Update profile, view grades, check schedule
- **Dashboard**: `student_dashboard.php`

### 🔵 Parent

- **Access**: Child's information and teacher communication
- **Capabilities**: Request teachers, update child's info, manage profile
- **Dashboard**: `parent_dashboard.php`

## 🔑 Default Login Credentials

### Admin

- **Username**: `admin001`
- **Password**: `password`

### Students

- **Usernames**: `S001`, `S002`, `S003`, `S004`, `S005`
- **Password**: `password123`

### Teachers

- **Usernames**: `T001`, `T002`
- **Password**: `password123`

### Parents

- **Usernames**: `P001`, `P002`
- **Password**: `password123`

## 📁 Project Structure

```
school-management/
├── 📱 Frontend Pages
│   ├── index.php                 # Homepage
│   ├── admin_login.php           # Admin login
│   ├── teacher_login.php         # Teacher login
│   ├── student_login.php         # Student login
│   ├── parent_login.php          # Parent login
│   ├── admin_dashboard.php       # Admin dashboard
│   ├── teacher_dashboard.php     # Teacher dashboard
│   ├── student_dashboard.php     # Student dashboard
│   ├── parent_dashboard.php      # Parent dashboard
│   └── logout.php               # Logout handler
├── 🔌 Backend API
│   └── api/
│       ├── add_user.php          # Add new users
│       ├── teacher_manage_students.php  # Teacher student management
│       ├── teacher_profile.php   # Teacher profile management
│       ├── student_profile.php   # Student profile management
│       ├── parent_profile.php    # Parent profile management
│       ├── parent_teacher_requests.php  # Teacher request system
│       └── [other API files]
├── 🎨 Assets
│   ├── css/
│   │   ├── dashboard.css         # Modern dashboard styling
│   │   ├── login.css            # Login page styling
│   │   ├── admin_dashboard.css  # Admin-specific styling
│   │   └── style.css            # Homepage styling
│   ├── js/
│   │   └── admin_dashboard.js   # Admin dashboard functionality
│   └── images/                  # School images and assets
├── ⚙️ Configuration
│   └── config/
│       └── db_connect.php       # Database connection
├── 📄 Content
│   └── includes/
│       ├── about.php            # About section
│       ├── academics.php        # Academics section
│       ├── admissions.php       # Admissions section
│       └── contact.php          # Contact section
├── 🗄️ Database
│   └── school_management.sql    # Database schema
└── 📖 Documentation
    └── README.md               # This file
```

## 🔌 API Endpoints

### Authentication

- `POST /admin_login.php` - Admin login
- `POST /teacher_login.php` - Teacher login
- `POST /student_login.php` - Student login
- `POST /parent_login.php` - Parent login

### User Management

- `POST /api/add_user.php` - Add new user (Admin only)
- `POST /api/update_admin.php` - Update admin profile
- `POST /api/delete_student.php` - Delete student (Admin only)
- `POST /api/delete_teacher.php` - Delete teacher (Admin only)
- `POST /api/delete_parent.php` - Delete parent (Admin only)

### Profile Management

- `POST /api/teacher_profile.php` - Teacher profile operations
- `POST /api/student_profile.php` - Student profile operations
- `POST /api/parent_profile.php` - Parent profile operations

### Specialized Features

- `POST /api/teacher_manage_students.php` - Teacher student management
- `POST /api/parent_teacher_requests.php` - Parent teacher requests

## 🔒 Security Features

### Authentication Security

- **Password Hashing**: Uses PHP's `password_hash()` with bcrypt
- **SQL Injection Protection**: Prepared statements throughout
- **Session Security**: Secure session management with regeneration
- **XSS Protection**: Input sanitization and `htmlspecialchars()`

### Access Control

- **Role-based Access**: Strict role verification on all pages
- **Session Validation**: Continuous session validation
- **Anti-back Button**: Prevents unauthorized access via browser back button
- **Cache Control**: Prevents caching of sensitive pages

### Browser Security

- **History Manipulation**: Prevents navigation via browser controls
- **Keyboard Shortcuts**: Disables shortcuts that could bypass security
- **Auto-logout**: Secure logout with complete session cleanup

## 📱 Screenshots

### Homepage

- Modern landing page with school information
- Login dropdown for different user roles
- Responsive design with carousel gallery

### Dashboards

- **Admin**: Comprehensive management interface with user statistics
- **Teacher**: Clean interface with student management and profile tools
- **Student**: Personal dashboard with academic progress tracking
- **Parent**: Family-oriented interface with child monitoring tools

## 🚀 Getting Started

1. **Set up the environment** (XAMPP/WAMP/LAMP)
2. **Import the database** using `school_management.sql`
3. **Configure database connection** in `config/db_connect.php`
4. **Access the system** via `http://localhost/school-management/`
5. **Login** using the default credentials provided above
6. **Explore** the different user roles and functionalities

## 🔧 Customization

### Adding New Features

- Create new API endpoints in the `/api/` directory
- Add corresponding frontend interfaces in dashboard files
- Update database schema as needed

### Styling Changes

- Modify CSS files in the `/css/` directory
- Update CSS custom properties in `dashboard.css` for theme changes
- Add new styles following the existing design pattern

### Database Modifications

- Update the schema in `school_management.sql`
- Modify API endpoints to handle new fields
- Update frontend forms accordingly

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/new-feature`)
3. Commit your changes (`git commit -am 'Add new feature'`)
4. Push to the branch (`git push origin feature/new-feature`)
5. Create a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 📞 Support

For support and questions:

- Create an issue in the repository
- Contact the development team
- Check the documentation for common solutions

---

**Built with ❤️ for educational institutions**

_Inspired by modern educational platforms and designed for efficiency, security, and user experience._
