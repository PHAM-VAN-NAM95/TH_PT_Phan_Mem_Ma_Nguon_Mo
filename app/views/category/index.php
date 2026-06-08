<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>
<section class="container page-heading flex-between">
    <div>
        <span class="eyebrow">Admin</span>
        <h1>Quản lý danh mục</h1>
        <p>Thêm, sửa, xóa danh mục cho sản phẩm bánh ngọt.</p>
    </div>
    <a class="btn btn-primary" href="<?= url('Category/add') ?>">+ Thêm danh mục</a>
</section>
<section class="container page-card">
    <div class="table-responsive">
        <table class="data-table">
            <thead><tr><th>Mã</th><th>Tên danh mục</th><th>Mô tả</th><th>Số sản phẩm</th><th>Thao tác</th></tr></thead>
            <tbody>
            <?php foreach ($categories as $category): ?>
                <tr>
                    <td>#<?= e($category['id']) ?></td>
                    <td><strong><?= e($category['name']) ?></strong></td>
                    <td><?= e($category['description']) ?></td>
                    <td><?= e($category['product_count']) ?></td>
                    <td class="actions">
                        <a class="btn btn-sm btn-outline" href="<?= url('Category/edit/' . $category['id']) ?>">Sửa</a>
                        <form method="post" action="<?= url('Category/delete/' . $category['id']) ?>" onsubmit="return confirm('Xóa danh mục này?')">
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
