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

// 🗑️ Удаление
if (isset($_GET['delete_id'])) {
    try {
        $pdo->prepare("DELETE FROM director_questions WHERE id = ?")->execute([(int)$_GET['delete_id']]);
        header('Location: index.php?msg=' . urlencode('✅ Жойылды!'));
        exit;
    } catch (Exception $e) {
        $error = '❌ Қате: ' . $e->getMessage();
    }
}

// ✅ Одобрение
if (isset($_GET['approve_id'])) {
    try {
        $pdo->prepare("UPDATE director_questions SET status = 'approved' WHERE id = ?")->execute([(int)$_GET['approve_id']]);
        header('Location: index.php?msg=' . urlencode('✅ Бекітілді!'));
        exit;
    } catch (Exception $e) {
        $error = '❌ Қате: ' . $e->getMessage();
    }
}

// 📥 Получаем все вопросы
try {
    $questions = $pdo->query("SELECT * FROM director_questions ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
    $total_questions = count($questions);
} catch (Exception $e) {
    $error = '❌ Дерекқор қатесі: ' . $e->getMessage();
    $questions = [];
    $total_questions = 0;
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php include __DIR__ . '/../temp/admin_head.php'; ?>
    <title>Директор блогы - сұрақтар</title>
    <style>
        .qa-admin-item { background: #fff; padding: 20px; border-radius: 8px; margin-bottom: 15px; border-left: 4px solid #ccc; }
        .qa-admin-item.pending { border-left-color: #FEC50C; }
        .qa-admin-item.approved { border-left-color: #48bb78; }
        .qa-meta { font-size: 12px; color: #888; margin-bottom: 10px; }
        .qa-question { font-weight: 600; color: #002E4B; margin-bottom: 10px; }
        .btn-group { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 10px; }
        .btn { padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; font-size: 13px; text-decoration: none; display: inline-block; color: #fff; }
        .btn-approve { background: #48bb78; }
        .btn-delete { background: #e53e3e; }
        .btn:hover { opacity: 0.9; }
        .alert { padding: 12px; border-radius: 6px; margin-bottom: 20px; }
        .alert-success { background: rgba(72,187,120,0.2); color: #2d7a4f; border: 1px solid #48bb78; }
        .alert-error { background: rgba(229,62,62,0.2); color: #c53030; border: 1px solid #e53e3e; }
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
                    <div class="post__content">
                        <h1>📩 Директор блогы - сұрақтар</h1>
                        
                        <?php if (isset($_GET['msg'])): ?>
                            <div class="alert alert-success"><?= htmlspecialchars($_GET['msg']) ?></div>
                        <?php endif; ?>
                        
                        <?php if (isset($error)): ?>
                            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>
                        
                        <?php if ($total_questions > 0): ?>
                            <div style="margin-bottom:20px;color:#fff;">
                                Барлығы: <strong><?= $total_questions ?></strong> сұрақ
                            </div>
                            
                            <?php foreach ($questions as $q): ?>
                            <div class="qa-admin-item <?= $q['status'] ?>">
                                <div class="qa-meta">
                                    <strong>ID: <?= $q['id'] ?></strong> | 
                                    <strong><?= htmlspecialchars($q['user_name']) ?></strong> 
                                    <?= $q['user_email'] ? '• ' . htmlspecialchars($q['user_email']) : '' ?> 
                                    • <?= date('d.m.Y H:i', strtotime($q['created_at'])) ?>
                                    • Статус: <strong style="color:<?= $q['status'] === 'approved' ? '#48bb78' : ($q['status'] === 'pending' ? '#FEC50C' : '#e53e3e') ?>">
                                        <?= $q['status'] ?>
                                    </strong>
                                </div>
                                <div class="qa-question"><?= htmlspecialchars($q['question']) ?></div>
                                
                                <?php if (!empty($q['answer'])): ?>
                                    <div style="background:#f8fafc;padding:12px;border-radius:6px;margin:10px 0;">
                                        <strong>🎓 Жауап:</strong><br>
                                        <?= nl2br(htmlspecialchars($q['answer'])) ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="btn-group">
                                    <?php if ($q['status'] === 'pending'): ?>
                                        <a href="?approve_id=<?= $q['id'] ?>" class="btn btn-approve" onclick="return confirm('Бекітесіз бе?')">✅ Бекіту</a>
                                    <?php endif; ?>
                                    <a href="answer.php?id=<?= $q['id'] ?>" class="btn" style="background:#002E4B;">✏️ Жауап беру</a>
                                    <a href="?delete_id=<?= $q['id'] ?>" class="btn btn-delete" onclick="return confirm('Жойсын ба?')">🗑️ Өшіру</a>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div style="text-align:center;padding:60px;color:#fff;">
                                <div style="font-size:60px;margin-bottom:20px;">📭</div>
                                <h2>Әзірше сұрақтар жоқ</h2>
                                <p style="color:#a0aec0;">Пайдаланушылар сұрақ қойған кезде осында көрінеді</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>