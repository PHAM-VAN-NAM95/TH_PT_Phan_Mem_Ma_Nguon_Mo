<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$username = 'root';
$password = '';
$dbName = 'sweetcake_shop';

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `$dbName`");

    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(120) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        full_name VARCHAR(120) NOT NULL,
        role ENUM('admin','user') NOT NULL DEFAULT 'user',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $pdo->exec("CREATE TABLE IF NOT EXISTS category (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        description TEXT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $pdo->exec("CREATE TABLE IF NOT EXISTS product (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(150) NOT NULL,
        description TEXT NULL,
        price DECIMAL(12,2) NOT NULL DEFAULT 0,
        image VARCHAR(255) NULL,
        category_id INT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        CONSTRAINT fk_product_category FOREIGN KEY (category_id) REFERENCES category(id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $pdo->exec("CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NULL,
        name VARCHAR(120) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        phone2 VARCHAR(20) NULL,
        address VARCHAR(255) NOT NULL,
        note TEXT NULL,
        total_amount DECIMAL(12,2) NOT NULL DEFAULT 0,
        status VARCHAR(30) NOT NULL DEFAULT 'new',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $pdo->exec("CREATE TABLE IF NOT EXISTS order_details (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT NOT NULL,
        product_id INT NULL,
        product_name VARCHAR(150) NOT NULL,
        quantity INT NOT NULL,
        price DECIMAL(12,2) NOT NULL,
        subtotal DECIMAL(12,2) NOT NULL,
        CONSTRAINT fk_order_details_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
        CONSTRAINT fk_order_details_product FOREIGN KEY (product_id) REFERENCES product(id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $categoryCount = (int)$pdo->query("SELECT COUNT(*) FROM category")->fetchColumn();
    if ($categoryCount === 0) {
        $stmt = $pdo->prepare("INSERT INTO category (name, description) VALUES (?, ?)");
        $categories = [
            ['Bánh kem', 'Các loại bánh kem sinh nhật, bánh mousse, bánh lạnh.'],
            ['Bánh mì ngọt', 'Bánh mì bơ sữa, croissant, bánh cuộn mềm.'],
            ['Cupcake', 'Cupcake nhỏ xinh, phù hợp tiệc sinh nhật và quà tặng.'],
            ['Bánh quy', 'Cookie, macaron và bánh quy handmade.'],
        ];
        foreach ($categories as $cat) {
            $stmt->execute($cat);
        }
    }

    $productCount = (int)$pdo->query("SELECT COUNT(*) FROM product")->fetchColumn();
    if ($productCount === 0) {
        $stmt = $pdo->prepare("INSERT INTO product (name, description, price, image, category_id) VALUES (?, ?, ?, ?, ?)");
        $products = [
            ['Bánh kem chocolate premium', 'Bánh chocolate nhiều lớp, phủ ganache thơm béo, phù hợp sinh nhật.', 289000, 'public/images/products/chocolate-cake.svg', 1],
            ['Bánh kem dâu tây tươi', 'Bánh bông lan mềm, kem tươi ít ngọt, trang trí dâu tây mọng nước.', 259000, 'public/images/products/strawberry-cake.svg', 1],
            ['Tiramisu hộp gia đình', 'Vị cà phê dịu, mascarpone béo nhẹ, dùng lạnh ngon hơn.', 179000, 'public/images/products/tiramisu.svg', 1],
            ['Croissant bơ Pháp giòn thơm', 'Lớp vỏ giòn, ruột xốp, mùi bơ thơm đặc trưng.', 39000, 'public/images/products/croissant.svg', 2],
            ['Bánh mì hoa cúc mini', 'Bánh mềm, thơm bơ sữa, phù hợp ăn sáng hoặc ăn nhẹ.', 45000, 'public/images/products/brioche.svg', 2],
            ['Cupcake vani kem bơ', 'Cupcake nhỏ xinh, kem bơ mịn, trang trí đơn giản tinh tế.', 35000, 'public/images/products/cupcake.svg', 3],
            ['Macaron hộp 6 vị', 'Macaron giòn nhẹ bên ngoài, nhân kem mềm, nhiều màu sắc.', 99000, 'public/images/products/macaron.svg', 4],
            ['Cookie chocolate chip', 'Cookie giòn rìa, mềm giữa, nhiều chip chocolate.', 69000, 'public/images/products/cookie.svg', 4],
        ];
        foreach ($products as $product) {
            $stmt->execute($product);
        }
    }

    $adminExists = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $adminExists->execute(['admin']);
    if ((int)$adminExists->fetchColumn() === 0) {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, full_name, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute(['admin', 'admin@sweetcake.local', password_hash('admin123', PASSWORD_DEFAULT), 'Quản trị viên SweetCake', 'admin']);
    }

    $userExists = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $userExists->execute(['user']);
    if ((int)$userExists->fetchColumn() === 0) {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, full_name, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute(['user', 'user@sweetcake.local', password_hash('user123', PASSWORD_DEFAULT), 'Khách hàng mẫu', 'user']);
    }

    echo '<!doctype html><html lang="vi"><head><meta charset="utf-8"><title>Setup SweetCake</title>';
    echo '<style>body{font-family:Arial,sans-serif;background:#fff7f2;color:#2b1b13;padding:40px}.box{max-width:760px;margin:auto;background:white;border-radius:24px;padding:32px;box-shadow:0 20px 60px rgba(120,72,48,.15)}a{display:inline-block;margin-top:16px;background:#8b4a2f;color:white;padding:12px 18px;border-radius:12px;text-decoration:none}.ok{color:#15803d;font-weight:bold}</style></head><body><div class="box">';
    echo '<h1 class="ok">✅ Setup database thành công!</h1>';
    echo '<p>Đã tạo database <b>sweetcake_shop</b>, bảng dữ liệu và dữ liệu mẫu.</p>';
    echo '<p><b>Admin:</b> admin / admin123<br><b>User:</b> user / user123</p>';
    echo '<a href="index.php">Vào website SweetCake Shop</a>';
    echo '</div></body></html>';
} catch (PDOException $e) {
    echo '<h2>Lỗi setup database</h2><pre>' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</pre>';
}
