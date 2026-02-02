<?php

class Student {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        return $this->pdo->query("SELECT * FROM students ORDER BY created_at DESC")->fetchAll();
    }

    public function create($name, $email, $phone, $dob, $address) {
        return $this->pdo->prepare("INSERT INTO students (name, email, phone, dob, address) VALUES (?, ?, ?, ?, ?)")
            ->execute([$name, $email, $phone, $dob, $address]);
    }

    public function update($id, $name, $email, $phone, $dob, $address) {
        return $this->pdo->prepare("UPDATE students SET name = ?, email = ?, phone = ?, dob = ?, address = ? WHERE id = ?")
            ->execute([$name, $email, $phone, $dob, $address, $id]);
    }

    public function delete($id) {
        return $this->pdo->prepare("DELETE FROM students WHERE id = ?")->execute([$id]);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM students WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
?>
