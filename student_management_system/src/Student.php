<?php

class Student {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM students ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function create($name, $email, $phone, $dob, $address) {
        $stmt = $this->pdo->prepare("INSERT INTO students (name, email, phone, dob, address) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$name, $email, $phone, $dob, $address]);
    }

    public function update($id, $name, $email, $phone, $dob, $address) {
        $stmt = $this->pdo->prepare("UPDATE students SET name = ?, email = ?, phone = ?, dob = ?, address = ? WHERE id = ?");
        return $stmt->execute([$name, $email, $phone, $dob, $address, $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM students WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM students WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
?>
