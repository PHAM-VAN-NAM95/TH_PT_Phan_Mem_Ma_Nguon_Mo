<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>
<section class="container page-heading">
    <div>
        <span class="eyebrow">Admin</span>
        <h1><?= e($title) ?></h1>
    </div>
</section>
<section class="container form-card">
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <div><?= e($error) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="admin-form">
        <div class="form-grid">
            <div>
                <label>Tên sản phẩm</label>
                <input type="text" name="name" value="<?= e($old['name'] ?? '') ?>" required minlength="10" maxlength="100">
                <small>Tên sản phẩm bắt buộc, từ 10 đến 100 ký tự.</small>
            </div>
            <div>
                <label>Giá</label>
                <input type="number" name="price" value="<?= e($old['price'] ?? '') ?>" min="1" step="1" required>
            </div>
            <div>
                <label>Danh mục</label>
                <select name="category_id">
                    <option value="">-- Chọn danh mục --</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= e($category['id']) ?>" <?= is_selected($old['category_id'] ?? '', $category['id']) ?>>
                            <?= e($category['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label>Hình ảnh</label>
                <input type="file" name="image" accept="image/*" class="image-input" data-preview="#imagePreview">
                <small>JPG, PNG, GIF, WEBP, SVG. Tối đa 3MB.</small>
            </div>
        </div>

        <div>
            <label>Mô tả</label>
            <textarea name="description" rows="5"><?= e($old['description'] ?? '') ?></textarea>
        </div>

        <div class="preview-box">
            <span>Xem trước ảnh:</span>
            <img id="imagePreview" src="<?= product_image($old['image'] ?? '') ?>" alt="preview">
        </div>

        <div class="form-actions">
            <button class="btn btn-primary" type="submit">Lưu sản phẩm</button>
            <a class="btn btn-outline" href="<?= url('Product/manage') ?>">Quay lại</a>
        </div>
    </form>
</section>
<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>