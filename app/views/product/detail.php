<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>
<section class="container detail-grid page-card">
    <div class="detail-image">
        <img src="<?= product_image($product['image']) ?>" alt="<?= e($product['name']) ?>">
    </div>
    <div class="detail-info">
        <span class="category-chip"><?= e($product['category_name'] ?? 'Chưa phân loại') ?></span>
        <h1><?= e($product['name']) ?></h1>
        <p class="detail-desc"><?= nl2br(e($product['description'])) ?></p>
        <div class="price-large"><?= money($product['price']) ?></div>
        <form action="<?= url('Cart/add/' . $product['id']) ?>" method="post" class="add-cart-form">
            <label>Số lượng</label>
            <div class="qty-control">
                <button type="button" class="qty-btn" data-action="minus">−</button>
                <input type="number" name="quantity" value="1" min="1" step="1" required>
                <button type="button" class="qty-btn" data-action="plus">+</button>
            </div>
            <button type="submit" class="btn btn-primary btn-lg"><span class="btn-cart-icon">🛒</span> Thêm vào giỏ hàng</button>
        </form>
    </div>
</section>
<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>
