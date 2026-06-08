<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>
<section class="container page-heading">
    <div>
        <span class="eyebrow">Orders</span>
        <h1>Danh sách đơn hàng</h1>
        <p>Mỗi đơn hàng hiển thị mã đơn, tên khách, số điện thoại, địa chỉ, tổng tiền và ngày đặt.</p>
    </div>
</section>
<section class="container page-card">
    <?php if (empty($orders)): ?>
        <div class="empty-state">Chưa có đơn hàng nào.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead><tr><th>Mã đơn</th><th>Tên khách</th><th>Số ĐT</th><th>Địa chỉ</th><th>Tổng tiền</th><th>Ngày đặt hàng</th><th>Thao tác</th></tr></thead>
                <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><strong>#<?= e($order['id']) ?></strong></td>
                        <td><?= e($order['name']) ?></td>
                        <td><?= e($order['phone']) ?><?= $order['phone2'] ? '<br><small>Phụ: ' . e($order['phone2']) . '</small>' : '' ?></td>
                        <td><?= e($order['address']) ?></td>
                        <td><strong><?= money($order['total_amount']) ?></strong></td>
                        <td><?= e($order['created_at']) ?></td>
                        <td><a class="btn btn-sm btn-outline" href="<?= url('Order/detail/' . $order['id']) ?>">Chi tiết</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>
