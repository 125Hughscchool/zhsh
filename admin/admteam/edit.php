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

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { header('Location: index.php'); exit; }

// 📥 Деректерді жүктеу
$member = null;
try {
    $stmt = $pdo->prepare("SELECT * FROM admteam WHERE id = ?");
    $stmt->execute([$id]);
    $member = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {}
if (!$member) { die('❌ Жазба табылмады. <a href="index.php">← Кері</a>'); }

// 📝 Форманы өңдеу
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_member'])) {
    $name = trim($_POST['name'] ?? '');
    $profession = trim($_POST['profession'] ?? '');
    
    if ($name === '') {
        $msg = '❌ Есімін жазыңыз!';
    } else {
        $imgName = $member['img']; // әдепкі бойынша ескі
        if (!empty($_FILES['img']['name']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = $basePath . 'assets/img/team/';
            if (!is_dir($uploadDir)) { mkdir($uploadDir, 0755, true); }
            $ext = strtolower(pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg','jpeg','png','gif','webp'])) {
                // Ескі суретті өшіру
                if ($member['img'] && file_exists($uploadDir . $member['img'])) {
                    unlink($uploadDir . $member['img']);
                }
                $imgName = 'adm_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                move_uploaded_file($_FILES['img']['tmp_name'], $uploadDir . $imgName);
            }
        }
        try {
            $stmt = $pdo->prepare("UPDATE admteam SET name=:name, profession=:prof, img=:img WHERE id=:id");
            $stmt->execute([':name'=>$name, ':prof'=>$profession, ':img'=>$imgName, ':id'=>$id]);
            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            $msg = '❌ Дерекқор қатесі: ' . $e->getMessage();
        }
    }
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
        <a class="btn--red btn--rounded btn" href="index.php">← Кері</a>
        <div class="post__content">
            <h1>✏️ Өңдеу: <?= htmlspecialchars($member['name']) ?></h1>
            <?php if ($msg): ?><div style="background:#ffe6e6;color:#dc3545;padding:15px;border-radius:6px;margin-bottom:20px"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
            <form method="post" enctype="multipart/form-data" style="max-width:600px">
                <div style="margin-bottom:20px">
                    <label style="display:block;margin-bottom:8px;font-weight:600">Есімі *</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($member['name']) ?>" style="width:100%;padding:12px;border:2px solid #ddd;border-radius:6px" required>
                </div>
                <div style="margin-bottom:20px">
                    <label style="display:block;margin-bottom:8px;font-weight:600">Лауазымы</label>
                    <input type="text" name="profession" value="<?= htmlspecialchars($member['profession']) ?>" style="width:100%;padding:12px;border:2px solid #ddd;border-radius:6px">
                </div>
                <?php if ($member['img']): ?>
                <div style="margin-bottom:15px">
                    <label style="font-weight:600">Ағымдағы сурет</label><br>
                    <img src="<?= BASE_URL ?>assets/img/team/<?= htmlspecialchars($member['img']) ?>" style="width:100px;height:100px;border-radius:50%;object-fit:cover;margin-top:5px">
                    <small style="color:#666">Ауыстыру үшін жаңасын жүктеңіз</small>
                </div>
                <?php endif; ?>
                <div style="margin-bottom:25px">
                    <label style="display:block;margin-bottom:8px;font-weight:600">Жаңа сурет</label>
                    <input type="file" name="img" accept="image/*" style="padding:10px;border:2px dashed #002E4B;border-radius:6px;background:#f8f9fa">
                </div>
                <button type="submit" name="update_member" class="btn btn--blue btn--rounded">💾 Өзгерістерді сақтау</button>
            </form>
        </div>
    </div></div></main>
</div></div>
</body></html>