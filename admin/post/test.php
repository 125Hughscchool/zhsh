<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require __DIR__ . '/path.php';
require __DIR__ . '/app/database/connect.php';

echo "<h2>🔍 Таблица команды</h2>";

// Проверяем, есть ли таблица
$tables = ['team', 'adm_team', 'admteams', 'members'];
$found = null;
foreach ($tables as $t) {
    $check = $pdo->query("SHOW TABLES LIKE '$t'")->fetch();
    if ($check) { $found = $t; break; }
}

if ($found) {
    echo "✅ Найдена таблица: <b>$found</b><br><br>";
    echo "<h3>Структура:</h3><pre>";
    $cols = $pdo->query("DESCRIBE $found")->fetchAll();
    foreach ($cols as $c) {
        echo $c['Field'] . " (" . $c['Type'] . ")<br>";
    }
    echo "</pre>";
    
    echo "<h3>Данные (первые 5):</h3><pre>";
    $data = $pdo->query("SELECT * FROM $found LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
    print_r($data);
    echo "</pre>";
} else {
    echo "❌ Таблица команды НЕ найдена!<br>";
    echo "Создай таблицу, например:<br><pre>";
    echo "CREATE TABLE team (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    position VARCHAR(255),
    img VARCHAR(255),
    status TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);";
    echo "</pre>";
}
?>