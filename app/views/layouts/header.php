<?php $currentUser = SessionHelper::currentUser(); ?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? 'SweetCake Shop') ?></title>
    <link rel="stylesheet" href="<?= asset('public/css/style.css') ?>">
</head>
<body>
<header class="site-header">
    <div class="container nav-wrap">
        <a class="brand" href="<?= url() ?>">
            <span class="brand-mark">SC</span>
            <span><b>SweetCake</b><small>Bakery Boutique</small></span>
        </a>

        <nav class="main-nav">
            <a href="<?= url() ?>">Trang chủ</a>
            <a href="<?= url('Product') ?>">Sản phẩm</a>
            <a href="<?= url('Cart') ?>"><span class="nav-icon">🛒</span> Giỏ hàng <span class="badge"><?= cart_count() ?></span></a>
            <?php if (SessionHelper::isLoggedIn()): ?>
                <a href="<?= url('Order/list') ?>">Đơn hàng</a>
            <?php endif; ?>
            <?php if (SessionHelper::isAdmin()): ?>
                <a href="<?= url('Product/manage') ?>">QL sản phẩm</a>
                <a href="<?= url('Category') ?>">QL danh mục</a>
                <a href="<?= url('User') ?>">QL tài khoản</a>
                <a href="<?= url('ApiClient') ?>">QL API</a>
            <?php endif; ?>
        </nav>

        <div class="auth-nav">
            <?php if ($currentUser): ?>
                <span class="user-pill"><?= e($currentUser['full_name']) ?> · <?= e($currentUser['role']) ?></span>
                <a class="btn btn-outline" href="<?= url('Auth/logout') ?>">Đăng xuất</a>
            <?php else: ?>
                <a class="btn btn-outline" href="<?= url('Auth/login') ?>">Đăng nhập</a>
                <a class="btn btn-primary" href="<?= url('Auth/register') ?>">Đăng ký</a>
            <?php endif; ?>
        </div>
    </div>
</header>

<main>
    <div class="container flash-wrap">
        <?php foreach (SessionHelper::getFlash() as $flash): ?>
            <div class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
        <?php endforeach; ?>
    </div>
