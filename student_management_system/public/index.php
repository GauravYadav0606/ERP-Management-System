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
    require_once __DIR__ . '/../templates/login.php';
} else {
    require_once __DIR__ . '/../templates/header.php';
    
    $templatePath = __DIR__ . '/../templates/' . $page . '.php';
    if (file_exists($templatePath)) {
        require_once $templatePath;
    } else {
        echo "<h2>Page not found</h2>";
    }
    require_once __DIR__ . '/../templates/footer.php';
}
?>
