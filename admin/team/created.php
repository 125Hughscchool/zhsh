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

$msg = '';
$old = $_POST;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $position = trim($_POST['position'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    
    if (empty($name)) {
        $msg = '❌ Есімін жазыңыз!';
    } elseif (empty($position)) {
        $msg = '❌ Лауазымын жазыңыз!';
    } elseif (strlen($name) < 12) {
        $msg = '❌ Есімі кемінде 12 таңба болуы керек!';
    } else {
        try {
            // 🖼️ Загрузка фото
            $photo_name = '';
            if (!empty($_FILES['photo']['name']) && $_FILES['photo']['error'] === 0) {
                $upload_dir = $root . 'assets/img/team/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
                
                $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                
                if (in_array($ext, $allowed)) {
                    $photo_name = 'teacher_' . time() . '_' . uniqid() . '.' . $ext;
                    move_uploaded_file($_FILES['photo']['tmp_name'], $upload_dir . $photo_name);
                }
            }
            
            // 💾 Вставка в БД
            $stmt = $pdo->prepare("
                INSERT INTO team (name, position, photo, bio, created_at) 
                VALUES (:name, :position, :photo, :bio, NOW())
            ");
            $stmt->execute([
                ':name' => $name,
                ':position' => $position,
                ':photo' => $photo_name,
                ':bio' => $bio
            ]);
            
            header('Location: index.php?msg=' . urlencode('✅ Мұғалім сәтті қосылды!') . '&type=success');
            exit;
        } catch (Exception $e) {
            $msg = '❌ Дерекқор қатесі: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php include __DIR__ . '/../temp/admin_head.php'; ?>
    <title>Мұғалім қосу</title>
    <style>
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; color: #fff; margin-bottom: 8px; font-weight: 500; }
        .form__control { width: 100%; padding: 12px; background: #fff; border: 1px solid #ccc; border-radius: 6px; color: #000; font-size: 14px; box-sizing: border-box; }
        .form__control:focus { border-color: #FEC50C; outline: none; }
        textarea.form__control { min-height: 150px; resize: vertical; }
        .alert { padding: 12px; border-radius: 6px; margin-bottom: 20px; color: #fff; font-weight: 500; }
        .alert-error { background: #e53e3e; }
        .btn-group { display: flex; gap: 15px; margin-top: 20px; }
        .hint { color: #718096; font-size: 13px; margin-top: 5px; }
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
                    <a href="index.php" class="btn--red btn--rounded btn" style="margin-bottom:20px;">← Тізімге қайту</a>
                    <div class="post__content">
                        <h1 style="color:#fff;">➕ Мұғалім қосу</h1>
                        
                        <?php if ($msg): ?>
                            <div class="alert alert-error"><?= htmlspecialchars($msg) ?></div>
                        <?php endif; ?>
                        
                        <form method="post" enctype="multipart/form-data" style="max-width:600px;">
                            <div class="form-group">
                                <label>Есімі *</label>
                                <input type="text" name="name" class="form__control" value="<?= htmlspecialchars($old['name'] ?? '') ?>" required placeholder="Мысалы: Сарыбаев Мақсат Махмутұлы">
                                <div class="hint">Кемінде 12 таңба</div>
                            </div>
                            
                            <div class="form-group">
                                <label>Лауазымы *</label>
                                <input type="text" name="position" class="form__control" value="<?= htmlspecialchars($old['position'] ?? '') ?>" required placeholder="Мысалы: Директор, Математика мұғалімі">
                            </div>
                            
                            <div class="form-group">
                                <label>Қысқаша ақпарат</label>
                                <textarea name="bio" class="form__control" rows="4" placeholder="Мұғалім туралы қысқаша мәлімет"><?= htmlspecialchars($old['bio'] ?? '') ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label>Сурет</label>
                                <input type="file" name="photo" class="form__control" accept="image/*">
                                <div class="hint">JPG, PNG, GIF, WEBP (макс. 5MB)</div>
                            </div>
                            
                            <div class="btn-group">
                                <button type="submit" class="btn btn--blue btn--rounded">💾 Сақтау</button>
                                <a href="index.php" style="padding:14px 24px;background:rgba(255,255,255,0.1);border-radius:8px;color:#a0aec0;text-decoration:none;">Болдырмау</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>