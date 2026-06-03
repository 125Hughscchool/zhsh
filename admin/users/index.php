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

// 🗑️ УДАЛЕНИЕ
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    if ($delete_id !== $_SESSION['id']) {
        try {
            $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$delete_id]);
            $msg = '✅ Пользователь удалён!';
            $msg_type = 'success';
        } catch (Exception $e) {
            $msg = '❌ Ошибка: ' . $e->getMessage();
            $msg_type = 'error';
        }
    } else {
        $msg = '❌ Нельзя удалить самого себя!';
        $msg_type = 'error';
    }
    header('Location: index.php?msg=' . urlencode($msg) . '&type=' . $msg_type);
    exit;
}

$search = $_GET['search'] ?? '';
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 20;
$offset = ($page - 1) * $limit;

try {
    // Подсчёт
    $count = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username LIKE ? OR email LIKE ?");
    $count->execute(["%{$search}%", "%{$search}%"]);
    $total = $count->fetchColumn();
    $total_pages = ceil($total / $limit);

    // 🛠️ ИСПРАВЛЕНИЕ: LIMIT и OFFSET подставляем как числа, чтобы PDO не брал их в кавычки
    $stmt = $pdo->prepare("
        SELECT id, username, email, role 
        FROM users 
        WHERE username LIKE ? OR email LIKE ?
        ORDER BY id DESC 
        LIMIT $limit OFFSET $offset
    ");
    $stmt->execute(["%{$search}%", "%{$search}%"]);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die('❌ Ошибка БД: ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php include __DIR__ . '/../temp/admin_head.php'; ?>
    <title>Пользователи</title>
    <style>
        .search-form { margin: 20px 0; display: flex; gap: 10px; }
        .search-form input { flex: 1; padding: 10px; border-radius: 6px; border: 1px solid #ccc; }
        .search-form button { padding: 10px 20px; background: #002E4B; color: #fff; border: none; border-radius: 6px; cursor: pointer; }
        .alert { padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; }
        .alert-success { background: rgba(72,187,120,0.2); border: 1px solid #48bb78; color: #9ae6b4; }
        .alert-error { background: rgba(229,62,62,0.2); border: 1px solid #e53e3e; color: #fc8181; }
        .btn-delete { color: #fc8181; font-weight: 600; text-decoration: none; }
        .btn-delete:hover { text-decoration: underline; }
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
                        <h1>👥 Пользователи</h1>
						
<!-- ➕ КНОПКА (пока без проверки, чтобы точно появилась) -->
						<?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
    <div style="margin-bottom: 20px;">
        <a href="created.php" class="btn btn--blue btn--rounded">➕ Добавить пользователя</a>
    </div>
<?php endif; ?>
                        <?php if (isset($_GET['msg'])): ?>
                            <div class="alert alert-<?php echo htmlspecialchars($_GET['type'] ?? 'success'); ?>">
                                <?php echo htmlspecialchars($_GET['msg']); ?>
                            </div>
                        <?php endif; ?>
                        <form class="search-form" method="get">
                            <input type="text" name="search" placeholder="Поиск по имени или email..." value="<?= htmlspecialchars($search) ?>">
                            <button type="submit">🔍 Найти</button>
                        </form>
                        <table class="content__table">
                            <tr class="table__inner">
                                <th class="table__title">ID</th>
                                <th class="table__title">Имя</th>
                                <th class="table__title">Email</th>
                                <th class="table__title">Роль</th>
                                <th class="table__title">Действия</th>
                            </tr>
                            <?php if (!empty($users)): ?>
                                <?php foreach ($users as $u): ?>
                                <tr class="table__inner">
                                    <td class="table__item"><?= $u['id'] ?></td>
                                    <td class="table__item"><?= htmlspecialchars($u['username']) ?></td>
                                    <td class="table__item"><?= htmlspecialchars($u['email']) ?></td>
                                    <td class="table__item">
                                        <?php if ($u['role'] == 'admin'): ?>
                                            <span style="color:#FEC50C;font-weight:600">👑 Админ</span>
                                        <?php else: ?>
                                            <span style="color:#9ae6b4">👤 Пользователь</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="table__item">
                                        <?php if ($u['id'] !== $_SESSION['id']): ?>
                                            <a href="?delete_id=<?= $u['id'] ?>" class="btn-delete"
                                               onclick="return confirm('Удалить <?= addslashes(htmlspecialchars($u['username'])) ?>?')">
                                                🗑️ Удалить
                                            </a>
                                        <?php else: ?>
                                            <span style="color:#666">—</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="5" style="text-align:center;padding:30px;color:#666;">Пользователи не найдены</td></tr>
                            <?php endif; ?>
                        </table>
                        <?php if ($total_pages > 1): ?>
                        <div class="pagination" style="margin-top:30px;">
                            <ul class="pagination__inner">
                                <?php for ($p = 1; $p <= $total_pages; $p++): ?>
                                    <li class="pagination__item"><a class="pagination__link" href="?page=<?= $p ?><?= $search ? '&search=' . urlencode($search) : '' ?>"><?= $p ?></a></li>
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