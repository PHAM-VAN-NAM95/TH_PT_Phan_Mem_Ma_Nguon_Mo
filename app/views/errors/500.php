<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>
<section class="container page-card">
    <h1>Lỗi hệ thống</h1>
    <p>Website gặp lỗi khi xử lý yêu cầu.</p>
    <pre class="error-box"><?= e($errorMessage ?? 'Unknown error') ?></pre>
    <a class="btn btn-primary" href="<?= url('setup_database.php') ?>">Kiểm tra database</a>
</section>
<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>
