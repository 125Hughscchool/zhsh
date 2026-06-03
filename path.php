<?php
// 🔥 Проверяем, запущена ли уже сессия
if (session_status() === PHP_SESSION_ACTIVE) {
    // Сессия уже запущена - ничего не делаем
} else {
    // Сессия НЕ запущена - настраиваем и запускаем
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    
    session_set_cookie_params([
        'lifetime' => 3600,
        'path' => '/',
        'domain' => '',
        'secure' => false,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    
    session_start();
}

// 🔧 Ошибки: показываем ТОЛЬКО на локалке
if (isset($_SERVER['REMOTE_ADDR']) && ($_SERVER['REMOTE_ADDR'] === '127.0.0.1' || $_SERVER['REMOTE_ADDR'] === '::1')) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    // На боевом сайте — скрываем ошибки!
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Константы
define('BASE_URL', 'https://kemel-urpaq.edu.kz/');
define('ROOT_PATH', __DIR__ . '/');

// Время
date_default_timezone_set('Asia/Almaty');
setlocale(LC_TIME, 'kk_KZ.UTF-8');

// 🔐 Подключение защитных классов (только если файлы существуют)
if (file_exists(__DIR__ . '/app/helpers/csrf.php')) {
    require_once __DIR__ . '/app/helpers/csrf.php';
}
if (file_exists(__DIR__ . '/app/helpers/login_log.php')) {
    require_once __DIR__ . '/app/helpers/login_log.php';
    // Инициализируем только если $pdo уже подключен
    if (isset($pdo)) {
        LoginLogger::init($pdo);
    }
}