<?php

class Schedule {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addSchedule($course_id, $day, $start_time, $end_time, $room) {
        $stmt = $this->pdo->prepare("INSERT INTO schedules (course_id, day_of_week, start_time, end_time, room) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$course_id, $day, $start_time, $end_time, $room]);
    }

    public function getAll() {
        $sql = "SELECT s.*, c.name as course_name, c.code 
                FROM schedules s 
                JOIN courses c ON s.course_id = c.id 
                ORDER BY s.day_of_week, s.start_time";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM schedules WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function updateSchedule($id, $course_id, $day, $start_time, $end_time, $room) {
        $stmt = $this->pdo->prepare("UPDATE schedules SET course_id = ?, day_of_week = ?, start_time = ?, end_time = ?, room = ? WHERE id = ?");
        return $stmt->execute([$course_id, $day, $start_time, $end_time, $room, $id]);
    }

    public function deleteSchedule($id) {
        $stmt = $this->pdo->prepare("DELETE FROM schedules WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
