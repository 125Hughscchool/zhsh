<?php
// 🔐 ВЫХОД ИЗ СИСТЕМЫ (чистый код)

// Относительный путь к корню (так как файл в корне, то просто ./)
require_once __DIR__ . '/path.php';

// Уничтожаем сессию
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();

// Редирект на главную
header('Location: ' . BASE_URL);
exit;
?>