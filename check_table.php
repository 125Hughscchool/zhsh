<?php
require __DIR__ . '/path.php';
require __DIR__ . '/app/database/connect.php';
global $pdo;

echo "<h2>📊 Структура таблицы s_posts:</h2>";
$cols = $pdo->query("DESCRIBE s_posts")->fetchAll(PDO::FETCH_ASSOC);
echo "<pre>";
foreach ($cols as $col) {
    print_r($col);
}
echo "</pre>";

echo "<h2>📋 Последние 5 записей:</h2>";
try {
    $posts = $pdo->query("SELECT * FROM s_posts ORDER BY id DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
    print_r($posts);
} catch (Exception $e) {
    echo "❌ Ошибка: " . $e->getMessage();
}
?>