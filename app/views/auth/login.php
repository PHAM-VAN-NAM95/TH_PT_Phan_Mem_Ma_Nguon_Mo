<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>
<section class="auth-page">
    <div class="auth-layout">
        <div class="auth-card auth-card-info">
            <span class="eyebrow">SweetCake member</span>
            <h1>Chào mừng bạn quay lại</h1>
            <p>Đăng nhập để theo dõi đơn hàng, lưu thông tin mua sắm và sử dụng các tính năng dành cho khách hàng hoặc quản trị viên.</p>
            <ul class="auth-benefits">
                <li>Theo dõi lịch sử đơn hàng nhanh chóng</li>
                <li>Cập nhật giỏ hàng và thông tin giao nhận thuận tiện</li>
                <li>Quản lý sản phẩm, danh mục và tài khoản khi đăng nhập admin</li>
            </ul>
        </div>
        <div class="auth-card auth-card-form">
            <span class="eyebrow">Welcome back</span>
            <h2>Đăng nhập</h2>
            <p>Vui lòng nhập tài khoản hoặc email để tiếp tục.</p>
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger"><?php foreach ($errors as $error): ?><div><?= e($error) ?></div><?php endforeach; ?></div>
            <?php endif; ?>
            <form method="post" class="admin-form auth-form">
                <label>Tài khoản hoặc email</label>
                <input type="text" name="account" value="<?= e($old['account']) ?>" required>

                <label>Mật khẩu</label>
                <input type="password" name="password" required>

                <button class="btn btn-primary btn-lg auth-submit" type="submit">Đăng nhập</button>
            </form>
            <div class="auth-links">
                <a href="<?= url('Auth/register') ?>">Chưa có tài khoản? Đăng ký ngay</a>
            </div>
        </div>
    </div>
</section>
<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>
