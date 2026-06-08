<?php
require_once BASE_PATH . '/app/config/database.php';
require_once BASE_PATH . '/app/models/CategoryModel.php';

class CategoryController
{
    private CategoryModel $categoryModel;

    public function __construct()
    {
        $db = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($db);
    }

    public function index(): void
    {
        SessionHelper::requireAdmin();
        $title = 'Quản lý danh mục';
        $categories = $this->categoryModel->getCategories();
        require BASE_PATH . '/app/views/category/index.php';
    }

    public function add(): void
    {
        SessionHelper::requireAdmin();
        $title = 'Thêm danh mục';
        $errors = [];
        $old = ['name' => '', 'description' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old['name'] = trim($_POST['name'] ?? '');
            $old['description'] = trim($_POST['description'] ?? '');
            $errors = $this->categoryModel->validateCategory($old['name']);
            if (empty($errors)) {
                $this->categoryModel->addCategory($old['name'], $old['description']);
                SessionHelper::setFlash('success', 'Thêm danh mục thành công.');
                header('Location: ' . url('Category'));
                exit;
            }
        }

        require BASE_PATH . '/app/views/category/form.php';
    }

    public function edit($id): void
    {
        SessionHelper::requireAdmin();
        $category = $this->categoryModel->getCategoryById($id);
        if (!$category) {
            SessionHelper::setFlash('danger', 'Không tìm thấy danh mục.');
            header('Location: ' . url('Category'));
            exit;
        }

        $title = 'Sửa danh mục';
        $errors = [];
        $old = $category;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old['name'] = trim($_POST['name'] ?? '');
            $old['description'] = trim($_POST['description'] ?? '');
            $errors = $this->categoryModel->validateCategory($old['name']);
            if (empty($errors)) {
                $this->categoryModel->updateCategory($id, $old['name'], $old['description']);
                SessionHelper::setFlash('success', 'Cập nhật danh mục thành công.');
                header('Location: ' . url('Category'));
                exit;
            }
        }

        require BASE_PATH . '/app/views/category/form.php';
    }

    public function delete($id): void
    {
        SessionHelper::requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->categoryModel->deleteCategory($id);
            SessionHelper::setFlash('success', 'Đã xóa danh mục. Sản phẩm thuộc danh mục này sẽ chuyển sang không có danh mục.');
        }
        header('Location: ' . url('Category'));
        exit;
    }
}
