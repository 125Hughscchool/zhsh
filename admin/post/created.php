<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$root = __DIR__ . '/../../';
require_once $root . 'path.php';
require_once $root . 'app/database/connect.php';
global $pdo;

// 🔐 Доступ любому авторизованному пользователю
if (!isset($_SESSION['id'])) {
    header('Location: ' . BASE_URL . 'account/signin.php');
    exit;
}

$msg = '';
$old = $_POST;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title   = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $anons   = trim($_POST['anons'] ?? '');
    $status  = isset($_POST['status']) ? 1 : 0;
    
    if (empty($title)) {
        $msg = '❌ Тақырыбын жазыңыз!';
    } elseif (empty($content)) {
        $msg = '❌ Толық мәтінді жазыңыз!';
    } else {
        try {
            // 🖼️ Загрузка картинки
            $img_name = '';
            if (!empty($_FILES['img']['name']) && $_FILES['img']['error'] === 0) {
                $upload_dir = $root . 'assets/img/posts/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
                
                $ext = strtolower(pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                
                if (in_array($ext, $allowed)) {
                    $img_name = 'post_' . time() . '_' . uniqid() . '.' . $ext;
                    move_uploaded_file($_FILES['img']['tmp_name'], $upload_dir . $img_name);
                }
            }
            
            // 💾 Вставка в БД (таблица posts)
            $stmt = $pdo->prepare("
                INSERT INTO posts (title, content, anons, img, status, created_at, updated_at) 
                VALUES (:title, :content, :anons, :img, :status, NOW(), NOW())
            ");
            $stmt->execute([
                ':title'   => $title,
                ':content' => $content,
                ':anons'   => $anons,
                ':img'     => $img_name,
                ':status'  => $status
            ]);
            
            header('Location: index.php?msg=' . urlencode('✅ Жазба сәтті қосылды!') . '&type=success');
            exit;
        } catch (Exception $e) {
            $msg = '❌ Қате: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php include __DIR__ . '/../temp/admin_head.php'; ?>
    <title>Жаңалық қосу</title>
    <style>
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; color: #fff; margin-bottom: 8px; font-weight: 500; }
        .form__control { width: 100%; padding: 12px; background: #fff; border: 1px solid #ccc; border-radius: 6px; color: #000; font-size: 14px; box-sizing: border-box; }
        .form__control:focus { border-color: #FEC50C; outline: none; }
        textarea.form__control { min-height: 250px; resize: vertical; }
        .alert { padding: 12px; border-radius: 6px; margin-bottom: 20px; color: #fff; font-weight: 500; }
        .alert-error { background: #e53e3e; }
        .btn-group { display: flex; gap: 15px; margin-top: 20px; }
        .checkbox-group { display: flex; align-items: center; gap: 10px; margin-top: 10px; }
        .checkbox-group input[type="checkbox"] { width: 20px; height: 20px; cursor: pointer; }
        .checkbox-group label { margin: 0; cursor: pointer; color: #FEC50C; font-weight: 600; }
        .hint { color: #718096; font-size: 13px; margin-top: 5px; }
    </style>
    <script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/super-build/ckeditor.js"></script>
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
                        <h1 style="color:#fff;">➕ Жаңа жаңалық қосу</h1>
                        
                        <?php if ($msg): ?>
                            <div class="alert alert-error"><?= htmlspecialchars($msg) ?></div>
                        <?php endif; ?>
                        
                        <form method="post" enctype="multipart/form-data" style="max-width:800px;">
                            <div class="form-group">
                                <label>Тақырыбы *</label>
                                <input type="text" name="title" class="form__control" value="<?= htmlspecialchars($old['title'] ?? '') ?>" required placeholder="Тақырыбын жазыңыз">
                            </div>
                            
                            <div class="form-group">
                                <label>Анонс (қысқаша сипаттама)</label>
                                <textarea name="anons" class="form__control" rows="3" placeholder="Жаңалықтар тізіміндегі қысқаша сипаттама"><?= htmlspecialchars($old['anons'] ?? '') ?></textarea>
                                <div class="hint">Бұл мәтін негізгі беттегі жаңалықтар анонсы ретінде көрсетіледі</div>
                            </div>
                            
                            <div class="form-group">
                                <label>Толық мәтін *</label>
                                <textarea id="editor" name="content" class="form__control" required><?= htmlspecialchars($old['content'] ?? '') ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label>Сурет</label>
                                <input type="file" name="img" class="form__control" accept="image/*">
                                <div class="hint">JPG, PNG, GIF, WEBP форматтары</div>
                            </div>
                            
                            <div class="form-group">
                                <div class="checkbox-group">
                                    <input type="checkbox" name="status" value="1" id="status" <?= ($old['status'] ?? 0) == 1 ? 'checked' : '' ?>>
                                    <label for="status">🔥 Бірден жариялау</label>
                                </div>
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
<script>
    ClassicEditor.create(document.querySelector('#editor')).catch(e => console.error(e));
</script>
</body>
</html>