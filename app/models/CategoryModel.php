<?php
class CategoryModel
{
    private PDO $conn;

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    public function getCategories(): array
    {
        $sql = "SELECT c.*, COUNT(p.id) AS product_count
                FROM category c
                LEFT JOIN product p ON p.category_id = c.id
                GROUP BY c.id
                ORDER BY c.id DESC";
        return $this->conn->query($sql)->fetchAll();
    }

    public function getCategoryById($id): ?array
    {
        $stmt = $this->conn->prepare('SELECT * FROM category WHERE id = :id');
        $stmt->execute([':id' => (int)$id]);
        $category = $stmt->fetch();
        return $category ?: null;
    }

    public function addCategory(string $name, string $description): bool
    {
        $stmt = $this->conn->prepare('INSERT INTO category (name, description) VALUES (:name, :description)');
        return $stmt->execute([':name' => $name, ':description' => $description]);
    }

    public function updateCategory($id, string $name, string $description): bool
    {
        $stmt = $this->conn->prepare('UPDATE category SET name = :name, description = :description WHERE id = :id');
        return $stmt->execute([':id' => (int)$id, ':name' => $name, ':description' => $description]);
    }

    public function deleteCategory($id): bool
    {
        $stmt = $this->conn->prepare('DELETE FROM category WHERE id = :id');
        return $stmt->execute([':id' => (int)$id]);
    }

    public function validateCategory(string $name): array
    {
        $errors = [];
        if (trim($name) === '') {
            $errors[] = 'Tên danh mục là bắt buộc.';
        } elseif ((function_exists('mb_strlen') ? mb_strlen($name, 'UTF-8') : strlen($name)) > 100) {
            $errors[] = 'Tên danh mục tối đa 100 ký tự.';
        }
        return $errors;
    }
}
