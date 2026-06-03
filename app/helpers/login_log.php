<?php
// app/helpers/login_log.php

class LoginLogger {
    private static $pdo;
    private static $max_attempts = 5;
    private static $lock_minutes = 30;
    
    public static function init($pdo) {
        self::$pdo = $pdo;
    }
    
    // Запись попытки входа
    public static function log($username, $success) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $stmt = self::$pdo->prepare("INSERT INTO login_attempts (ip, username, success) VALUES (?, ?, ?)");
        $stmt->execute([$ip, $username, (int)$success]);
    }
    
    // Проверка: заблокирован ли IP/юзер
    public static function isLocked($username = null) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $since = date('Y-m-d H:i:s', strtotime('-' . self::$lock_minutes . ' minutes'));
        
        // Считаем неудачные попытки
        $stmt = self::$pdo->prepare("
            SELECT COUNT(*) FROM login_attempts 
            WHERE ip = ? AND success = 0 AND attempt_time > ?
        ");
        $stmt->execute([$ip, $since]);
        $count = $stmt->fetchColumn();
        
        return $count >= self::$max_attempts;
    }
    
    // Очистка старых записей (запускать раз в день или по крону)
    public static function cleanup() {
        $old = date('Y-m-d H:i:s', strtotime('-7 days'));
        self::$pdo->prepare("DELETE FROM login_attempts WHERE attempt_time < ?")->execute([$old]);
    }
}
?>