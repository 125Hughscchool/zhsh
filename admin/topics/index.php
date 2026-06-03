<?php
// 1. НАСТРОЙКИ
if (session_status() === PHP_SESSION_NONE) { session_start(); }
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 2. ПОДКЛЮЧЕНИЯ (правильные относительные пути)
// __DIR__ = /admin/topics/, значит /../../ = корень сайта
$root = __DIR__ . '/../../';
require_once $root . 'path.php';
require_once $root . 'app/database/connect.php';
global $pdo;

if (!$pdo) { die("❌ Базаға қосылу мүмкін емес"); }

// 3. ПРОВЕРКА ВХОДА
if (!isset($_SESSION['id']) || $_SESSION['id'] <= 0) {
    header('Location: ' . BASE_URL . 'account/signin.php');
    exit;
}

// 4. УДАЛЕНИЕ КАТЕГОРИИ
if (isset($_GET['delete_id'])) {
    try {
        $pdo->prepare("DELETE FROM topics WHERE id = ?")->execute([(int)$_GET['delete_id']]);
    } catch (Exception $e) { /* тихо */ }
    header('Location: index.php');
    exit;
}

// 5. ПОЛУЧАЕМ СПИСОК КАТЕГОРИЙ
$topics = [];
try {
    // ⚠️ Если ошибка "Unknown column" — проверь, как называются колонки в твоей таблице
    $stmt = $pdo->query("SELECT id, title, description, created_at FROM topics ORDER BY id DESC");
    $topics = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "<div style='color:red;padding:20px;background:#ffe6e6;margin:20px;border-radius:6px;'>";
    echo "❌ Ошибка БД: " . $e->getMessage() . "<br>";
    echo "💡 Проверь: существует ли таблица <b>topics</b> и колонки <b>name, description</b>";
    echo "</div>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php include __DIR__ . '/../temp/admin_head.php'; ?>
</head>
<body>
<div class="page">
    <div class="container__block">
        <?php include __DIR__ . '/../temp/admin_header.php'; ?>
        <?php include __DIR__ . '/../temp/admin_sidebar.php'; ?>
        
        <main class="main">
            <div class="container">
                <div class="post">
                    <a class="btn--red btn--rounded btn" href="created.php">➕ Категория қосу</a>
                    
                    <div class="post__content">
                        <h1>📂 Категорияларды басқару</h1>
                        
                        <?php if (!empty($topics)): ?>
                        <table class="content__table">
                            <tr class="table__inner">
                                <th class="table__title">ID</th>
                                <th class="table__title">Атауы</th>
                                <th class="table__title">Сипаттама</th>
                                <th class="table__title">Күні</th>
                                <th colspan="2" class="table__title">Басқару</th>
                            </tr>
                            <?php foreach ($topics as $key => $t): ?>
                                <tr class="table__inner">
                                    <td class="table__item"><?= $key + 1 ?></td>
                                    <td class="table__item"><?= htmlspecialchars($t['name'] ?? '—') ?></td>
                                    <td class="table__item"><?= htmlspecialchars($t['description'] ?? '—') ?></td>
                                    <td class="table__item"><?= !empty($t['created_at']) ? date('d.m.Y', strtotime($t['created_at'])) : '—' ?></td>
                                    <td class="table__item"><a href="edit.php?id=<?= $t['id'] ?>" style="color:#007bff">✏️</a></td>
                                    <td class="table__item"><a href="?delete_id=<?= $t['id'] ?>" onclick="return confirm('Өшіру?')" style="color:#dc3545">🗑️</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                        <?php else: ?>
                            <p style="color:#666;padding:20px">📭 Категориялар жоқ. <a href="created.php">Біріншісін қосу</a></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>