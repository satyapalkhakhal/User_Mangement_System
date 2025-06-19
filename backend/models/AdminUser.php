<?php
class AdminUser {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($name, $email, $phone, $password) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->pdo->prepare("INSERT INTO admin_user (name, email, phone, password) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$name, $email, $phone, $hashedPassword]);
    }

    public function findByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM admin_user WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM admin_user WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}