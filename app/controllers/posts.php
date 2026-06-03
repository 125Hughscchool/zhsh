<?php
// app/controllers/posts.php — ЧИСТАЯ ВЕРСИЯ (БЕЗ session_start!)
function selectOneFromPostsWithPost($table, $id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM $table WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return null;
    }
}
$errMsg = [];
$postsAll = [];

// Получаем посты только если функция БД доступна
if (function_exists('selectAll')) {
    try {
        $postsAll = selectAll('posts');
    } catch (Exception $e) {
        $postsAll = [];
    }
}

// Обработка удаления (через GET-параметр)
if (isset($_GET['delete_id']) && function_exists('delete')) {
    delete('posts', (int)$_GET['delete_id']);
    header('Location: index.php');
    exit;
}

// Обработка статуса (публикация/черновик)
if (isset($_GET['publish'], $_GET['pub_id']) && function_exists('update')) {
    update('posts', (int)$_GET['pub_id'], ['status' => (int)$_GET['publish']]);
    header('Location: index.php');
    exit;
}

// Обработка формы добавления поста
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_post'])) {
    $title = trim($_POST['title'] ?? '');
    $anons = trim($_POST['anons'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $publish = isset($_POST['publish']) ? 1 : 0;
    
    if ($title !== '' && $content !== '') {
        // Загрузка изображения (упрощённо)
        $imgName = null;
        if (!empty($_FILES['img']['name']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../assets/img/posts/';
            if (!is_dir($uploadDir)) { mkdir($uploadDir, 0755, true); }
            $ext = strtolower(pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg','jpeg','png','gif','webp'])) {
                $imgName = 'post_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                move_uploaded_file($_FILES['img']['tmp_name'], $uploadDir . $imgName);
            }
        }
        
        // Вставка в БД
        if (function_exists('insert')) {
            insert('posts', [
                'title' => $title,
                'anons' => $anons,
                'content' => $content,
                'img' => $imgName,
                'status' => $publish,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            header('Location: index.php');
            exit;
        }
    }
}
?>