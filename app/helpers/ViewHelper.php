<?php
function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function url(string $path = ''): string
{
    $path = ltrim($path, '/');
    return BASE_URL . ($path === '' ? '/' : '/' . $path);
}

function asset(string $path): string
{
    return url($path);
}

function money($amount): string
{
    return number_format((float)$amount, 0, ',', '.') . ' đ';
}

function product_image(?string $image): string
{
    $image = trim((string)$image);
    $photoMap = [
        'public/images/products/chocolate-cake.svg' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?auto=format&fit=crop&w=900&q=80',
        'public/images/products/strawberry-cake.svg' => 'https://images.unsplash.com/photo-1464349095431-e9a21285b5f3?auto=format&fit=crop&w=900&q=80',
        'public/images/products/tiramisu.svg' => 'https://images.unsplash.com/photo-1602351447937-745cb720612f?auto=format&fit=crop&w=900&q=80',
        'public/images/products/croissant.svg' => 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?auto=format&fit=crop&w=900&q=80',
        'public/images/products/brioche.svg' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=900&q=80',
        'public/images/products/cupcake.svg' => 'https://images.unsplash.com/photo-1486427944299-d1955d23e34d?auto=format&fit=crop&w=900&q=80',
        'public/images/products/macaron.svg' => 'https://images.unsplash.com/photo-1569864358642-9d1684040f43?auto=format&fit=crop&w=900&q=80',
        'public/images/products/cookie.svg' => 'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?auto=format&fit=crop&w=900&q=80',
        'public/images/products/default.svg' => 'https://images.unsplash.com/photo-1517433670267-08bbd4be890f?auto=format&fit=crop&w=900&q=80',
    ];

    if ($image === '') {
        $image = 'public/images/products/default.svg';
    }

    if (isset($photoMap[$image])) {
        return $photoMap[$image];
    }

    if (preg_match('/^https?:\/\//i', $image)) {
        return $image;
    }

    return asset($image);
}

function cart_count(): int
{
    $count = 0;
    foreach ($_SESSION['cart'] ?? [] as $qty) {
        $count += (int)$qty;
    }
    return $count;
}

function is_selected($a, $b): string
{
    return (string)$a === (string)$b ? 'selected' : '';
}
