<?php
// Включаем показ ВСЕХ ошибок
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

echo "🔍 DEBUG MODE ON<br>";
echo "PHP Version: " . phpversion() . "<br>";

// Проверяем, существует ли path.php
if (file_exists(__DIR__ . '/path.php')) {
    echo "✅ path.php найден<br>";
    require __DIR__ . '/path.php';
    echo "✅ path.php подключен, BASE_URL = " . (defined('BASE_URL') ? BASE_URL : 'не определена') . "<br>";
} else {
    echo "❌ path.php НЕ найден!<br>";
}

// Проверяем подключение к БД
if (file_exists(__DIR__ . '/app/database/connect.php')) {
    echo "✅ connect.php найден<br>";
    require __DIR__ . '/app/database/connect.php';
    if (isset($pdo)) {
        echo "✅ PDO подключен к БД!<br>";
    } else {
        echo "❌ PDO НЕ подключен (переменная \$pdo не создана)<br>";
    }
} else {
    echo "❌ connect.php НЕ найден!<br>";
}

echo "<br>🎯 Если видишь этот текст — значит, базовые файлы работают.<br>";
echo "Теперь открывай index.php и смотри, где обрыв.";
?>