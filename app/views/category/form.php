<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>
<section class="container page-heading">
    <div>
        <span class="eyebrow">Admin</span>
        <h1><?= e($title) ?></h1>
    </div>
</section>
<section class="container form-card small-form">
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?><div><?= e($error) ?></div><?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form method="post" class="admin-form">
        <label>Tên danh mục</label>
        <input type="text" name="name" value="<?= e($old['name'] ?? '') ?>" required maxlength="100">
        <label>Mô tả</label>
        <textarea name="description" rows="5"><?= e($old['description'] ?? '') ?></textarea>
        <div class="form-actions">
            <button class="btn btn-primary" type="submit">Lưu danh mục</button>
            <a class="btn btn-outline" href="<?= url('Category') ?>">Quay lại</a>
        </div>
    </form>
</section>
<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>
