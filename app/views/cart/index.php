<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>
<section class="container page-heading">
    <div>
        <span class="eyebrow">Shopping cart</span>
        <h1>Giỏ hàng của bạn</h1>
        <p>Có thể tăng, giảm hoặc nhập trực tiếp số lượng. Số lượng phải là số nguyên lớn hơn 0.</p>
    </div>
</section>

<section class="container page-card">
    <?php if (empty($cartItems)): ?>
        <div class="empty-state">
            <h2>Giỏ hàng đang trống</h2>
            <p>Hãy chọn một vài chiếc bánh thật ngon nhé.</p>
            <a class="btn btn-primary" href="<?= url('Product') ?>">Tiếp tục mua bánh</a>
        </div>
    <?php else: ?>
        <?php $total = 0; ?>
        <form method="post" action="<?= url('Cart/update') ?>">
            <div class="table-responsive">
                <table class="data-table cart-table">
                    <thead><tr><th>Sản phẩm</th><th>Đơn giá</th><th>Số lượng</th><th>Tạm tính</th><th></th></tr></thead>
                    <tbody>
                    <?php foreach ($cartItems as $item): $total += $item['subtotal']; ?>
                        <tr>
                            <td class="cart-product">
                                <img class="table-img" src="<?= product_image($item['image']) ?>" alt="<?= e($item['name']) ?>">
                                <div><strong><?= e($item['name']) ?></strong><br><small><?= e($item['category_name'] ?? '') ?></small></div>
                            </td>
                            <td><?= money($item['price']) ?></td>
                            <td>
                                <div class="qty-control small">
                                    <button type="button" class="qty-btn" data-action="minus">−</button>
                                    <input type="number" name="quantities[<?= e($item['id']) ?>]" value="<?= e($item['quantity']) ?>" min="1" step="1" required>
                                    <button type="button" class="qty-btn" data-action="plus">+</button>
                                </div>
                            </td>
                            <td><strong><?= money($item['subtotal']) ?></strong></td>
                            <td>
                                <form></form>
                                <button formaction="<?= url('Cart/remove/' . $item['id']) ?>" formmethod="post" class="btn btn-sm btn-danger" onclick="return confirm('Xóa sản phẩm khỏi giỏ?')">Xóa</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="cart-summary">
                <div>
                    <button class="btn btn-outline" type="submit">Cập nhật số lượng</button>
                    <button class="btn btn-danger" formaction="<?= url('Cart/clear') ?>" formmethod="post" onclick="return confirm('Làm trống giỏ hàng?')">Làm trống</button>
                </div>
                <div class="total-box">
                    <span>Tổng tiền</span>
                    <strong><?= money($total) ?></strong>
                    <a class="btn btn-primary btn-lg" href="<?= url('Order/checkout') ?>">Thanh toán</a>
                </div>
            </div>
        </form>
    <?php endif; ?>
</section>
<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>
