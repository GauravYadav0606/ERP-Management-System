# Student Management System - NIET

A comprehensive web-based Student Management System built with PHP and MySQL. This system allows educational institutions to manage student records, track attendance, assign grades, and schedule classes efficiently.


## 🚀 Features

- **Dashboard**: Real-time overview of total students, courses, and active classes.
- **Student Management**: Add, view, update, and delete student profiles.
- **Attendance Tracking**: 
  - Mark attendance (Present/Absent/Late).
  - View detailed attendance history and percentage per student.
  - Add remarks for each attendance record.
- **Grade Management**: 
  - Create courses/subjects.
  - Assign grades and remarks to students.
  - Edit and delete grade entries.
- **Schedule Management**: 
  - Create class timetables with days, times, and room numbers.
  - Visual weekly schedule for easy tracking.
- **User Authentication**: Secure login system for administrators/staff.
- **Responsive Design**: Clean, modern UI built with vanilla CSS.

## 🛠️ Tech Stack

- **Backend**: PHP (Vanilla)
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript
- **Server**: Apache (via XAMPP/WAMP)

## 📂 File Structure

```
student_management_system/ ├── config/ # Database connection configuration │ └── database.php ├── database/ # SQL schema scripts │ └── schema.sql ├── public/ # Publicly accessible files │ ├── css/ # Stylesheets │ └── index.php # Main entry point (Router) ├── src/ # Backend logic & Models │ ├── Auth.php │ ├── Student.php │ ├── Attendance.php │ ├── Grade.php │ └── Schedule.php └── templates/ # Frontend views/pages ├── dashboard.php ├── students.php ├── student_profile.php ├── attendance.php ├── grades.php └── schedule.php
```

## ⚙️ Installation & Setup

1.  **Clone the Repository**
    ```bash
    git clone https://github.com/yourusername/student-management-system.git
    cd student-management-system
    ```

2.  **Set up the Database**
    - Open your MySQL client (e.g., PHPMyAdmin).
    - Create a new database named `student_db`.
    - Import the `database/schema.sql` file to create the necessary tables.

3.  **Configure Connection**
    - Open `config/database.php`.
    - Update the `$username`, `$password`, and `$host` variables if your local setup differs from the defaults (Default: root, empty password).

4.  **Run the Application**
    - Place the project folder in your server's root directory (e.g., `htdocs` for XAMPP).
    - Start Apache and MySQL services.
    - Open your browser and navigate to:
      ```
      http://localhost/student_management_system/public/
      ```

## 🔑 Default Credentials

- **Username**: `admin`
- **Password**: `admin123`
