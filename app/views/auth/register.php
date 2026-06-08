<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>
<section class="auth-page">
    <div class="auth-layout auth-layout-register">
        <div class="auth-card auth-card-info">
            <span class="eyebrow">Create account</span>
            <h1>Đăng ký khách hàng mới</h1>
            <p>Tạo tài khoản để đặt bánh nhanh hơn, lưu thông tin nhận hàng và theo dõi các đơn hàng đã mua tại SweetCake Shop.</p>
            <ul class="auth-benefits">
                <li>Mua bánh nhanh với thông tin giao hàng đã lưu</li>
                <li>Dễ dàng xem lịch sử và chi tiết đơn hàng</li>
                <li>Trải nghiệm mua sắm chuyên nghiệp hơn trên website</li>
            </ul>
        </div>
        <div class="auth-card auth-card-form wide-auth">
            <span class="eyebrow">Customer account</span>
            <h2>Đăng ký</h2>
            <p>Điền đầy đủ thông tin bên dưới để tạo tài khoản mới.</p>
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger"><?php foreach ($errors as $error): ?><div><?= e($error) ?></div><?php endforeach; ?></div>
            <?php endif; ?>
            <form method="post" class="admin-form auth-form">
                <label>Họ tên</label>
                <input type="text" name="full_name" value="<?= e($old['full_name']) ?>" required>
                <div class="form-grid">
                    <div>
                        <label>Tên đăng nhập</label>
                        <input type="text" name="username" value="<?= e($old['username']) ?>" required minlength="3">
                    </div>
                    <div>
                        <label>Email</label>
                        <input type="email" name="email" value="<?= e($old['email']) ?>" required>
                    </div>
                    <div>
                        <label>Mật khẩu</label>
                        <input type="password" name="password" required minlength="6">
                    </div>
                    <div>
                        <label>Nhập lại mật khẩu</label>
                        <input type="password" name="confirm_password" required minlength="6">
                    </div>
                </div>
                <button class="btn btn-primary btn-lg auth-submit" type="submit">Tạo tài khoản</button>
            </form>
            <div class="auth-links">
                <a href="<?= url('Auth/login') ?>">Đã có tài khoản? Đăng nhập</a>
            </div>
        </div>
    </div>
</section>
<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>
