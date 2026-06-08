<?php
require_once BASE_PATH . '/app/config/database.php';
require_once BASE_PATH . '/app/models/ProductModel.php';

class ApiProductController
{
    private ProductModel $productModel;

    public function __construct()
    {
        $db = (new Database())->getConnection();
        $this->productModel = new ProductModel($db);
    }

    public function route($id = null): void
    {
        header('Content-Type: application/json; charset=utf-8');
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET' && $id === null) {
            $this->index();
            return;
        }
        if ($method === 'GET' && $id !== null) {
            $this->show($id);
            return;
        }
        if ($method === 'POST' && $id === null) {
            $this->store();
            return;
        }
        if ($method === 'POST' && $id !== null) {
            $this->update($id);
            return;
        }
        if ($method === 'PUT' && $id !== null) {
            $this->update($id);
            return;
        }
        if ($method === 'DELETE' && $id !== null) {
            $this->destroy($id);
            return;
        }

        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ'], JSON_UNESCAPED_UNICODE);
    }

    private function index(): void
    {
        echo json_encode(['success' => true, 'data' => $this->productModel->getProducts()], JSON_UNESCAPED_UNICODE);
    }

    private function show($id): void
    {
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy sản phẩm'], JSON_UNESCAPED_UNICODE);
            return;
        }
        echo json_encode(['success' => true, 'data' => $product], JSON_UNESCAPED_UNICODE);
    }

    private function store(): void
    {
        $data = $this->inputData();
        $errors = $this->productModel->validateProduct(trim($data['name'] ?? ''), $data['price'] ?? null);
        if (!empty($errors)) {
            http_response_code(422);
            echo json_encode(['success' => false, 'errors' => $errors], JSON_UNESCAPED_UNICODE);
            return;
        }

        $this->productModel->addProduct(
            trim($data['name']),
            trim($data['description'] ?? ''),
            (float)$data['price'],
            $this->uploadImage(trim($data['image'] ?? '')),
            $data['category_id'] ?? null
        );
        http_response_code(201);
        echo json_encode(['success' => true, 'message' => 'Thêm sản phẩm qua API thành công'], JSON_UNESCAPED_UNICODE);
    }

    private function update($id): void
    {
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy sản phẩm'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $data = $this->inputData();
        $name = trim($data['name'] ?? $product['name']);
        $description = trim($data['description'] ?? $product['description']);
        $price = $data['price'] ?? $product['price'];
        $image = $this->uploadImage(trim($data['image'] ?? $product['image']));
        $categoryId = $data['category_id'] ?? $product['category_id'];
        $errors = $this->productModel->validateProduct($name, $price);

        if (!empty($errors)) {
            http_response_code(422);
            echo json_encode(['success' => false, 'errors' => $errors], JSON_UNESCAPED_UNICODE);
            return;
        }

        $this->productModel->updateProduct($id, $name, $description, (float)$price, $image, $categoryId);
        echo json_encode(['success' => true, 'message' => 'Cập nhật sản phẩm qua API thành công'], JSON_UNESCAPED_UNICODE);
    }

    private function destroy($id): void
    {
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy sản phẩm'], JSON_UNESCAPED_UNICODE);
            return;
        }
        $this->productModel->deleteProduct($id);
        echo json_encode(['success' => true, 'message' => 'Xóa sản phẩm qua API thành công'], JSON_UNESCAPED_UNICODE);
    }

    private function inputData(): array
    {
        if (!empty($_POST)) {
            return $_POST;
        }
        return $this->jsonInput();
    }

    private function uploadImage(?string $currentImage = null): ?string
    {
        if (!isset($_FILES['image']) || ($_FILES['image']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return $currentImage;
        }

        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            return $currentImage;
        }

        $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        $originalName = $_FILES['image']['name'] ?? '';
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExt, true)) {
            return $currentImage;
        }

        if (($_FILES['image']['size'] ?? 0) > 3 * 1024 * 1024) {
            return $currentImage;
        }

        $uploadDir = BASE_PATH . '/public/uploads/products';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = 'api_cake_' . date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $destination = $uploadDir . '/' . $fileName;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
            return $currentImage;
        }

        return 'public/uploads/products/' . $fileName;
    }

    private function jsonInput(): array
    {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);
        return is_array($data) ? $data : [];
    }
}
