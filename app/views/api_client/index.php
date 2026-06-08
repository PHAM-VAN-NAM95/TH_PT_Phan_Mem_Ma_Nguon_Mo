<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>
<section class="container page-heading">
    <div>
        <span class="eyebrow">Admin workspace</span>
        <h1>Quản lý sản phẩm qua API</h1>
        <p>Khu vực thao tác sản phẩm bằng REST API với giao diện quản trị gọn gàng, dễ sử dụng.</p>
    </div>
</section>
<section class="container api-grid">
    <div class="form-card">
        <div class="section-title section-title-small">
            <div>
                <span class="eyebrow">Biểu mẫu</span>
                <h2>Thêm / cập nhật sản phẩm</h2>
            </div>
        </div>
        <form id="apiForm" class="admin-form">
            <input type="hidden" id="apiProductId">
            <label>Tên sản phẩm</label>
            <input type="text" id="apiName" required minlength="10" maxlength="100">

            <label>Mô tả</label>
            <textarea id="apiDescription" rows="4"></textarea>

            <div class="form-grid">
                <div>
                    <label>Giá</label>
                    <input type="number" id="apiPrice" min="1" step="1000" required>
                </div>
                <div>
                    <label>Danh mục</label>
                    <select id="apiCategoryId">
                        <option value="">Chọn danh mục</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= e($category['id']) ?>"><?= e($category['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <label>Ảnh sản phẩm</label>
            <div class="api-upload-box">
                <input type="hidden" id="apiImage" value="public/images/products/default.svg">
                <input type="file" id="apiImageFile" accept="image/*">
                <div class="api-upload-preview">
                    <img id="apiImagePreview" src="<?= product_image('public/images/products/default.svg') ?>" alt="Xem trước ảnh sản phẩm">
                    <span>Chọn ảnh bánh từ máy tính</span>
                </div>
            </div>

            <div class="form-actions">
                <button class="btn btn-primary" type="submit">Lưu sản phẩm</button>
                <button class="btn btn-outline" type="button" id="resetApiForm">Làm mới biểu mẫu</button>
            </div>
        </form>
        <div id="apiMessage" class="api-message"></div>
    </div>

    <div class="page-card">
        <div class="section-title section-title-small">
            <div>
                <span class="eyebrow">Danh sách</span>
                <h2>Sản phẩm đồng bộ từ API</h2>
            </div>
        </div>
        <div class="table-responsive">
            <table class="data-table" id="apiProductTable">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Giá</th>
                    <th>Thao tác</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</section>
<script>
window.SWEETCAKE_API_URL = '<?= url('api/products') ?>';
window.SWEETCAKE_BASE_URL = '<?= url() ?>';
</script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="<?= asset('public/js/api-client.js') ?>"></script>
<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>
