<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$root = __DIR__ . '/../../';
require_once $root . 'path.php';
require_once $root . 'app/database/connect.php';
global $pdo;

if (!isset($_SESSION['id'])) {
    header('Location: ' . BASE_URL . 'account/signin.php');
    exit;
}

$s_topics = [];
try {
    $s_topics = $pdo->query("SELECT id, name FROM s_topics ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $errMsg = "Қате: " . $e->getMessage();
}

// ✅ ОБРАБОТКА ФОРМЫ
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_s_post'])) {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $id_s_topic = !empty($_POST['id_s_topic']) ? (int)$_POST['id_s_topic'] : null;
    $publish = isset($_POST['publish']) ? 1 : 0;
    
    if (empty($title) || empty($content)) {
        $errMsg = '❌ Тақырып пен мазмұнды толтырыңыз!';
    } elseif (mb_strlen($title, 'UTF-8') < 7) {
        $errMsg = '❌ Тақырып 7 символдан ұзын болуы керек!';
    } else {
        try {
            $imgName = null;
            if (!empty($_FILES['img']['name']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = $root . 'assets/img/posts/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                
                $ext = strtolower(pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                
                if (in_array($ext, $allowed)) {
                    $imgName = 'post_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                    move_uploaded_file($_FILES['img']['tmp_name'], $uploadDir . $imgName);
                }
            }
            
            $stmt = $pdo->prepare("INSERT INTO s_posts (title, content, img, status, id_s_topic, created_data) VALUES (:title, :content, :img, :status, :id_s_topic, NOW())");
            $stmt->execute([
                ':title' => $title,
                ':content' => $content,
                ':img' => $imgName,
                ':status' => $publish,
                ':id_s_topic' => $id_s_topic
            ]);
            
            header('Location: index.php?added=1');
            exit;
            
        } catch (PDOException $e) {
            $errMsg = '❌ Дерекқор қатесі: ' . $e->getMessage();
        } catch (Exception $e) {
            $errMsg = '❌ Қате: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Жазба қосу - Әкімші панелі</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin.css">
    <style>
        .post__content { padding: 40px; max-width: 900px; margin: 0 auto; }
        h1 { color: #fff; margin-bottom: 30px; font-size: 28px; }
        .form-group { margin-bottom: 25px; }
        .form-group label { display: block; color: #a0aec0; margin-bottom: 10px; font-size: 14px; font-weight: 500; }
        .form__control { width: 100%; padding: 14px 18px; background: rgba(255, 255, 255, 0.05); border: 2px solid rgba(255, 255, 255, 0.1); border-radius: 8px; color: #fff; font-size: 15px; }
        .form__control:focus { outline: none; border-color: #FEC50C; background: rgba(255, 255, 255, 0.1); }
        .form__control::placeholder { color: #718096; }
        .add-post__textarea { width: 100%; min-height: 300px; padding: 14px 18px; background: rgba(255, 255, 255, 0.05); border: 2px solid rgba(255, 255, 255, 0.1); border-radius: 8px; color: #fff; font-size: 15px; font-family: inherit; resize: vertical; }
        .add-post__textarea:focus { outline: none; border-color: #FEC50C; background: rgba(255, 255, 255, 0.1); }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .form__select { width: 100%; padding: 14px 40px 14px 18px; background: rgba(255, 255, 255, 0.05); border: 2px solid rgba(255, 255, 255, 0.1); border-radius: 8px; color: #fff; font-size: 15px; cursor: pointer; appearance: none; }
        .form__select:focus { outline: none; border-color: #FEC50C; }
        .form__select option { background: #0d1f2d; color: #fff; }
        .checkbox-wrapper { display: flex; align-items: center; gap: 12px; padding: 14px 18px; background: rgba(255, 255, 255, 0.05); border: 2px solid rgba(255, 255, 255, 0.1); border-radius: 8px; height: 58px; }
        .checkbox-wrapper input[type="checkbox"] { width: 22px; height: 22px; accent-color: #FEC50C; cursor: pointer; }
        .checkbox-wrapper label { color: #a0aec0; font-size: 15px; cursor: pointer; user-select: none; margin: 0; }
        .file-input-wrapper { padding: 14px 18px; background: rgba(255, 255, 255, 0.05); border: 2px dashed rgba(255, 255, 255, 0.3); border-radius: 8px; height: 58px; display: flex; align-items: center; }
        .file-input-wrapper input[type="file"] { color: #a0aec0; font-size: 14px; cursor: pointer; width: 100%; }
        .form-actions { margin-top: 35px; padding-top: 30px; border-top: 2px solid rgba(255, 255, 255, 0.1); display: flex; gap: 15px; align-items: center; }
        .btn--save { padding: 14px 32px; font-size: 16px; font-weight: 600; background: linear-gradient(135deg, #4299e1 0%, #667eea 100%); border: none; border-radius: 8px; color: #fff; cursor: pointer; }
        .btn--save:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(66, 153, 225, 0.4); }
        .btn--cancel { padding: 14px 24px; background: rgba(255, 255, 255, 0.1); border: 2px solid rgba(255, 255, 255, 0.2); border-radius: 8px; color: #a0aec0; text-decoration: none; font-size: 15px; }
        .btn--cancel:hover { background: rgba(255, 255, 255, 0.15); color: #fff; }
        .small-help { color: #718096; font-size: 13px; margin-top: 8px; display: block; }
        .alert { padding: 15px 20px; border-radius: 8px; margin-bottom: 25px; font-weight: 500; }
        .alert-error { background: rgba(229, 62, 62, 0.2); border: 1px solid rgba(229, 62, 62, 0.4); color: #fc8181; }
        @media (max-width: 768px) { .form-row { grid-template-columns: 1fr; } .post__content { padding: 20px; } }
    </style>
</head>
<body>
<div class="page">
    <div class="container__block">
        <?php include("../temp/admin_header.php");?>
        <?php include("../temp/admin_sidebar.php");?>
        
        <main class="main">
            <div class="container">
                <div class="post">
                    <div class="post__content">
                        <h1>📝 Жаңа жазба қосу (Тест)</h1>
                        
                        <?php if (!empty($errMsg)): ?>
                            <div class="alert alert-error"><?= htmlspecialchars($errMsg) ?></div>
                        <?php endif; ?>
                        
                        <!-- ✅ ДОБАВЛЕН onsubmit ДЛЯ ПРОВЕРКИ -->
                        <form method="post" enctype="multipart/form-data" onsubmit="return confirm('🔥 Форма отправляется! Жазба сақтала ма?');">
                            <div class="form-group">
                                <label for="title">Жазба тақырыбы *</label>
                                <input type="text" id="title" name="title" placeholder="Жазба тақырыбын енгізіңіз" class="form__control" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required>
                                <span class="small-help">Кемінде 7 символ болуы керек</span>
                            </div>
                            
                            <div class="form-group">
                                <label for="content">Жазба мазмұны *</label>
                                <!-- ✅ ОБЫЧНОЕ ПОЛЕ БЕЗ EDITOR -->
                                <textarea id="content" name="content" placeholder="Жазба мазмұнын енгізіңіз..." class="add-post__textarea" required><?= htmlspecialchars($_POST['content'] ?? '') ?></textarea>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="category">Санат</label>
                                    <select id="category" class="form__select" name="id_s_topic">
                                        <option value="" selected disabled>— санатты таңдаңыз —</option>
                                        <?php if (!empty($s_topics)): ?>
                                            <?php foreach ($s_topics as $topic): ?>
                                                <option value="<?= $topic['id'] ?>" <?= (isset($_POST['id_s_topic']) && $_POST['id_s_topic'] == $topic['id']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($topic['name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <option disabled>❌ Санаттар жүктелмеді</option>
                                        <?php endif; ?>
                                    </select>
                                    <span class="small-help">Жүктелген санаттар: <?= count($s_topics) ?></span>
                                </div>
                                
                                <div class="form-group">
                                    <label>Жариялау параметрлері</label>
                                    <div class="checkbox-wrapper">
                                        <input type="checkbox" id="publish" name="publish" value="1" <?= (isset($_POST['publish']) && $_POST['publish']) ? 'checked' : '' ?>>
                                        <label for="publish">Бірден жариялау</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="image">Сурет</label>
                                <div class="file-input-wrapper">
                                    <input type="file" id="image" name="img" accept="image/*">
                                </div>
                                <span class="small-help">Рұқсат етілген форматтар: JPG, PNG, GIF, WEBP (макс. 5MB)</span>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" name="add_s_post" class="btn--save">
                                    💾 Жазбаны сақтау
                                </button>
                                <a href="index.php" class="btn--cancel">
                                    ← Болдырмау
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- ✅ СКРИПТЫ РЕДАКТОРА УДАЛЕНЫ -->

</body>
</html>