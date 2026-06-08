<?php
class UserModel
{
    private PDO $conn;

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    public function findByUsernameOrEmail(string $account): ?array
    {
        $stmt = $this->conn->prepare('SELECT * FROM users WHERE username = :username OR email = :email LIMIT 1');
        $stmt->execute([
            ':username' => $account,
            ':email' => $account,
        ]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function findByUsername(string $username): ?array
    {
        $stmt = $this->conn->prepare('SELECT * FROM users WHERE username = :username LIMIT 1');
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->conn->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->conn->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function getAllUsers(): array
    {
        $stmt = $this->conn->query('SELECT id, username, email, full_name, role, created_at FROM users ORDER BY created_at DESC, id DESC');
        return $stmt->fetchAll() ?: [];
    }

    public function create(string $username, string $email, string $password, string $fullName, string $role = 'user'): bool
    {
        $stmt = $this->conn->prepare('INSERT INTO users (username, email, password, full_name, role) VALUES (:username, :email, :password, :full_name, :role)');
        return $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => password_hash($password, PASSWORD_DEFAULT),
            ':full_name' => $fullName,
            ':role' => $role,
        ]);
    }

    public function updateRole(int $id, string $role): bool
    {
        $stmt = $this->conn->prepare('UPDATE users SET role = :role WHERE id = :id');
        return $stmt->execute([
            ':role' => $role,
            ':id' => $id,
        ]);
    }

    public function deleteUser(int $id): bool
    {
        $stmt = $this->conn->prepare('DELETE FROM users WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}
