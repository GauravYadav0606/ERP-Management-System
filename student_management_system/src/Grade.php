<?php
class Grade {
    private $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    public function getCourses() {
        return $this->pdo->query("SELECT * FROM courses")->fetchAll();
    }
    public function addCourse($name, $code, $description) {
        $stmt = $this->pdo->prepare("INSERT INTO courses (name, code, description) VALUES (?, ?, ?)");
        return $stmt->execute([$name, $code, $description]);
    }
    public function assignGrade($student_id, $course_id, $grade, $remarks) {
        $stmt = $this->pdo->prepare("INSERT INTO grades (student_id, course_id, grade, remarks) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$student_id, $course_id, $grade, $remarks]);
    }
    public function getGradesByCourse($course_id) {
        $sql = "SELECT g.*, s.name as student_name 
                FROM grades g 
                JOIN students s ON g.student_id = s.id 
                WHERE g.course_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$course_id]);
        return $stmt->fetchAll();
    }
    public function updateGrade($id, $grade, $remarks) {
        $stmt = $this->pdo->prepare("UPDATE grades SET grade = ?, remarks = ? WHERE id = ?");
        return $stmt->execute([$grade, $remarks, $id]);
    }
    public function deleteGrade($id) {
        $stmt = $this->pdo->prepare("DELETE FROM grades WHERE id = ?");
        return $stmt->execute([$id]);
    }
    public function deleteCourse($id) {
        $stmt = $this->pdo->prepare("DELETE FROM courses WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
