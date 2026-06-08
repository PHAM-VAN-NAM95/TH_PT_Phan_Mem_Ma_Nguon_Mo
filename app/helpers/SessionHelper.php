<?php
class SessionHelper
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function isLoggedIn(): bool
    {
        self::start();
        return isset($_SESSION['user']);
    }

    public static function isAdmin(): bool
    {
        self::start();
        return isset($_SESSION['user']) && ($_SESSION['user']['role'] ?? '') === 'admin';
    }

    public static function currentUser(): ?array
    {
        self::start();
        return $_SESSION['user'] ?? null;
    }

    public static function requireLogin(): void
    {
        if (!self::isLoggedIn()) {
            self::setFlash('warning', 'Bạn cần đăng nhập để sử dụng chức năng này.');
            header('Location: ' . url('Auth/login'));
            exit;
        }
    }

    public static function requireAdmin(): void
    {
        if (!self::isAdmin()) {
            self::setFlash('danger', 'Bạn không có quyền truy cập chức năng quản trị.');
            header('Location: ' . url('Product'));
            exit;
        }
    }

    public static function setFlash(string $type, string $message): void
    {
        self::start();
        $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
    }

    public static function getFlash(): array
    {
        self::start();
        $flash = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);
        return $flash;
    }
}
