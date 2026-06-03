<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
error_reporting(E_ALL);
ini_set('display_errors', 1);

$basePath = __DIR__ . '/../../';
require $basePath . 'path.php';
require_once $basePath . 'app/database/connect.php';
require_once $basePath . 'app/database/db.php';
global $pdo;

if (!isset($_SESSION['id']) || $_SESSION['id'] <= 0) {
    header('Location: ' . BASE_URL . 'account/signin.php');
    exit;
}

// 🗑️ Өшіру
if (isset($_GET['delete_id'])) {
    try {
        $stmt = $pdo->prepare("SELECT img FROM admteam WHERE id = ?");
        $stmt->execute([(int)$_GET['delete_id']]);
        $row = $stmt->fetch();
        if ($row && $row['img']) {
            $imgPath = $basePath . 'assets/img/team/' . $row['img'];
            if (file_exists($imgPath)) { unlink($imgPath); }
        }
        $pdo->prepare("DELETE FROM admteam WHERE id = ?")->execute([(int)$_GET['delete_id']]);
    } catch (Exception $e) {}
    header('Location: index.php');
    exit;
}

// ✅ Статус
if (isset($_GET['publish'], $_GET['pub_id'])) {
    try {
        $pdo->prepare("UPDATE admteam SET status = ? WHERE id = ?")
            ->execute([(int)$_GET['publish'], (int)$_GET['pub_id']]);
    } catch (Exception $e) {}
    header('Location: index.php');
    exit;
}

// 📥 Тізім
$teamAll = [];
try {
    $stmt = $pdo->query("SELECT id, name, profession, img, status, created_data FROM admteam ORDER BY id DESC");
    $teamAll = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $errMsg = $e->getMessage();
}
?>
<!DOCTYPE html>
<html>
<head><?php include __DIR__ . '/../temp/admin_head.php'; ?></head>
<body>
<div class="page"><div class="container__block">
    <?php include __DIR__ . '/../temp/admin_header.php'; ?>
    <?php include __DIR__ . '/../temp/admin_sidebar.php'; ?>
    <main class="main"><div class="container"><div class="post">
        <a class="btn--red btn--rounded btn" href="created.php">➕ Қосу</a>
        <div class="post__content">
            <h1>👥 Әкімшілікті басқару</h1>
            <?php if (!empty($teamAll)): ?>
            <table class="content__table">
                <tr class="table__inner">
                    <th class="table__title">ID</th>
                    <th class="table__title">Есімі</th>
                    <th class="table__title">Лауазымы</th>
                    <th class="table__title">Сурет</th>
                    <th class="table__title">Күні</th>
                    <th class="table__title">Күйі</th>
                    <th colspan="2" class="table__title">Әрекеттер</th>
                </tr>
                <?php foreach ($teamAll as $key => $m): ?>
                <tr class="table__inner">
                    <td class="table__item"><?= $key+1 ?></td>
                    <td class="table__item"><?= htmlspecialchars($m['name']) ?></td>
                    <td class="table__item"><?= htmlspecialchars($m['profession'] ?? '—') ?></td>
                    <td class="table__item"><?= $m['img'] ? '<img src="'.BASE_URL.'assets/img/team/'.$m['img'].'" style="width:50px;height:50px;border-radius:50%;object-fit:cover;">' : '—' ?></td>
                    <td class="table__item"><?= (!empty($m['created_data']) && $m['created_data']!='0000-00-00 00:00:00') ? date('d.m.Y',strtotime($m['created_data'])) : '—' ?></td>
                    <td class="table__item"><?= $m['status'] ? '<a href="?publish=0&pub_id='.$m['id'].'" style="color:#28a745">✅ Жарияланды</a>' : '<a href="?publish=1&pub_id='.$m['id'].'" style="color:#6c757d">⏳ Жасырын</a>' ?></td>
                    <td class="table__item"><a href="edit.php?id=<?= $m['id'] ?>" style="color:#007bff">✏️ Өңдеу</a></td>
                    <td class="table__item"><a href="?delete_id=<?= $m['id'] ?>" onclick="return confirm('Жойғыңыз келе ме?')" style="color:#dc3545">🗑️</a></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php else: ?><p style="color:#666;padding:20px">📭 Жазбалар жоқ. <a href="created.php">Біріншісін қосу</a></p><?php endif; ?>
        </div>
    </div></div></main>
    <?php include __DIR__ . '/../temp/admin_footer_modals.php'; ?>
</div></div>
</body></html>