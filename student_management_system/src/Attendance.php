<?php

class Attendance {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function markAttendance($student_id, $course_id, $date, $status, $remarks = '') {
        $sql = "INSERT INTO attendance (student_id, course_id, date, status, remarks) 
                VALUES (?, ?, ?, ?, ?) 
                ON DUPLICATE KEY UPDATE status = ?, remarks = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$student_id, $course_id, $date, $status, $remarks, $status, $remarks]);
    }

    public function getByDateAndCourse($date, $course_id) {
        $sql = "SELECT s.id, s.name, a.status, a.remarks 
                FROM students s 
                LEFT JOIN attendance a ON s.id = a.student_id AND a.date = ? AND a.course_id = ?
                ORDER BY s.name";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$date, $course_id]);
        return $stmt->fetchAll();
    }
    
    public function getStudentAttendance($student_id) {
         $sql = "SELECT a.*, c.name as course_name, c.code 
                 FROM attendance a 
                 LEFT JOIN courses c ON a.course_id = c.id 
                 WHERE a.student_id = ? 
                 ORDER BY a.date DESC";
         $stmt = $this->pdo->prepare($sql);
         $stmt->execute([$student_id]);
         return $stmt->fetchAll();
    }
}
?>
