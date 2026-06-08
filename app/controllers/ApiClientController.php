<?php
require_once BASE_PATH . '/app/config/database.php';
require_once BASE_PATH . '/app/models/CategoryModel.php';

class ApiClientController
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
        $title = 'Quản lý sản phẩm qua API';
        $categories = $this->categoryModel->getCategories();
        require BASE_PATH . '/app/views/api_client/index.php';
    }
}
