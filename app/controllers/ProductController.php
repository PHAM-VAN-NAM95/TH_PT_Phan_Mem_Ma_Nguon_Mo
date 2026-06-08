<?php
require_once BASE_PATH . '/app/config/database.php';
require_once BASE_PATH . '/app/models/ProductModel.php';
require_once BASE_PATH . '/app/models/CategoryModel.php';

class ProductController
{
    private ProductModel $productModel;
    private CategoryModel $categoryModel;

    public function __construct()
    {
        $db = (new Database())->getConnection();
        $this->productModel = new ProductModel($db);
        $this->categoryModel = new CategoryModel($db);
    }

    public function index(): void
    {
        $title = 'Sản phẩm bánh ngọt';
        $keyword = trim($_GET['q'] ?? '');
        $categoryId = $_GET['category_id'] ?? null;
        $categories = $this->categoryModel->getCategories();
        $products = $this->productModel->getProducts($keyword, $categoryId);
        require BASE_PATH . '/app/views/product/list.php';
    }

    public function detail($id): void
    {
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            SessionHelper::setFlash('danger', 'Không tìm thấy sản phẩm.');
            header('Location: ' . url('Product'));
            exit;
        }
        $title = $product['name'];
        require BASE_PATH . '/app/views/product/detail.php';
    }

    public function manage(): void
    {
        SessionHelper::requireAdmin();
        $title = 'Quản lý sản phẩm';
        $products = $this->productModel->getProducts();
        require BASE_PATH . '/app/views/product/manage.php';
    }

    public function add(): void
    {
        SessionHelper::requireAdmin();
        $title = 'Thêm sản phẩm';
        $categories = $this->categoryModel->getCategories();
        $errors = [];
        $old = ['name' => '', 'description' => '', 'price' => '', 'category_id' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old = [
                'name' => trim($_POST['name'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'price' => $_POST['price'] ?? '',
                'category_id' => $_POST['category_id'] ?? '',
            ];
            $errors = $this->productModel->validateProduct($old['name'], $old['price']);
            $image = $this->uploadImage($errors);

            if (empty($errors)) {
                $this->productModel->addProduct($old['name'], $old['description'], (float)$old['price'], $image, $old['category_id']);
                SessionHelper::setFlash('success', 'Thêm sản phẩm thành công.');
                header('Location: ' . url('Product/manage'));
                exit;
            }
        }

        require BASE_PATH . '/app/views/product/form.php';
    }

    public function edit($id): void
    {
        SessionHelper::requireAdmin();
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            SessionHelper::setFlash('danger', 'Không tìm thấy sản phẩm cần sửa.');
            header('Location: ' . url('Product/manage'));
            exit;
        }

        $title = 'Sửa sản phẩm';
        $categories = $this->categoryModel->getCategories();
        $errors = [];
        $old = $product;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old = [
                'name' => trim($_POST['name'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'price' => $_POST['price'] ?? '',
                'category_id' => $_POST['category_id'] ?? '',
                'image' => $product['image'],
            ];
            $errors = $this->productModel->validateProduct($old['name'], $old['price']);
            $newImage = $this->uploadImage($errors);
            $image = $newImage ?: ($product['image'] ?? null);

            if (empty($errors)) {
                $this->productModel->updateProduct($id, $old['name'], $old['description'], (float)$old['price'], $image, $old['category_id']);
                SessionHelper::setFlash('success', 'Cập nhật sản phẩm thành công.');
                header('Location: ' . url('Product/manage'));
                exit;
            }
        }

        require BASE_PATH . '/app/views/product/form.php';
    }

    public function delete($id): void
    {
        SessionHelper::requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->productModel->deleteProduct($id);
            SessionHelper::setFlash('success', 'Đã xóa sản phẩm.');
        }
        header('Location: ' . url('Product/manage'));
        exit;
    }

    private function uploadImage(array &$errors): ?string
    {
        if (!isset($_FILES['image']) || ($_FILES['image']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Upload hình ảnh thất bại.';
            return null;
        }

        $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        $originalName = $_FILES['image']['name'];
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExt, true)) {
            $errors[] = 'Hình ảnh chỉ nhận jpg, jpeg, png, gif, webp hoặc svg.';
            return null;
        }

        if ($_FILES['image']['size'] > 3 * 1024 * 1024) {
            $errors[] = 'Dung lượng hình ảnh tối đa 3MB.';
            return null;
        }

        $uploadDir = BASE_PATH . '/public/uploads/products';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = 'cake_' . date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $destination = $uploadDir . '/' . $fileName;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
            $errors[] = 'Không thể lưu hình ảnh vào thư mục upload.';
            return null;
        }

        return 'public/uploads/products/' . $fileName;
    }
}
