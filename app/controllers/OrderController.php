<?php
require_once BASE_PATH . '/app/config/database.php';
require_once BASE_PATH . '/app/models/ProductModel.php';
require_once BASE_PATH . '/app/models/OrderModel.php';

class OrderController
{
    private ProductModel $productModel;
    private OrderModel $orderModel;

    public function __construct()
    {
        $db = (new Database())->getConnection();
        $this->productModel = new ProductModel($db);
        $this->orderModel = new OrderModel($db);
    }

    public function checkout(): void
    {
        $title = 'Thanh toán';
        $cartItems = $this->getCartItems();
        if (empty($cartItems)) {
            SessionHelper::setFlash('warning', 'Giỏ hàng đang trống.');
            header('Location: ' . url('Cart'));
            exit;
        }

        $currentUser = SessionHelper::currentUser();
        $old = [
            'name' => $currentUser['full_name'] ?? '',
            'phone' => '',
            'phone2' => '',
            'address' => '',
            'note' => '',
        ];
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old = [
                'name' => trim($_POST['name'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
                'phone2' => trim($_POST['phone2'] ?? ''),
                'address' => trim($_POST['address'] ?? ''),
                'note' => trim($_POST['note'] ?? ''),
            ];

            if ($old['name'] === '') {
                $errors[] = 'Vui lòng nhập tên khách hàng.';
            }
            if ($old['phone'] === '') {
                $errors[] = 'Vui lòng nhập số điện thoại chính.';
            }
            if ($old['address'] === '') {
                $errors[] = 'Vui lòng nhập địa chỉ giao hàng.';
            }

            if (empty($errors)) {
                $userId = $currentUser['id'] ?? null;
                $orderId = $this->orderModel->createOrder($old, $cartItems, $userId ? (int)$userId : null);
                unset($_SESSION['cart']);
                SessionHelper::setFlash('success', 'Đặt hàng thành công. Mã đơn hàng của bạn là #' . $orderId . '.');
                header('Location: ' . url('Order/confirmation/' . $orderId));
                exit;
            }
        }

        require BASE_PATH . '/app/views/order/checkout.php';
    }

    public function confirmation($id): void
    {
        $order = $this->orderModel->getOrderById($id);
        if (!$order) {
            SessionHelper::setFlash('danger', 'Không tìm thấy đơn hàng.');
            header('Location: ' . url('Product'));
            exit;
        }
        $details = $this->orderModel->getOrderDetails($id);
        $title = 'Đặt hàng thành công';
        require BASE_PATH . '/app/views/order/confirmation.php';
    }

    public function list(): void
    {
        SessionHelper::requireLogin();
        $title = 'Danh sách đơn hàng';
        $currentUser = SessionHelper::currentUser();
        $orders = SessionHelper::isAdmin()
            ? $this->orderModel->getOrders()
            : $this->orderModel->getOrders((int)$currentUser['id']);
        require BASE_PATH . '/app/views/order/list.php';
    }

    public function detail($id): void
    {
        SessionHelper::requireLogin();
        $order = $this->orderModel->getOrderById($id);
        $currentUser = SessionHelper::currentUser();

        if (!$order || (!SessionHelper::isAdmin() && (int)$order['user_id'] !== (int)$currentUser['id'])) {
            SessionHelper::setFlash('danger', 'Bạn không có quyền xem đơn hàng này.');
            header('Location: ' . url('Order/list'));
            exit;
        }

        $details = $this->orderModel->getOrderDetails($id);
        $title = 'Chi tiết đơn hàng #' . $id;
        require BASE_PATH . '/app/views/order/detail.php';
    }

    private function getCartItems(): array
    {
        $items = [];
        foreach ($_SESSION['cart'] ?? [] as $productId => $quantity) {
            $product = $this->productModel->getProductById($productId);
            if (!$product) {
                continue;
            }
            $items[] = [
                'id' => (int)$product['id'],
                'name' => $product['name'],
                'price' => (float)$product['price'],
                'quantity' => (int)$quantity,
                'subtotal' => (float)$product['price'] * (int)$quantity,
                'image' => $product['image'],
            ];
        }
        return $items;
    }
}
