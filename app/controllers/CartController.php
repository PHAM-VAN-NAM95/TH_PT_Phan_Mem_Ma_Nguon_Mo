<?php
require_once BASE_PATH . '/app/config/database.php';
require_once BASE_PATH . '/app/models/ProductModel.php';

class CartController
{
    private ProductModel $productModel;

    public function __construct()
    {
        $db = (new Database())->getConnection();
        $this->productModel = new ProductModel($db);
        $_SESSION['cart'] = $_SESSION['cart'] ?? [];
    }

    public function index(): void
    {
        $title = 'Giỏ hàng';
        $cartItems = $this->getCartItems();
        require BASE_PATH . '/app/views/cart/index.php';
    }

    public function add($productId): void
    {
        $product = $this->productModel->getProductById($productId);
        if (!$product) {
            SessionHelper::setFlash('danger', 'Sản phẩm không tồn tại.');
            header('Location: ' . url('Product'));
            exit;
        }

        $quantity = $_POST['quantity'] ?? 1;
        if (!$this->isPositiveInteger($quantity)) {
            SessionHelper::setFlash('danger', 'Số lượng sản phẩm phải là số nguyên lớn hơn 0.');
            header('Location: ' . url('Product/detail/' . $productId));
            exit;
        }

        $_SESSION['cart'][$productId] = ($_SESSION['cart'][$productId] ?? 0) + (int)$quantity;
        SessionHelper::setFlash('success', 'Đã thêm sản phẩm vào giỏ hàng.');
        header('Location: ' . url('Cart'));
        exit;
    }

    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . url('Cart'));
            exit;
        }

        $quantities = $_POST['quantities'] ?? [];
        foreach ($quantities as $productId => $quantity) {
            if (!$this->isPositiveInteger($quantity)) {
                SessionHelper::setFlash('danger', 'Số lượng sản phẩm phải là số nguyên lớn hơn 0. Vui lòng nhập lại.');
                header('Location: ' . url('Cart'));
                exit;
            }
        }

        foreach ($quantities as $productId => $quantity) {
            if (isset($_SESSION['cart'][$productId])) {
                $_SESSION['cart'][$productId] = (int)$quantity;
            }
        }

        SessionHelper::setFlash('success', 'Đã cập nhật giỏ hàng và tính lại tổng tiền.');
        header('Location: ' . url('Cart'));
        exit;
    }

    public function remove($productId): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            unset($_SESSION['cart'][$productId]);
            SessionHelper::setFlash('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
        }
        header('Location: ' . url('Cart'));
        exit;
    }

    public function clear(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            unset($_SESSION['cart']);
            SessionHelper::setFlash('success', 'Đã làm trống giỏ hàng.');
        }
        header('Location: ' . url('Cart'));
        exit;
    }

    private function isPositiveInteger($value): bool
    {
        return filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) !== false;
    }

    private function getCartItems(): array
    {
        $items = [];
        foreach ($_SESSION['cart'] ?? [] as $productId => $quantity) {
            $product = $this->productModel->getProductById($productId);
            if (!$product) {
                unset($_SESSION['cart'][$productId]);
                continue;
            }
            $product['quantity'] = (int)$quantity;
            $product['subtotal'] = (float)$product['price'] * (int)$quantity;
            $items[] = $product;
        }
        return $items;
    }
}
