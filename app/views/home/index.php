<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>
<section class="hero hero-clean">
    <div class="container hero-grid hero-grid-clean">
        <div class="hero-text">
            <span class="eyebrow">Bakery handmade mỗi ngày</span>
            <h1>Ngọt ngào trong từng lát bánh, tinh tế trong từng khoảnh khắc.</h1>
            <p>SweetCake Shop mang đến bánh kem, tiramisu, cupcake và bánh quy được làm mới mỗi ngày. Mỗi chiếc bánh được chăm chút từ hương vị đến hình thức để phù hợp cho sinh nhật, tiệc nhỏ và quà tặng.</p>
            <div class="hero-actions">
                <a class="btn btn-primary btn-lg" href="<?= url('Product') ?>">Mua bánh ngay</a>
                <a class="btn btn-outline btn-lg" href="<?= url('Cart') ?>"><span class="btn-cart-icon">🛒</span> Xem giỏ hàng</a>
            </div>
            <div class="hero-trust">
                <div><strong>Fresh</strong><span>Bánh làm mới mỗi ngày</span></div>
                <div><strong>COD</strong><span>Thanh toán khi nhận</span></div>
                <div><strong>Fast</strong><span>Giao hàng nội thành</span></div>
            </div>
        </div>
        <div class="hero-symbol-card" aria-label="Hình ảnh minh họa cửa hàng bánh SweetCake">
            <div class="bakery-window">
                <div class="awning"><span></span><span></span><span></span><span></span></div>
                <div class="window-glass">
                    <div class="cake-stand">
                        <div class="cake-layer layer-top"></div>
                        <div class="cake-layer layer-mid"></div>
                        <div class="cake-layer layer-bottom"></div>
                        <div class="cake-cherry"></div>
                    </div>
                    <div class="cupcake-symbol"><span></span></div>
                    <div class="cookie-symbol"><span></span><i></i><b></b></div>
                </div>
                <div class="shop-label">SweetCake Boutique</div>
            </div>
        </div>
    </div>
</section>

<section class="container stats-strip">
    <div><strong><?= count($products) ?>+</strong><span>Mẫu bánh đang bán</span></div>
    <div><strong><?= count($categories) ?></strong><span>Danh mục bánh ngọt</span></div>
    <div><strong>Daily</strong><span>Làm mới bánh mỗi ngày</span></div>
    <div><strong>Fast</strong><span>Giao hàng nội thành nhanh chóng</span></div>
</section>

<section class="container section-block">
    <div class="section-title">
        <div>
            <span class="eyebrow">SweetCake collection</span>
            <h2>Toàn bộ sản phẩm</h2>
        </div>
        <a class="btn btn-outline" href="<?= url('Product') ?>">Xem trang sản phẩm</a>
    </div>
    <?php if (empty($products)): ?>
        <div class="empty-state">Hiện chưa có sản phẩm nào.</div>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <article class="product-card">
                    <a href="<?= url('Product/detail/' . $product['id']) ?>" class="product-image">
                        <img src="<?= product_image($product['image']) ?>" alt="<?= e($product['name']) ?>">
                    </a>
                    <div class="product-body">
                        <span class="category-chip"><?= e($product['category_name'] ?? 'Bánh ngọt') ?></span>
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
