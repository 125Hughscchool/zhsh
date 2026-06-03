<?php
session_start();
require_once __DIR__ . '/../../path.php';
require_once __DIR__ . '/../../app/database/connect.php';
global $pdo;
if (!isset($_SESSION['id'])) { header('Location: ' . BASE_URL . 'account/signin.php'); exit; }

$group = $_GET['group'] ?? 'students';
$stmt = $pdo->prepare("SELECT * FROM static_pages WHERE group_type = ? ORDER BY id");
$stmt->execute([$group]);
$pages = $stmt->fetchAll(PDO::FETCH_ASSOC);
$title = ($group == 'parents') ? '👨‍👩‍‍👦 Ата-аналарға' : '🎓 Оқушыларға';
?>
<!DOCTYPE html>
<html><head><?php include __DIR__ . '/../temp/admin_head.php'; ?><title><?= $title ?></title></head>
<body><div class="page"><div class="container__block">
    <?php include __DIR__ . '/../temp/admin_header.php'; ?>
    <?php include __DIR__ . '/../temp/admin_sidebar.php'; ?>
    <main class="main"><div class="container"><div class="post"><div class="post__content">
        <h1><?= $title ?></h1>
        <table class="content__table"><tr class="table__inner">
            <th class="table__title">Раздел</th><th class="table__title">Slug</th><th class="table__title">Действие</th>
        </tr><?php foreach ($pages as $p): ?>
        <tr class="table__inner">
            <td class="table__item"><?= htmlspecialchars($p['title_kz']) ?></td>
            <td class="table__item"><code><?= $p['slug'] ?></code></td>
            <td class="table__item"><a href="edit.php?slug=<?= $p['slug'] ?>" class="btn btn--blue btn--min">✏️ Редактировать</a></td>
        </tr><?php endforeach; ?></table>
    </div></div></div></main></div></div></body></html>