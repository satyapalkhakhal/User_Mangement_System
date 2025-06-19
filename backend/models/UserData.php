<?php
class UserData {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM user_data");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM user_data WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO user_data (name, email, phone, role, address, photo) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['name'],
            $data['email'],
            $data['phone'],
            $data['role'],
            $data['address'] ?? null,
            $data['photo'] ?? null
        ]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE user_data SET name = ?, email = ?, phone = ?, role = ?, address = ?, photo = ? WHERE id = ?");
        return $stmt->execute([
            $data['name'],
            $data['email'],
            $data['phone'],
            $data['role'],
            $data['address'] ?? null,
            $data['photo'] ?? null,
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM user_data WHERE id = ?");
        return $stmt->execute([$id]);
    }
    public function getByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM user_data WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}