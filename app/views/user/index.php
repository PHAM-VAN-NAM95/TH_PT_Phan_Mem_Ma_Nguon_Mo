<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>
<section class="container page-heading flex-between">
    <div>
        <span class="eyebrow">Admin</span>
        <h1>Quản lý tài khoản</h1>
        <p>Theo dõi danh sách tài khoản khách hàng và quản trị viên trên hệ thống.</p>
    </div>
</section>

<section class="container page-card">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Họ tên</th>
                <th>Tên đăng nhập</th>
                <th>Email</th>
                <th>Vai trò</th>
                <th>Ngày tạo</th>
                <th>Thao tác</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td>#<?= e($user['id']) ?></td>
                    <td><strong><?= e($user['full_name']) ?></strong></td>
                    <td><?= e($user['username']) ?></td>
                    <td><?= e($user['email']) ?></td>
                    <td>
                        <span class="category-chip <?= ($user['role'] === 'admin') ? 'chip-admin' : '' ?>">
                            <?= $user['role'] === 'admin' ? 'Quản trị viên' : 'Khách hàng' ?>
                        </span>
                    </td>
                    <td><?= e($user['created_at']) ?></td>
                    <td class="actions">
                        <?php if (($user['username'] ?? '') !== 'admin'): ?>
                            <form method="post" action="<?= url('User/toggleRole/' . $user['id']) ?>">
                                <button class="btn btn-sm btn-outline" type="submit">
                                    <?= $user['role'] === 'admin' ? 'Chuyển user' : 'Cấp admin' ?>
                                </button>
                            </form>
                            <form method="post" action="<?= url('User/delete/' . $user['id']) ?>" onsubmit="return confirm('Bạn có chắc muốn xóa tài khoản này?')">
                                <button class="btn btn-sm btn-danger" type="submit">Xóa</button>
                            </form>
                        <?php else: ?>
                            <span class="muted-text">Tài khoản mặc định</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>
