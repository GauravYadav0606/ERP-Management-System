<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Auth.php';
require_once __DIR__ . '/../src/Student.php';

$auth = new Auth($pdo);
if (!$auth->isLoggedIn()) {
    header('Location: ../public/index.php');
    exit;
}

$studentModel = new Student($pdo);
$students = $studentModel->getAll();

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="students_export_' . date('Y-m-d') . '.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['ID', 'Name', 'Email', 'Phone', 'DOB', 'Address', 'Joined Date']);

foreach ($students as $student) {
    fputcsv($output, [
        $student['id'],
        $student['name'],
        $student['email'],
        $student['phone'],
        $student['dob'],
        $student['address'],
        $student['created_at']
    ]);
}
fclose($output);
?>
