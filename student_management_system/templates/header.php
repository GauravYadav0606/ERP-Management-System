<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduSync Student Management System</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="main-wrapper">
        <aside class="sidebar">
            <div class="logo">
                🎓 EduSync
            </div>
            <nav class="nav">
                <a href="index.php?page=dashboard" class="nav-link <?php echo $page === 'dashboard' ? 'active' : ''; ?>">
                    Dashboard
                </a>
                <a href="index.php?page=students" class="nav-link <?php echo $page === 'students' ? 'active' : ''; ?>">
                    Students
                </a>
                <a href="index.php?page=attendance" class="nav-link <?php echo $page === 'attendance' ? 'active' : ''; ?>">
                    Attendance
                </a>
                <a href="index.php?page=grades" class="nav-link <?php echo $page === 'grades' ? 'active' : ''; ?>">
                    Grades
                </a>
                <a href="index.php?page=schedule" class="nav-link <?php echo $page === 'schedule' ? 'active' : ''; ?>">
                    Schedule
                </a>
                <a href="index.php?page=logout" class="nav-link" style="margin-top: auto; color: #ef4444;">
                    Logout
                </a>
            </nav>
        </aside>
        <main class="content">
            <header class="header">
                <h2><?php echo ucwords(str_replace('_', ' ', $page)); ?></h2>
                <div class="user-info">
                    <span>Welcome, <b><?php echo $_SESSION['username'] ?? 'User'; ?></b></span>
                </div>
            </header>
