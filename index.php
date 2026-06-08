<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('BASE_PATH', __DIR__);
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
define('BASE_URL', rtrim($scriptDir === '/' ? '' : $scriptDir, '/'));

require_once BASE_PATH . '/app/helpers/SessionHelper.php';
require_once BASE_PATH . '/app/helpers/ViewHelper.php';
SessionHelper::start();

$url = $_GET['url'] ?? '';
$url = trim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$segments = $url === '' ? [] : explode('/', $url);

try {
    if (isset($segments[0]) && strtolower($segments[0]) === 'api') {
        $resource = strtolower($segments[1] ?? '');
        if ($resource === 'products') {
            require_once BASE_PATH . '/app/controllers/ApiProductController.php';
            $controller = new ApiProductController();
            $id = $segments[2] ?? null;
            $controller->route($id);
            exit;
        }
        http_response_code(404);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => false, 'message' => 'API không tồn tại'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $controllerName = isset($segments[0]) && $segments[0] !== '' ? ucfirst($segments[0]) . 'Controller' : 'DefaultController';
    $action = isset($segments[1]) && $segments[1] !== '' ? $segments[1] : 'index';
    $params = array_slice($segments, 2);

    $controllerFile = BASE_PATH . '/app/controllers/' . $controllerName . '.php';
    if (!file_exists($controllerFile)) {
        http_response_code(404);
        $title = 'Không tìm thấy trang';
        require BASE_PATH . '/app/views/errors/404.php';
        exit;
    }

    require_once $controllerFile;
    $controller = new $controllerName();

    if (!method_exists($controller, $action)) {
        http_response_code(404);
        $title = 'Không tìm thấy chức năng';
        require BASE_PATH . '/app/views/errors/404.php';
        exit;
    }

    call_user_func_array([$controller, $action], $params);
} catch (Throwable $e) {
    http_response_code(500);
    $title = 'Lỗi hệ thống';
    $errorMessage = $e->getMessage();
    require BASE_PATH . '/app/views/errors/500.php';
}
