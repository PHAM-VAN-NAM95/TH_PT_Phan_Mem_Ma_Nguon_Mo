<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>
<section class="container page-heading flex-between">
    <div>
        <span class="eyebrow">Admin</span>
        <h1>Quản lý sản phẩm</h1>
        <p>Thêm, sửa, xóa và quản lý hình ảnh sản phẩm bánh ngọt.</p>
    </div>
    <a class="btn btn-primary" href="<?= url('Product/add') ?>">+ Thêm sản phẩm</a>
</section>
<section class="container page-card">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
            <tr>
                <th>Ảnh</th>
                <th>Tên sản phẩm</th>
                <th>Danh mục</th>
                <th>Giá</th>
                <th>Ngày tạo</th>
                <th>Thao tác</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><img class="table-img" src="<?= product_image($product['image']) ?>" alt="<?= e($product['name']) ?>"></td>
                    <td><strong><?= e($product['name']) ?></strong><br><small><?= e(mb_substr($product['description'], 0, 70, 'UTF-8')) ?>...</small></td>
                    <td><?= e($product['category_name'] ?? 'Chưa phân loại') ?></td>
                    <td><?= money($product['price']) ?></td>
                    <td><?= e($product['created_at']) ?></td>
                    <td class="actions">
                        <a class="btn btn-sm btn-outline" href="<?= url('Product/edit/' . $product['id']) ?>">Sửa</a>
                        <form method="post" action="<?= url('Product/delete/' . $product['id']) ?>" onsubmit="return confirm('Xóa sản phẩm này?')">
                            <button class="btn btn-sm btn-danger" type="submit">Xóa</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>
