<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require __DIR__ . '/path.php';
require __DIR__ . '/app/database/connect.php';
require_once __DIR__ . '/app/database/db.php';

$page = $_GET['page'] ?? 1;
$limit = 4;
$offset = $limit * ($page - 1);

$newposts = [];
$total_pages = 1;
try {
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE status = 1 ORDER BY id DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    $newposts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $total_pages = max(1, ceil($pdo->query("SELECT COUNT(*) FROM posts WHERE status = 1")->fetchColumn() / $limit));
} catch (PDOException $e) { /* тихо */ }

// === ШАБЛОНЫ С МЕТКАМИ ===
include __DIR__ . '/include/head.php';
echo '<!-- ✅ [1] head.php OK -->';

include __DIR__ . '/include/header.php';
echo '<!-- ✅ [2] header.php OK -->';

include __DIR__ . '/include/sprite.php';
echo '<!-- ✅ [3] sprite.php OK -->';

include __DIR__ . '/include/intro.php';
echo '<!-- ✅ [4] intro.php OK -->';

include __DIR__ . '/include/section.php';
echo '<!-- ✅ [5] section.php OK -->';

include __DIR__ . '/include/news.php';
echo '<!-- ✅ [6] news.php OK -->';

include __DIR__ . '/include/director.php';
echo '<!-- ✅ [7] director.php OK -->';

include __DIR__ . '/include/team.php';
echo '<!-- ✅ [8] team.php OK -->';

include __DIR__ . '/include/resource.php';
echo '<!-- ✅ [9] resource.php OK -->';

include __DIR__ . '/include/footer.php';
echo '<!-- ✅ [10] footer.php OK -->';
?>