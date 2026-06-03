<?php
// 1. НАСТРОЙКИ
if (session_status() === PHP_SESSION_NONE) { session_start(); }
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 2. ПОДКЛЮЧЕНИЯ
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

// 4. ПОЛУЧАЕМ КАТЕГОРИИ (для выпадающего списка) ⚠️ ИСПРАВЛЕНО: title вместо name
$categories = [];
try {
    $stmt = $pdo->query("SELECT id, title FROM topics ORDER BY title");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $categories = [];
}

// 5. ОБРАБОТКА ФОРМЫ
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_cert'])) {
    $title = trim($_POST['title'] ?? '');
    $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
    $link = trim($_POST['link'] ?? '');
    
    if ($title === '') {
        $msg = '❌ Құжат атауын жазыңыз!';
    } else {
        try {
            $fileName = null;
            
            // Загрузка файла
            if (!empty($_FILES['userfile']['name']) && $_FILES['userfile']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = $root . 'assets/files/';
                if (!is_dir($uploadDir)) { mkdir($uploadDir, 0755, true); }
                
                $ext = strtolower(pathinfo($_FILES['userfile']['name'], PATHINFO_EXTENSION));
                $allowed = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'zip', 'rar'];
                
                if (in_array($ext, $allowed)) {
                    $fileName = 'doc_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                    move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadDir . $fileName);
                } else {
                    $msg = '❌ Рұқсат етілмеген файл түрі!';
                }
            }
            
            // Если нет ошибки — вставляем в БД
            if (empty($msg)) {
                $stmt = $pdo->prepare("INSERT INTO cert (title, userfile, link, category_id, created_at) VALUES (:title, :file, :link, :cat, NOW())");
                $stmt->execute([
                    ':title' => $title,
                    ':file' => $fileName,
                    ':link' => $link !== '' ? $link : null,
                    ':cat' => $category_id
                ]);
                
                header('Location: index.php?added=1');
                exit;
            }
        } catch (PDOException $e) {
            $msg = '❌ Қате: ' . $e->getMessage();
        }
    }
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
                    <a class="btn--red btn--rounded btn" href="index.php">← Тізімге</a>
                    
                    <div class="post__content">
                        <h1>📄 Жаңа құжат</h1>
                        
                        <?php if (!empty($msg)): ?>
                            <div style="background:#ffe6e6;color:#dc3545;padding:15px;border-radius:6px;margin:20px 0;">
                                <?= htmlspecialchars($msg) ?>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($_GET['added'])): ?>
                            <div style="background:#e6ffe6;color:#28a745;padding:15px;border-radius:6px;margin:20px 0;">
                                ✅ Құжат қосылды! <a href="index.php" style="color:#002E4B;font-weight:600;">→ Тізімге</a>
                            </div>
                        <?php endif; ?>
                        
                        <form method="post" enctype="multipart/form-data" style="max-width:600px;">
                            <div style="margin-bottom:20px;">
                                <label style="display:block;margin-bottom:8px;font-weight:600;">Құжат атауы *</label>
                                <input type="text" name="title" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" 
                                       style="width:100%;padding:12px;border:2px solid #ddd;border-radius:6px;" required>
                            </div>
                            
                            <!-- ✅ ВЫПАДАЮЩИЙ СПИСОК КАТЕГОРИЙ (ИСПРАВЛЕН) -->
                            <div style="margin-bottom:20px;">
                                <label style="display:block;margin-bottom:8px;font-weight:600;">Категория</label>
                                <select name="category_id" style="width:100%;padding:12px;border:2px solid #ddd;border-radius:6px;background:#fff;">
                                    <option value="">Категория таңдамау</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" <?= (($_POST['category_id'] ?? '') == $cat['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat['title']) ?>  <!-- ✅ title, а не name -->
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (empty($categories)): ?>
                                    <small style="color:#999;">⚠️ Категориялар жоқ. Алдымен <a href="../topics/index.php">категория қосыңыз</a>.</small>
                                <?php endif; ?>
                            </div>
                            
                            <div style="margin-bottom:20px;">
                                <label style="display:block;margin-bottom:8px;font-weight:600;">Файл жүктеу</label>
                                <input type="file" name="userfile" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar" 
                                       style="padding:10px;border:2px dashed #002E4B;border-radius:6px;background:#f8f9fa;">
                                <small style="color:#666;display:block;margin-top:5px;">PDF, DOC, XLS, PPT, ZIP — макс. 10 МБ</small>
                            </div>
                            
                            <div style="margin-bottom:25px;">
                                <label style="display:block;margin-bottom:8px;font-weight:600;">Сыртқы сілтеме (опция)</label>
                                <input type="url" name="link" value="<?= htmlspecialchars($_POST['link'] ?? '') ?>" 
                                       placeholder="https://..." style="width:100%;padding:12px;border:2px solid #ddd;border-radius:6px;">
                                <small style="color:#666;display:block;margin-top:5px;">Егер файл емес, сілтеме болса</small>
                            </div>
                            
                            <button type="submit" name="add_cert" class="btn btn--blue btn--rounded">💾 Сақтау</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>