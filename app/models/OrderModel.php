<?php
class OrderModel
{
    private PDO $conn;

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    public function createOrder(array $customer, array $items, ?int $userId = null): int
    {
        $total = 0;
        foreach ($items as $item) {
            $total += $item['subtotal'];
        }

        $this->conn->beginTransaction();
        try {
            $stmt = $this->conn->prepare('INSERT INTO orders (user_id, name, phone, phone2, address, note, total_amount)
                VALUES (:user_id, :name, :phone, :phone2, :address, :note, :total_amount)');
            $stmt->execute([
                ':user_id' => $userId,
                ':name' => $customer['name'],
                ':phone' => $customer['phone'],
                ':phone2' => $customer['phone2'] ?? null,
                ':address' => $customer['address'],
                ':note' => $customer['note'] ?? null,
                ':total_amount' => $total,
            ]);

            $orderId = (int)$this->conn->lastInsertId();
            $detailStmt = $this->conn->prepare('INSERT INTO order_details (order_id, product_id, product_name, quantity, price, subtotal)
                VALUES (:order_id, :product_id, :product_name, :quantity, :price, :subtotal)');

            foreach ($items as $item) {
                $detailStmt->execute([
                    ':order_id' => $orderId,
                    ':product_id' => $item['id'],
                    ':product_name' => $item['name'],
                    ':quantity' => $item['quantity'],
                    ':price' => $item['price'],
                    ':subtotal' => $item['subtotal'],
                ]);
            }

            $this->conn->commit();
            return $orderId;
        } catch (Throwable $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    public function getOrders(?int $userId = null): array
    {
        $sql = 'SELECT o.*, u.username FROM orders o LEFT JOIN users u ON u.id = o.user_id';
        $params = [];
        if ($userId !== null) {
            $sql .= ' WHERE o.user_id = :user_id';
            $params[':user_id'] = $userId;
        }
        $sql .= ' ORDER BY o.id DESC';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getOrderById($id): ?array
    {
        $stmt = $this->conn->prepare('SELECT o.*, u.username FROM orders o LEFT JOIN users u ON u.id = o.user_id WHERE o.id = :id');
        $stmt->execute([':id' => (int)$id]);
        $order = $stmt->fetch();
        return $order ?: null;
    }

    public function getOrderDetails($orderId): array
    {
        $stmt = $this->conn->prepare('SELECT * FROM order_details WHERE order_id = :order_id ORDER BY id ASC');
        $stmt->execute([':order_id' => (int)$orderId]);
        return $stmt->fetchAll();
    }
}
