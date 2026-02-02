<?php
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Auth.php';

$auth = new Auth($pdo);
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

if (!$auth->isLoggedIn() && $page !== 'login') {
    header('Location: index.php?page=login');
    exit;
}

if ($page === 'login') {
    require_once '../templates/login.php';
} else {
    require_once '../templates/header.php';
    
    // Simple routing
    $file = "../templates/$page.php";
    if (file_exists($file)) {
        require_once $file;
    } else {
        echo "<h2>Page not found</h2>";
    }

    require_once '../templates/footer.php';
}
?>
