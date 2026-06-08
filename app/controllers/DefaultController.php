<?php
require_once BASE_PATH . '/app/config/database.php';
require_once BASE_PATH . '/app/models/ProductModel.php';
require_once BASE_PATH . '/app/models/CategoryModel.php';

class DefaultController
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
        $title = 'SweetCake Shop - Bánh ngọt mỗi ngày';
        $products = $this->productModel->getProducts();
        $categories = $this->categoryModel->getCategories();
        $heroProducts = array_slice($products, 0, 3);
        require BASE_PATH . '/app/views/home/index.php';
    }
}
