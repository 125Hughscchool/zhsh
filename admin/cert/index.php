<?php
// 1. НАСТРОЙКИ
if (session_status() === PHP_SESSION_NONE) { session_start(); }
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 2. ПОДКЛЮЧЕНИЯ (правильные пути)
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

// 4. УДАЛЕНИЕ ДОКУМЕНТА
if (isset($_GET['delete_id'])) {
    try {
        // Удаляем файл, если есть
        $stmt = $pdo->prepare("SELECT userfile FROM cert WHERE id = ?");
        $stmt->execute([(int)$_GET['delete_id']]);
        $row = $stmt->fetch();
        if ($row && $row['userfile']) {
            $filePath = $root . 'assets/files/' . $row['userfile'];
            if (file_exists($filePath)) { unlink($filePath); }
        }
        $pdo->prepare("DELETE FROM cert WHERE id = ?")->execute([(int)$_GET['delete_id']]);
    } catch (Exception $e) {}
    header('Location: index.php');
    exit;
}

// 5. ПОЛУЧАЕМ СПИСОК ДОКУМЕНТОВ
$docs = [];
try {
    $stmt = $pdo->query("SELECT c.id, c.title, c.userfile, c.link, c.category_id, c.created_at, t.title as category_name 
FROM cert c 
LEFT JOIN topics t ON c.category_id = t.id");
    $docs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "<div style='color:red;padding:20px;'>❌ Ошибка: " . $e->getMessage() . "</div>";
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
                    <a class="btn--red btn--rounded btn" href="created.php">➕ Құжат қосу</a>
                    
                    <div class="post__content">
                        <h1>📄 Құжаттарды басқару</h1>
                        
                        <?php if (!empty($docs)): ?>
                        <table class="content__table">
                            <tr class="table__inner">
                                <th class="table__title">ID</th>
                                <th class="table__title">Атауы</th>
                                <th class="table__title">Категория</th>
                                <th class="table__title">Файл</th>
                                <th class="table__title">Күні</th>
                                <th colspan="2" class="table__title">Басқару</th>
                            </tr>
                            <?php foreach ($docs as $key => $d): ?>
                                <tr class="table__inner">
                                    <td class="table__item"><?= $key + 1 ?></td>
                                    <td class="table__item"><?= htmlspecialchars($d['title']) ?></td>
                                    <td class="table__item"><?= htmlspecialchars($d['category_name'] ?? '—') ?></td>
                                    <td class="table__item">
                                        <?php if ($d['userfile']): ?>
                                            <span style="color:#28a745;">📎 <?= htmlspecialchars(pathinfo($d['userfile'], PATHINFO_EXTENSION)) ?></span>
                                        <?php elseif ($d['link']): ?>
                                            <span style="color:#007bff;">🔗 Сілтеме</span>
                                        <?php else: ?>
                                            <span style="color:#999;">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="table__item"><?= !empty($d['created_at']) ? date('d.m.Y', strtotime($d['created_at'])) : '—' ?></td>
                                    <td class="table__item"><a href="edit.php?id=<?= $d['id'] ?>" style="color:#007bff">✏️</a></td>
                                    <td class="table__item"><a href="?delete_id=<?= $d['id'] ?>" onclick="return confirm('Өшіру?')" style="color:#dc3545">🗑️</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                        <?php else: ?>
                            <p style="color:#666;padding:20px">📭 Құжаттар жоқ. <a href="created.php">Біріншісін қосу</a></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>