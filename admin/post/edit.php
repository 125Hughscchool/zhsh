<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
error_reporting(E_ALL);
ini_set('display_errors', 1);

$basePath = __DIR__ . '/../../';
require $basePath . 'path.php';
require_once $basePath . 'app/database/db.php';

// Проверка авторизации
if (!isset($_SESSION['id']) || $_SESSION['id'] <= 0) {
    header('Location: ' . BASE_URL . 'account/signin.php');
    exit;
}

// Получаем ID поста из URL
$postId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($postId <= 0) {
    header('Location: index.php');
    exit;
}

// Загружаем данные поста
$post = null;
if (function_exists('selectOne')) {
    $post = selectOne('posts', ['id' => $postId]);
}
if (!$post) {
    die('❌ Запись не найдена. <a href="index.php">← Назад к списку</a>');
}

// Обработка формы
$errMsg = '';
$successMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_post'])) {
    $title = trim($_POST['title'] ?? '');
    $anons = trim($_POST['anons'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $publish = isset($_POST['publish']) ? 1 : 0;
    
    if ($title === '' || $content === '') {
        $errMsg = 'Заполните название и содержимое!';
    } else {
        $imgName = $post['img']; // По умолчанию оставляем старое изображение
        
        // Загрузка нового изображения (если выбрали)
        if (!empty($_FILES['img']['name']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = $basePath . 'assets/img/posts/';
            if (!is_dir($uploadDir)) { mkdir($uploadDir, 0755, true); }
            $ext = strtolower(pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION));
            $allowed = array('jpg', 'jpeg', 'png', 'gif', 'webp');
            
            if (in_array($ext, $allowed)) {
                // Удаляем старое изображение, если есть
                if ($post['img'] && file_exists($uploadDir . $post['img'])) {
                    unlink($uploadDir . $post['img']);
                }
                $imgName = 'post_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                move_uploaded_file($_FILES['img']['tmp_name'], $uploadDir . $imgName);
            }
        }
        
        // Обновление в БД
        if (function_exists('update')) {
            update('posts', $postId, array(
                'title' => $title,
                'anons' => $anons,
                'content' => $content,
                'img' => $imgName,
                'status' => $publish
            ));
            $successMsg = '✅ Запись обновлена! <a href="index.php">→ К списку</a>';
            // Обновляем данные для формы
            $post['title'] = $title;
            $post['anons'] = $anons;
            $post['content'] = $content;
            $post['status'] = $publish;
            $post['img'] = $imgName;
        } else {
            $errMsg = 'Функция update() не найдена';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактировать запись | Админ</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;500;600;700;800&family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Noto Sans', 'Montserrat', sans-serif; background: #f5f7fa; color: #333; }
        .admin-wrap { display: flex; min-height: 100vh; }
        .sidebar { width: 220px; background: #002E4B; color: #fff; padding: 20px 15px; position: fixed; height: 100vh; }
        .sidebar__logo { font-weight: 800; font-size: 18px; margin-bottom: 30px; color: #FEC50C; }
        .sidebar__menu { list-style: none; }
        .sidebar__menu li { margin-bottom: 8px; }
        .sidebar__menu a { display: block; padding: 10px 15px; color: #fff; text-decoration: none; border-radius: 4px; }
        .sidebar__menu a:hover, .sidebar__menu a.active { background: rgba(255,255,255,0.1); }
        .sidebar__logout { margin-top: 40px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.2); }
        .sidebar__logout a { color: #ff6b6b; text-decoration: none; }
        .main { flex: 1; margin-left: 220px; padding: 30px; }
        .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid #eee; }
        .admin-header h1 { color: #002E4B; font-size: 24px; }
        .btn { display: inline-block; padding: 10px 20px; background: #FEC50C; color: #002E4B; text-decoration: none; border-radius: 6px; font-weight: 600; border: none; cursor: pointer; }
        .btn:hover { background: #e6b20a; }
        .btn--back { background: #fff; color: #002E4B; border: 1px solid #002E4B; }
        .btn--danger { background: #dc3545; color: #fff; }
        .form-card { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); max-width: 800px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #002E4B; }
        .form-group input[type="text"], .form-group textarea { width: 100%; padding: 12px 15px; border: 2px solid #ddd; border-radius: 6px; font-size: 14px; background: #fff; font-family: inherit; }
        .form-group textarea { min-height: 250px; resize: vertical; }
        .form-actions { display: flex; gap: 20px; align-items: center; flex-wrap: wrap; margin-top: 25px; padding-top: 20px; border-top: 1px solid #eee; }
        .checkbox { display: flex; align-items: center; gap: 8px; cursor: pointer; }
        .file-upload { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: #f8f9fa; border: 2px dashed #002E4B; border-radius: 6px; cursor: pointer; }
        .file-upload input { display: none; }
        .alert { padding: 12px 20px; border-radius: 6px; margin-bottom: 20px; }
        .alert-error { background: #ffe6e6; color: #dc3545; border: 1px solid #ffcccc; }
        .alert-success { background: #e6ffe6; color: #28a745; border: 1px solid #ccffcc; }
        .current-img { margin: 10px 0; }
        .current-img img { max-width: 200px; border-radius: 4px; }
        @media (max-width: 768px) {
            .sidebar { width: 70px; }
            .sidebar__logo, .sidebar__menu span { display: none; }
            .sidebar__menu a { text-align: center; }
            .main { margin-left: 70px; padding: 20px 15px; }
            .form-actions { flex-direction: column; align-items: stretch; }
        }
    </style>
</head>
<body>
<div class="admin-wrap">
    <aside class="sidebar">
        <div class="sidebar__logo">КЕМЕЛ ҰРПАҚ</div>
        <ul class="sidebar__menu">
            <li><a href="index.php">📝 Записи</a></li>
            <li><a href="created.php">➕ Добавить</a></li>
        </ul>
        <div class="sidebar__logout">
            <a href="<?php echo BASE_URL; ?>account/signout.php">🚪 Выйти</a>
        </div>
    </aside>
    
    <main class="main">
        <div class="admin-header">
            <h1>✏️ Редактировать запись #<?php echo $post['id']; ?></h1>
            <div>
                <a href="index.php" class="btn btn--back">← Назад</a>
                <a href="created.php?delete_id=<?php echo $post['id']; ?>" class="btn btn--danger" onclick="return confirm('Удалить эту запись?')">🗑️ Удалить</a>
            </div>
        </div>
        
        <?php if (!empty($errMsg)): ?>
            <div class="alert alert-error">⚠️ <?php echo htmlspecialchars($errMsg); ?></div>
        <?php endif; ?>
        <?php if (!empty($successMsg)): ?>
            <div class="alert alert-success"><?php echo $successMsg; ?></div>
        <?php endif; ?>
        
        <div class="form-card">
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Название *</label>
                    <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($post['title'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="anons">Краткое описание</label>
                    <input type="text" name="anons" id="anons" value="<?php echo htmlspecialchars($post['anons'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="content">Содержимое *</label>
                    <textarea name="content" id="content" required><?php echo htmlspecialchars($post['content'] ?? ''); ?></textarea>
                </div>
                
                <!-- Текущее изображение -->
                <?php if (!empty($post['img'])): ?>
                <div class="form-group">
                    <label>Текущее изображение</label>
                    <div class="current-img">
                        <img src="<?php echo BASE_URL; ?>assets/img/posts/<?php echo htmlspecialchars($post['img']); ?>" alt="Current">
                    </div>
                    <small style="color:#666;">Загрузите новое, чтобы заменить</small>
                </div>
                <?php endif; ?>
                
                <div class="form-actions">
                    <label class="checkbox">
                        <input type="checkbox" name="publish" value="1" <?php echo !empty($post['status']) ? 'checked' : ''; ?>>
                        Опубликовать
                    </label>
                    <label class="file-upload">
                        📁 Новое изображение
                        <input type="file" name="img" accept="image/*">
                    </label>
                    <button type="submit" name="update_post" class="btn">💾 Сохранить изменения</button>
                </div>
            </form>
        </div>
    </main>
</div>
</body>
</html>