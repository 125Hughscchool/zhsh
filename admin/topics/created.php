<?php
// 1. НАСТРОЙКИ
if (session_status() === PHP_SESSION_NONE) { session_start(); }
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 2. ПОДКЛЮЧЕНИЯ (правильные относительные пути)
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
// 4. ПОЛУЧАЕМ КАТЕГОРИИ
$categories = [];
try {
    $stmt = $pdo->query("SELECT id, title FROM topics ORDER BY title");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $categories = [];
}

// 5. ОБРАБОТКА ФОРМЫ
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_topic'])) {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    
    if ($title === '') {
        $msg = '❌ Категория атауын жазыңыз!';
    } else {
        try {
            // ⚠️ Используем title, а не name (как в твоей БД)
            $stmt = $pdo->prepare("INSERT INTO topics (title, description, created_at) VALUES (:title, :desc, NOW())");
            $stmt->execute([
                ':title' => $title,
                ':desc' => $description !== '' ? $description : null
            ]);
            
            header('Location: index.php?added=1');
            exit;
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
                        <h1>📂 Жаңа категория</h1>
                        
                        <?php if (!empty($msg)): ?>
                            <div style="background:#ffe6e6;color:#dc3545;padding:15px;border-radius:6px;margin:20px 0;">
                                <?= htmlspecialchars($msg) ?>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($_GET['added'])): ?>
                            <div style="background:#e6ffe6;color:#28a745;padding:15px;border-radius:6px;margin:20px 0;">
                                ✅ Категория қосылды! <a href="index.php" style="color:#002E4B;font-weight:600;">→ Тізімге</a>
                            </div>
                        <?php endif; ?>
                        
                        <form method="post" style="max-width:500px;">
                            <div style="margin-bottom:20px;">
                                <label style="display:block;margin-bottom:8px;font-weight:600;">Атауы *</label>
                                <input type="text" name="title" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" 
                                       style="width:100%;padding:12px;border:2px solid #ddd;border-radius:6px;" required>
                            </div>
                            
                            <div style="margin-bottom:25px;">
                                <label style="display:block;margin-bottom:8px;font-weight:600;">Сипаттама</label>
                                <textarea name="description" rows="3" 
                                          style="width:100%;padding:12px;border:2px solid #ddd;border-radius:6px;resize:vertical;"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                            </div>
                            
                            <button type="submit" name="add_topic" class="btn btn--blue btn--rounded">💾 Сақтау</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>