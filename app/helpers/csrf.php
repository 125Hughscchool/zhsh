<?php
// app/helpers/csrf.php

class Csrf {
    // Генерация токена
    public static function generate() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    // Проверка токена
    public static function validate($token) {
        if (!isset($_SESSION['csrf_token']) || empty($token)) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }
    
    // Сброс токена (после успешной обработки)
    public static function reset() {
        unset($_SESSION['csrf_token']);
    }
    
    // Поле для вставки в форму
    public static function field() {
        return '<input type="hidden" name="csrf_token" value="' . self::generate() . '">';
    }
}
?>