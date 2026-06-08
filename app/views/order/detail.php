<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>
<section class="container page-heading">
    <div>
        <span class="eyebrow">Order detail</span>
        <h1>Chi tiết đơn hàng #<?= e($order['id']) ?></h1>
    </div>
</section>
<section class="container page-card">
    <div class="order-info-grid">
        <div><span>Tên khách</span><strong><?= e($order['name']) ?></strong></div>
        <div><span>Số điện thoại</span><strong><?= e($order['phone']) ?></strong></div>
        <div><span>Phone2</span><strong><?= e($order['phone2'] ?: 'Không có') ?></strong></div>
        <div><span>Ngày đặt</span><strong><?= e($order['created_at']) ?></strong></div>
        <div class="wide"><span>Địa chỉ</span><strong><?= e($order['address']) ?></strong></div>
        <div class="wide"><span>Ghi chú</span><strong><?= e($order['note'] ?: 'Không có') ?></strong></div>
    </div>
    <div class="table-responsive">
        <table class="data-table">
            <thead><tr><th>Sản phẩm</th><th>Đơn giá</th><th>Số lượng</th><th>Thành tiền</th></tr></thead>
            <tbody>
            <?php foreach ($details as $item): ?>
                <tr>
                    <td><?= e($item['product_name']) ?></td>
                    <td><?= money($item['price']) ?></td>
                    <td><?= e($item['quantity']) ?></td>
                    <td><strong><?= money($item['subtotal']) ?></strong></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="summary-total"><span>Tổng tiền</span><strong><?= money($order['total_amount']) ?></strong></div>
</section>
<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>
