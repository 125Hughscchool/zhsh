<?php
session_start();
$root = __DIR__ . '/../../';
require_once $root . 'path.php';
require_once $root . 'app/database/connect.php';
global $pdo;

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die('🚫 Рұқсат жоқ');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    $answer = trim($_POST['answer'] ?? '');
    $action = $_POST['action'] ?? 'save';
    
    if ($action === 'approve_answer') {
        // Сохранить ответ + одобрить
        $stmt = $pdo->prepare("UPDATE director_questions SET answer = ?, status = 'approved', updated_at = NOW() WHERE id = ?");
        $stmt->execute([$answer, $id]);
    } else {
        // Только сохранить ответ
        $stmt = $pdo->prepare("UPDATE director_questions SET answer = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$answer, $id]);
    }
    
    header('Location: index.php?msg=' . urlencode('✅ Жауап сақталды!'));
    exit;
}

// Для редактирования
if (isset($_GET['id'])) {
    $q = $pdo->prepare("SELECT * FROM director_questions WHERE id = ?");
    $q->execute([(int)$_GET['id']]);
    $question = $q->fetch();
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php include __DIR__ . '/../temp/admin_head.php'; ?>
    <title>Жауап беру</title>
</head>
<body>
<div class="page">
    <div class="container__block">
        <?php include __DIR__ . '/../temp/admin_header.php'; ?>
        <?php include __DIR__ . '/../temp/admin_sidebar.php'; ?>
        <main class="main">
            <div class="container">
                <div class="post">
                    <a href="index.php" class="btn--red btn--rounded btn" style="margin-bottom:20px;">← Кері</a>
                    <div class="post__content">
                        <h1 style="color:#fff;">✍️ Жауап беру</h1>
                        <div style="background:#fff;padding:20px;border-radius:8px;color:#000;margin-bottom:20px;">
                            <strong>Сұрақ:</strong><br>
                            <?= htmlspecialchars($question['question']) ?><br><br>
                            <small style="color:#888;"><?= htmlspecialchars($question['user_name']) ?> • <?= date('d.m.Y', strtotime($question['created_at'])) ?></small>
                        </div>
                        <form method="post">
                            <input type="hidden" name="id" value="<?= $question['id'] ?>">
                            <label style="color:#fff;display:block;margin-bottom:10px;">Жауап:</label>
                            <textarea name="answer" style="width:100%;min-height:200px;padding:12px;border-radius:6px;border:1px solid #ccc;" required><?= htmlspecialchars($question['answer'] ?? '') ?></textarea>
                            <div style="margin-top:20px;display:flex;gap:15px;">
                                <button type="submit" name="action" value="save" class="btn btn--blue btn--rounded">💾 Сақтау</button>
                                <button type="submit" name="action" value="approve_answer" class="btn btn--blue btn--rounded" style="background:#48bb78;">✅ Сақтау және бекіту</button>
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