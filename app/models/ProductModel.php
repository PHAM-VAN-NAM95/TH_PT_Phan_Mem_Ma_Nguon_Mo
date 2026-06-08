<?php
class ProductModel
{
    private PDO $conn;
    private string $table_name = 'product';

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    public function getProducts(string $keyword = '', $categoryId = null): array
    {
        $sql = "SELECT p.*, c.name AS category_name
                FROM {$this->table_name} p
                LEFT JOIN category c ON p.category_id = c.id
                WHERE 1=1";
        $params = [];

        if ($keyword !== '') {
            $sql .= " AND (p.name LIKE :keyword_name OR p.description LIKE :keyword_description)";
            $params[':keyword_name'] = '%' . $keyword . '%';
            $params[':keyword_description'] = '%' . $keyword . '%';
        }

        if ($categoryId !== null && $categoryId !== '') {
            $sql .= " AND p.category_id = :category_id";
            $params[':category_id'] = (int)$categoryId;
        }

        $sql .= " ORDER BY p.id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getProductById($id): ?array
    {
        $stmt = $this->conn->prepare("SELECT p.*, c.name AS category_name
            FROM {$this->table_name} p
            LEFT JOIN category c ON p.category_id = c.id
            WHERE p.id = :id");
        $stmt->execute([':id' => (int)$id]);
        $product = $stmt->fetch();
        return $product ?: null;
    }

    public function addProduct(string $name, string $description, float $price, ?string $image, $categoryId): bool
    {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table_name} (name, description, price, image, category_id)
            VALUES (:name, :description, :price, :image, :category_id)");
        return $stmt->execute([
            ':name' => $name,
            ':description' => $description,
            ':price' => $price,
            ':image' => $image,
            ':category_id' => $categoryId !== '' ? $categoryId : null,
        ]);
    }

    public function updateProduct($id, string $name, string $description, float $price, ?string $image, $categoryId): bool
    {
        $stmt = $this->conn->prepare("UPDATE {$this->table_name}
            SET name = :name, description = :description, price = :price, image = :image, category_id = :category_id
            WHERE id = :id");
        return $stmt->execute([
            ':id' => (int)$id,
            ':name' => $name,
            ':description' => $description,
            ':price' => $price,
            ':image' => $image,
            ':category_id' => $categoryId !== '' ? $categoryId : null,
        ]);
    }

    public function deleteProduct($id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table_name} WHERE id = :id");
        return $stmt->execute([':id' => (int)$id]);
    }

    public function validateProduct(string $name, $price): array
    {
        $errors = [];
        $length = function_exists('mb_strlen') ? mb_strlen($name, 'UTF-8') : strlen($name);

        if (trim($name) === '') {
            $errors[] = 'Tên sản phẩm là bắt buộc.';
        } elseif ($length < 10 || $length > 100) {
            $errors[] = 'Tên sản phẩm phải có từ 10 đến 100 ký tự.';
        }

        if (!is_numeric($price) || (float)$price <= 0) {
            $errors[] = 'Giá phải là số lớn hơn 0.';
        }

        return $errors;
    }
}
