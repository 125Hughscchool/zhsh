<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$root = __DIR__ . '/../../';
require_once $root . 'path.php';
require_once $root . 'app/database/connect.php';
global $pdo;

// 🔐 Проверка входа
if (!isset($_SESSION['id'])) {
    header('Location: ' . BASE_URL . 'account/signin.php');
    exit;
}

// 🗑️ Удаление
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    try {
        $stmt_del = $pdo->prepare("SELECT photo FROM team WHERE id = ?");
        $stmt_del->execute([$delete_id]);
        $photo = $stmt_del->fetchColumn();
        if ($photo) {
            $img_path = $root . 'assets/img/team/' . $photo;
            if (file_exists($img_path)) {
                unlink($img_path);
            }
        }
        $pdo->prepare("DELETE FROM team WHERE id = ?")->execute([$delete_id]);
        header('Location: index.php?msg=' . urlencode('✅ Мұғалім сәтті жойылды!') . '&type=success');
        exit;
    } catch (Exception $e) {
        $error_msg = '❌ Қате: ' . $e->getMessage();
    }
}

// 📥 Получение списка
$search = $_GET['search'] ?? '';
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 20;
$offset = ($page - 1) * $limit;

try {
    $count = $pdo->prepare("SELECT COUNT(*) FROM team WHERE name LIKE ?");
    $count->execute(["%{$search}%"]);
    $total = $count->fetchColumn();
    $total_pages = ceil($total / $limit);

    $stmt_list = $pdo->prepare("
        SELECT id, name, position, photo, created_at 
        FROM team 
        WHERE name LIKE ? 
        ORDER BY id DESC 
        LIMIT $limit OFFSET $offset
    ");
    $stmt_list->execute(["%{$search}%"]);
    $teachers = $stmt_list->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die('❌ Дерекқор қатесі: ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php include __DIR__ . '/../temp/admin_head.php'; ?>
    <title>Мұғалімдерді басқару</title>
    <style>
        .search-form { margin: 20px 0; display: flex; gap: 10px; }
        .search-form input { flex: 1; padding: 10px; border-radius: 6px; border: 1px solid #ccc; }
        .search-form button { padding: 10px 20px; background: #002E4B; color: #fff; border: none; border-radius: 6px; cursor: pointer; }
        .alert { padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; }
        .alert-success { background: rgba(72,187,120,0.2); border: 1px solid #48bb78; color: #9ae6b4; }
        .alert-error { background: rgba(229,62,62,0.2); border: 1px solid #e53e3e; color: #fc8181; }
        .btn-edit { color: #4ade80; font-weight: 600; text-decoration: none; margin-right: 10px; }
        .btn-delete { color: #fc8181; font-weight: 600; text-decoration: none; }
        .btn-edit:hover, .btn-delete:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class="page">
    <div class="container__block">
        <?php include __DIR__ . '/../temp/admin_header.php'; ?>
        <?php include __DIR__ . '/../temp/admin_sidebar.php'; ?>
        <main class="main">
            <div class="container">
                <div class="post">
                    <div class="post__content">
                        <h1>👨‍🏫 Мұғалімдерді басқару</h1>
                        
                        <?php if (isset($_GET['msg'])): ?>
                            <div class="alert alert-<?php echo htmlspecialchars($_GET['type'] ?? 'success'); ?>">
                                <?php echo htmlspecialchars($_GET['msg']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div style="margin-bottom:20px;">
                            <a href="created.php" class="btn btn--blue btn--rounded">➕ Мұғалім қосу</a>
                        </div>
                        
                        <form class="search-form" method="get">
                            <input type="text" name="search" placeholder="Есімі бойынша іздеу..." value="<?= htmlspecialchars($search) ?>">
                            <button type="submit">🔍 Іздеу</button>
                        </form>
                        
                        <table class="content__table">
                            <tr class="table__inner">
                                <th class="table__title">ID</th>
                                <th class="table__title">Есімі</th>
                                <th class="table__title">Лауазымы</th>
                                <th class="table__title">Сурет</th>
                                <th class="table__title">Күні</th>
                                <th class="table__title">Әрекеттер</th>
                            </tr>
                            <?php if (!empty($teachers)): ?>
                                <?php foreach ($teachers as $t): ?>
                                <tr class="table__inner">
                                    <td class="table__item"><?= $t['id'] ?></td>
                                    <td class="table__item" style="font-weight:500;"><?= htmlspecialchars($t['name']) ?></td>
                                    <td class="table__item"><?= htmlspecialchars($t['position']) ?></td>
                                    <td class="table__item">
                                        <?php if (!empty($t['photo'])): ?>
                                            <img src="<?= BASE_URL ?>assets/img/team/<?= htmlspecialchars($t['photo']) ?>" 
                                                 style="width:50px;height:50px;border-radius:50%;object-fit:cover;">
                                        <?php else: ?>
                                            <span style="color:#999">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="table__item"><?= date('d.m.Y', strtotime($t['created_at'])) ?></td>
                                    <td class="table__item">
                                        <a href="edit.php?id=<?= $t['id'] ?>" class="btn-edit">✏️ Өңдеу</a>
                                        <a href="?delete_id=<?= $t['id'] ?>" class="btn-delete" onclick="return confirm('«<?= addslashes(htmlspecialchars($t['name'])) ?>» мұғалімін жойғыңыз келе ме?')">🗑️ Өшіру</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="6" style="text-align:center;padding:30px;color:#666;">Мұғалімдер табылмады</td></tr>
                            <?php endif; ?>
                        </table>
                        
                        <?php if ($total_pages > 1): ?>
                        <div class="pagination" style="margin-top:30px;">
                            <ul class="pagination__inner">
                                <?php for ($pg = 1; $pg <= $total_pages; $pg++): ?>
                                    <li class="pagination__item"><a class="pagination__link" href="?page=<?= $pg ?><?= $search ? '&search=' . urlencode($search) : '' ?>"><?= $pg ?></a></li>
                                <?php endfor; ?>
                            </ul>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>