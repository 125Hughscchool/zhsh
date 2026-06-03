<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$root = __DIR__ . '/../../';
require_once $root . 'path.php';
require_once $root . 'app/database/connect.php';
global $pdo;

if (!isset($_SESSION['id'])) {
    header('Location: ' . BASE_URL . 'account/signin.php');
    exit;
}

// Удаление записи
if (isset($_GET['delete_id'])) {
    try {
        // Сначала удаляем файл картинки, если есть
        $stmt = $pdo->prepare("SELECT img FROM s_posts WHERE id = ?");
        $stmt->execute([(int)$_GET['delete_id']]);
        $row = $stmt->fetch();
        if ($row && $row['img']) {
            $filePath = $root . 'assets/img/posts/' . $row['img'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        // Удаляем запись из БД
        $pdo->prepare("DELETE FROM s_posts WHERE id = ?")->execute([(int)$_GET['delete_id']]);
    } catch (Exception $e) {
        // Тихо игнорируем ошибки удаления
    }
    header('Location: index.php');
    exit;
}

// Получаем список записей
$posts = [];
try {
    // ✅ Используем правильные названия: s_posts, s_topics, title, name
    $stmt = $pdo->query("
        SELECT p.id, p.title, p.img, p.status, p.created_data, t.name as category_name 
        FROM s_posts p 
        LEFT JOIN s_topics t ON p.id_s_topic = t.id 
        ORDER BY p.id DESC
    ");
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $dbError = "❌ Ошибка БД: " . $e->getMessage();
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
                    <a class="btn--red btn--rounded btn" href="created.php">➕ Жаңа жазба</a>
                    
                    <div class="post__content">
                        <h1>📋 Жазбалар тізімі</h1>
                        
                        <?php if (isset($_GET['added'])): ?>
                            <div style="background:#e6ffe6;color:#28a745;padding:15px;border-radius:6px;margin-bottom:20px;">
                                ✅ Жазба сәтті қосылды!
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($dbError)): ?>
                            <div style="background:#ffe6e6;color:#dc3545;padding:15px;border-radius:6px;margin-bottom:20px;">
                                <?= htmlspecialchars($dbError) ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($posts)): ?>
                        <table class="content__table">
                            <tr class="table__inner">
                                <th class="table__title">ID</th>
                                <th class="table__title">Сурет</th>
                                <th class="table__title">Тақырып</th>
                                <th class="table__title">Санат</th>
                                <th class="table__title">Күні</th>
                                <th class="table__title">Статус</th>
                                <th colspan="2" class="table__title">Басқару</th>
                            </tr>
                            <?php foreach ($posts as $key => $p): ?>
                                <tr class="table__inner">
                                    <td class="table__item"><?= $key + 1 ?></td>
                                    <td class="table__item">
                                        <?php if ($p['img']): ?>
                                            <img src="<?= BASE_URL ?>assets/img/posts/<?= htmlspecialchars($p['img']) ?>" 
                                                 alt="" style="width:50px;height:50px;object-fit:cover;border-radius:4px;">
                                        <?php else: ?>
                                            <span style="color:#666">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="table__item"><?= htmlspecialchars(mb_substr($p['title'], 0, 50)) ?><?= mb_strlen($p['title']) > 50 ? '...' : '' ?></td>
                                    <td class="table__item"><?= htmlspecialchars($p['category_name'] ?? '—') ?></td>
                                    <td class="table__item"><?= !empty($p['created_data']) ? date('d.m.Y', strtotime($p['created_data'])) : '—' ?></td>
                                    <td class="table__item">
                                        <?php if ($p['status']): ?>
                                            <span style="color:#28a745;font-weight:600">✅ Жарияланды</span>
                                        <?php else: ?>
                                            <span style="color:#666">⏳ Черновик</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="table__item">
                                        <a href="created.php?edit=<?= $p['id'] ?>" style="color:#007bff">✏️</a>
                                    </td>
                                    <td class="table__item">
                                        <a href="?delete_id=<?= $p['id'] ?>" onclick="return confirm('Өшіруді растайсыз ба?')" style="color:#dc3545">🗑️</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                        <?php else: ?>
                            <p style="color:#666;padding:20px">📭 Жазбалар жоқ. <a href="created.php" style="color:#007bff">Бірінші жазбаны қосыңыз</a></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>