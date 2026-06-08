<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>
<section class="container page-heading">
    <div>
        <span class="eyebrow">Checkout</span>
        <h1>Thông tin đặt hàng</h1>
        <p>Thêm số điện thoại phụ và ghi chú giao hàng nếu cần.</p>
    </div>
</section>
<section class="container checkout-grid">
    <div class="form-card">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?><div><?= e($error) ?></div><?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form method="post" class="admin-form">
            <label>Tên khách hàng</label>
            <input type="text" name="name" value="<?= e($old['name']) ?>" required>
            <div class="form-grid">
                <div>
                    <label>Số điện thoại</label>
                    <input type="text" name="phone" value="<?= e($old['phone']) ?>" required>
                </div>
                <div>
                    <label>Số điện thoại phụ</label>
                    <input type="text" name="phone2" value="<?= e($old['phone2']) ?>" placeholder="Có thể bỏ trống">
                </div>
            </div>
            <label>Địa chỉ giao hàng</label>
            <input type="text" name="address" value="<?= e($old['address']) ?>" required>
            <label>Ghi chú</label>
            <textarea name="note" rows="4" placeholder="Ví dụ: giao sau 18h, ít ngọt, viết chữ lên bánh..."><?= e($old['note']) ?></textarea>
            <button class="btn btn-primary btn-lg" type="submit">Đặt hàng COD</button>
        </form>
    </div>
    <aside class="order-summary page-card">
        <h2>Tóm tắt đơn hàng</h2>
        <?php $total = 0; foreach ($cartItems as $item): $total += $item['subtotal']; ?>
            <div class="summary-item">
                <span><?= e($item['name']) ?> × <?= e($item['quantity']) ?></span>
                <strong><?= money($item['subtotal']) ?></strong>
            </div>
        <?php endforeach; ?>
        <div class="summary-total"><span>Tổng thanh toán</span><strong><?= money($total) ?></strong></div>
    </aside>
</section>
<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>
