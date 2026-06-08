<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>
<section class="container page-card confirmation-card center-text">
    <div class="success-icon">✓</div>
    <h1>Đặt hàng thành công!</h1>
    <p>Cảm ơn bạn đã đặt bánh tại SweetCake Shop.</p>
    <h2>Mã đơn hàng: #<?= e($order['id']) ?></h2>
    <p><strong>Tên khách:</strong> <?= e($order['name']) ?> · <strong>SĐT:</strong> <?= e($order['phone']) ?></p>
    <p><strong>Địa chỉ:</strong> <?= e($order['address']) ?></p>
    <p><strong>Tổng tiền:</strong> <?= money($order['total_amount']) ?></p>
    <div class="form-actions center-actions">
        <a class="btn btn-primary" href="<?= url('Product') ?>">Tiếp tục mua bánh</a>
        <?php if (SessionHelper::isLoggedIn()): ?>
            <a class="btn btn-outline" href="<?= url('Order/list') ?>">Xem danh sách đơn hàng</a>
        <?php endif; ?>
    </div>
</section>
<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>
