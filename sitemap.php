<?php
// Отключаем вывод ошибок в поток — они ломают XML
error_reporting(0);
ini_set('display_errors', 0);

// Подключаем БД
require __DIR__ . '/app/database/connect.php';

// Заголовки
header('Content-Type: application/xml; charset=utf-8');

$domain = 'https://kemel-urpaq.edu.kz';

// Начало XML с пространством имён
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

// Главная
echo '  <url>' . "\n";
echo '    <loc>' . $domain . '/</loc>' . "\n";
echo '    <lastmod>' . date('Y-m-d') . '</lastmod>' . "\n";
echo '    <changefreq>daily</changefreq>' . "\n";
echo '    <priority>1.0</priority>' . "\n";
echo '  </url>' . "\n";

// Контакты (если страница существует)
echo '  <url>' . "\n";
echo '    <loc>' . $domain . '/contacts</loc>' . "\n";
echo '    <changefreq>monthly</changefreq>' . "\n";
echo '    <priority>0.9</priority>' . "\n";
echo '  </url>' . "\n";

// Новости из БД
try {
    $stmt = $pdo->prepare("SELECT id, created_at, updated_at FROM posts WHERE status = 1 ORDER BY created_at DESC");
    $stmt->execute();
    while ($post = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $date = !empty($post['updated_at']) 
            ? date('Y-m-d', strtotime($post['updated_at']))
            : date('Y-m-d', strtotime($post['created_at']));
        
        echo '  <url>' . "\n";
        echo '    <loc>' . $domain . '/post.php?id=' . (int)$post['id'] . '</loc>' . "\n";
        echo '    <lastmod>' . $date . '</lastmod>' . "\n";
        echo '    <changefreq>monthly</changefreq>' . "\n";
        echo '    <priority>0.7</priority>' . "\n";
        echo '  </url>' . "\n";
    }
} catch (Exception $e) {
    // Тихо игнорируем ошибки БД, чтобы не ломать XML
}

// Корректное закрытие
echo '</urlset>' . "\n";
?>