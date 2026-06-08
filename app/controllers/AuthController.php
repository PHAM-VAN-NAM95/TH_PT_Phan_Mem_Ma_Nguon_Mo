<?php
require_once BASE_PATH . '/app/config/database.php';
require_once BASE_PATH . '/app/models/UserModel.php';

class AuthController
{
    private UserModel $userModel;

    public function __construct()
    {
        $db = (new Database())->getConnection();
        $this->userModel = new UserModel($db);
    }

    public function login(): void
    {
        $title = 'Đăng nhập';
        $errors = [];
        $old = ['account' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old['account'] = trim($_POST['account'] ?? '');
            $password = $_POST['password'] ?? '';
            $user = $this->userModel->findByUsernameOrEmail($old['account']);

            if (!$user || !password_verify($password, $user['password'])) {
                $errors[] = 'Tài khoản hoặc mật khẩu không đúng.';
            } else {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'full_name' => $user['full_name'],
                    'role' => $user['role'],
                ];
                SessionHelper::setFlash('success', 'Đăng nhập thành công. Xin chào ' . $user['full_name'] . '!');
                header('Location: ' . url('Product'));
                exit;
            }
        }

        require BASE_PATH . '/app/views/auth/login.php';
    }

    public function register(): void
    {
        $title = 'Đăng ký';
        $errors = [];
        $old = ['username' => '', 'email' => '', 'full_name' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old['username'] = trim($_POST['username'] ?? '');
            $old['email'] = trim($_POST['email'] ?? '');
            $old['full_name'] = trim($_POST['full_name'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';

            if ($old['full_name'] === '') {
                $errors[] = 'Vui lòng nhập họ tên.';
            }
            if ($old['username'] === '' || strlen($old['username']) < 3) {
                $errors[] = 'Tên đăng nhập phải có ít nhất 3 ký tự.';
            } elseif ($this->userModel->findByUsername($old['username'])) {
                $errors[] = 'Tên đăng nhập đã tồn tại.';
            }
            if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Email không hợp lệ.';
            } elseif ($this->userModel->findByEmail($old['email'])) {
                $errors[] = 'Email đã được sử dụng.';
            }
            if (strlen($password) < 6) {
                $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự.';
            }
            if ($password !== $confirm) {
                $errors[] = 'Mật khẩu nhập lại không khớp.';
            }

            if (empty($errors)) {
                $this->userModel->create($old['username'], $old['email'], $password, $old['full_name'], 'user');
                SessionHelper::setFlash('success', 'Đăng ký thành công. Bạn có thể đăng nhập.');
                header('Location: ' . url('Auth/login'));
                exit;
            }
        }

        require BASE_PATH . '/app/views/auth/register.php';
    }

    public function logout(): void
    {
        unset($_SESSION['user']);
        SessionHelper::setFlash('success', 'Đã đăng xuất.');
        header('Location: ' . url('Product'));
        exit;
    }
}
