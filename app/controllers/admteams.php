<?php
// app/controllers/admteams.php — для таблицы team (photo, bio, без status)

$teamAll = [];
$errMsg = '';

// 1. Получаем список
try {
    global $pdo;
    $stmt = $pdo->query("SELECT id, name, position, photo, bio, created_at FROM team ORDER BY id DESC");
    $teamAll = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    error_log('Team load: ' . $e->getMessage());
}

// 2. Обработка добавления
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_member'])) {
    $name = trim($_POST['name'] ?? '');
    $position = trim($_POST['position'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    
    if ($name === '') {
        $errMsg = 'Введите имя!';
    } else {
        try {
            global $pdo;
            $photoName = null;
            
            // Загрузка фото (колонка photo, не img!)
            if (!empty($_FILES['img']['name']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../assets/img/team/';
                if (!is_dir($uploadDir)) { mkdir($uploadDir, 0755, true); }
                $ext = strtolower(pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg','jpeg','png','gif','webp'])) {
                    $photoName = 'team_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                    move_uploaded_file($_FILES['img']['tmp_name'], $uploadDir . $photoName);
                }
            }
            
            // ✅ Вставка с правильными колонками: photo вместо img, нет status
            $stmt = $pdo->prepare("INSERT INTO team (name, position, bio, photo, created_at) VALUES (:name, :position, :bio, :photo, :created_at)");
            $stmt->execute([
                ':name' => $name,
                ':position' => $position,
                ':bio' => $bio,
                ':photo' => $photoName,
                ':created_at' => date('Y-m-d H:i:s')
            ]);
            
            header('Location: index.php?added=1');
            exit;
        } catch (PDOException $e) {
            $errMsg = 'Ошибка БД: ' . $e->getMessage();
            error_log('Team insert: ' . $e->getMessage());
        }
    }
}

// 3. Удаление
if (isset($_GET['delete_id'])) {
    try {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM team WHERE id = ?");
        $stmt->execute([(int)$_GET['delete_id']]);
        header('Location: index.php');
        exit;
    } catch (Exception $e) {
        error_log('Team delete: ' . $e->getMessage());
    }
}
?>