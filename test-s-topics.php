<?php
require __DIR__ . '/path.php';
require __DIR__ . '/app/database/connect.php';
require __DIR__ . '/app/database/db.php';

echo "<h2>🔍 Быстрый тест s_topics:</h2>";

$topics = selectAll('s_topics');
echo "<p>Найдено: <b>" . count($topics) . "</b></p>";

foreach ($topics as $t) {
    echo "<p>✅ #{$t['id']}: <b>" . htmlspecialchars($t['name']) . "</b></p>";
}
?>