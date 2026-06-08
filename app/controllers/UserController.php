<?php
require_once BASE_PATH . '/app/config/database.php';
require_once BASE_PATH . '/app/models/UserModel.php';

class UserController
{
    private UserModel $userModel;

    public function __construct()
    {
        $db = (new Database())->getConnection();
        $this->userModel = new UserModel($db);
    }

    public function index(): void
    {
        SessionHelper::requireAdmin();
        $title = 'Quản lý tài khoản';
        $users = $this->userModel->getAllUsers();
        require BASE_PATH . '/app/views/user/index.php';
    }

    public function toggleRole($id): void
    {
        SessionHelper::requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . url('User'));
            exit;
        }

        $id = (int)$id;
        $user = $this->userModel->findById($id);
        if (!$user) {
            SessionHelper::setFlash('danger', 'Không tìm thấy tài khoản cần cập nhật.');
            header('Location: ' . url('User'));
            exit;
        }

        if (($user['username'] ?? '') === 'admin') {
            SessionHelper::setFlash('warning', 'Không thể thay đổi quyền của tài khoản admin mặc định.');
            header('Location: ' . url('User'));
            exit;
        }

        $newRole = ($user['role'] ?? 'user') === 'admin' ? 'user' : 'admin';
        $this->userModel->updateRole($id, $newRole);
        SessionHelper::setFlash('success', 'Đã cập nhật quyền tài khoản thành ' . ($newRole === 'admin' ? 'quản trị viên.' : 'khách hàng.'));
        header('Location: ' . url('User'));
        exit;
    }

    public function delete($id): void
    {
        SessionHelper::requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . url('User'));
            exit;
        }

        $id = (int)$id;
        $user = $this->userModel->findById($id);
        $currentUser = SessionHelper::currentUser();
        if (!$user) {
            SessionHelper::setFlash('danger', 'Không tìm thấy tài khoản cần xóa.');
            header('Location: ' . url('User'));
            exit;
        }

        if (($user['username'] ?? '') === 'admin') {
            SessionHelper::setFlash('warning', 'Không thể xóa tài khoản admin mặc định.');
            header('Location: ' . url('User'));
            exit;
        }

        if ($currentUser && (int)$currentUser['id'] === $id) {
            SessionHelper::setFlash('warning', 'Bạn không thể tự xóa chính tài khoản đang đăng nhập.');
            header('Location: ' . url('User'));
            exit;
        }

        $this->userModel->deleteUser($id);
        SessionHelper::setFlash('success', 'Đã xóa tài khoản thành công.');
        header('Location: ' . url('User'));
        exit;
    }
}
