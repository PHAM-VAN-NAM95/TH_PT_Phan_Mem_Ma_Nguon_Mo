<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>
<section class="container page-heading">
    <div>
        <span class="eyebrow">Menu SweetCake</span>
        <h1>Sản phẩm bánh ngọt</h1>
        <p>Khám phá toàn bộ sản phẩm và thêm nhanh vào giỏ hàng ngay từ danh sách.</p>
    </div>
</section>

<section class="container filter-card">
    <form method="get" action="<?= url('Product') ?>" class="filter-form">
        <input type="text" name="q" placeholder="Tìm bánh kem, cupcake, cookie..." value="<?= e($keyword ?? '') ?>">
        <select name="category_id">
            <option value="">Tất cả danh mục</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= e($category['id']) ?>" <?= is_selected($categoryId ?? '', $category['id']) ?>><?= e($category['name']) ?></option>
            <?php endforeach; ?>
        </select>
        <button class="btn btn-primary" type="submit">Tìm kiếm</button>
    </form>
</section>

<section class="container section-block">
    <?php if (empty($products)): ?>
        <div class="empty-state">Không tìm thấy sản phẩm phù hợp.</div>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <article class="product-card">
                    <a href="<?= url('Product/detail/' . $product['id']) ?>" class="product-image">
                        <img src="<?= product_image($product['image']) ?>" alt="<?= e($product['name']) ?>">
                    </a>
                    <div class="product-body">
                        <span class="category-chip"><?= e($product['category_name'] ?? 'Chưa phân loại') ?></span>
                        <h3><?= e($product['name']) ?></h3>
                        <p><?= e(mb_strimwidth($product['description'] ?? '', 0, 110, '...', 'UTF-8')) ?></p>
                        <div class="product-bottom column-bottom">
                            <strong><?= money($product['price']) ?></strong>
                            <div class="card-actions-row">
                                <form method="post" action="<?= url('Cart/add/' . $product['id']) ?>" class="inline-form">
                                    <input type="hidden" name="quantity" value="1">
                                    <button class="btn btn-sm btn-primary btn-icon-cart" type="submit" title="Thêm vào giỏ hàng" aria-label="Thêm vào giỏ hàng">🛒</button>
                                </form>
                                <a class="btn btn-sm btn-outline" href="<?= url('Product/detail/' . $product['id']) ?>">View</a>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>
