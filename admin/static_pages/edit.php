<?php
session_start();
require_once __DIR__ . '/../../path.php';
require_once __DIR__ . '/../../app/database/connect.php';
global $pdo;
if (!isset($_SESSION['id'])) { header('Location: ' . BASE_URL . 'account/signin.php'); exit; }

$slug = $_GET['slug'] ?? '';
$stmt = $pdo->prepare("SELECT * FROM static_pages WHERE slug = ?");
$stmt->execute([$slug]);
$page = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$page) { header('Location: index.php'); exit; }

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_page'])) {
    $title_kz = trim($_POST['title_kz']);
    $content_kz = trim($_POST['content_kz']);
    $img = $page['img'];
    if (!empty($_FILES['img']['name']) && $_FILES['img']['error'] === 0) {
        $dir = __DIR__ . '/../../assets/img/pages/';
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        $ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
        $img = $slug . '_' . time() . '.' . $ext;
        move_uploaded_file($_FILES['img']['tmp_name'], $dir . $img);
    }
    $upd = $pdo->prepare("UPDATE static_pages SET title_kz=?, content_kz=?, img=?, updated_at=NOW() WHERE slug=?");
    $upd->execute([$title_kz, $content_kz, $img, $slug]);
    $msg = '✅ Сақталды!';
    $page['title_kz'] = $title_kz; $page['content_kz'] = $content_kz; $page['img'] = $img;
}
?>
<!DOCTYPE html>
<html><head><?php include __DIR__ . '/../temp/admin_head.php'; ?>
<script src="https://cdn.ckeditor.com/ckeditor5/27.0.0/classic/ckeditor.js"></script>
<style>.form-group{margin-bottom:20px}.form-group label{display:block;color:#a0aec0;margin-bottom:8px;font-weight:500}.form__control{width:100%;padding:12px;background:rgba(255,255,255,0.05);border:2px solid rgba(255,255,255,0.1);border-radius:8px;color:#fff}.form__control:focus{outline:none;border-color:#FEC50C}textarea.form__control{min-height:300px;resize:vertical}.preview{max-width:250px;border-radius:8px;margin:10px 0;border:2px solid #FEC50C}.alert{background:#e6ffe6;color:#28a745;padding:15px;border-radius:8px;margin-bottom:20px}</style></head>
<body><div class="page"><div class="container__block">
    <?php include __DIR__ . '/../temp/admin_header.php'; ?>
    <?php include __DIR__ . '/../temp/admin_sidebar.php'; ?>
    <main class="main"><div class="container"><div class="post">
        <a href="index.php?group=<?= $page['group_type'] ?>" class="btn--red btn--rounded btn" style="margin-bottom:20px;">← Тізімге</a>
        <div class="post__content"><h1>✏️ <?= htmlspecialchars($page['title_kz']) ?></h1>
        <?php if($msg): ?><div class="alert"><?= $msg ?></div><?php endif; ?>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group"><label>Название раздела *</label><input type="text" name="title_kz" class="form__control" value="<?= htmlspecialchars($page['title_kz']) ?>" required></div>
            <div class="form-group"><label>Содержимое *</label><textarea id="editor" name="content_kz" class="form__control" required><?= htmlspecialchars($page['content_kz']) ?></textarea></div>
            <div class="form-group"><label>Изображение / Файл</label><?php if($page['img']): ?><img src="<?= BASE_URL ?>assets/img/pages/<?= htmlspecialchars($page['img']) ?>" class="preview"><?php endif; ?><input type="file" name="img" accept="image/*,.pdf" class="form__control" style="margin-top:10px;"></div>
            <button type="submit" name="save_page" class="btn btn--blue btn--rounded">💾 Сақтау</button>
        </form></div></div></div></main></div></div>
<script>ClassicEditor.create(document.querySelector('#editor')).catch(e=>console.error(e));</script>
</body></html>