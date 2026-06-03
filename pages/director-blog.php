<?php
require __DIR__ . '/../path.php';
require __DIR__ . '/../app/database/connect.php';
global $pdo;

$msg = '';
$msg_type = '';

// 📝 Обработка формы
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_question'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $question = trim($_POST['question'] ?? '');
    
    if (!$name || !$question) {
        $msg = '❌ Барлық міндетті өрістерді толтырыңыз!';
        $msg_type = 'error';
    } elseif (strlen($question) < 20) {
        $msg = '❌ Сұрақ кемінде 20 таңба болуы керек!';
        $msg_type = 'error';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO director_questions (user_name, user_email, question, status) VALUES (?, ?, ?, 'pending')");
            $stmt->execute([$name, $email, $question]);
            
            // 📧 Уведомление директору (опционально)
            $director_email = 'kemelurpaq.taraz@125highschool.kz';
            $subject = '📩 Жаңа сұрақ: ' . mb_substr($question, 0, 50, 'UTF-8') . '...';
            $body = "Есім: $name\nEmail: $email\n\nСұрақ:\n$question";
            $headers = "From: no-reply@kemel-urpaq.edu.kz\r\nReply-To: $email";
            @mail($director_email, $subject, $body, $headers);
            
            $msg = '✅ Сұрағыңыз жіберілді! Модерациядан кейін жарияланады.';
            $msg_type = 'success';
        } catch (Exception $e) {
            $msg = '❌ Қате: ' . $e->getMessage();
            $msg_type = 'error';
        }
    }
}

// 📥 Получаем одобренные вопросы с ответами
$questions = $pdo->query("
    SELECT * FROM director_questions 
    WHERE status = 'approved' 
    ORDER BY created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="kk">
<head>
    <?php include __DIR__ . '/../include/head.php'; ?>
    <title>Директорға сұрақ | Кемел Ұрпақ</title>
    <style>
        .qa-page { max-width: 800px; margin: 40px auto; padding: 0 15px; }
        .qa-form { background: #fff; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); margin-bottom: 40px; }
        .qa-form h2 { color: #002E4B; margin: 0 0 20px; font-size: 22px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; color: #333; margin-bottom: 5px; font-weight: 500; }
        .form__control { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; box-sizing: border-box; }
        .form__control:focus { border-color: #FEC50C; outline: none; }
        textarea.form__control { min-height: 120px; resize: vertical; }
        .btn-submit { background: #002E4B; color: #fff; padding: 12px 30px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; }
        .btn-submit:hover { background: #004a7a; }
        .alert { padding: 12px; border-radius: 6px; margin-bottom: 20px; }
        .alert-success { background: rgba(72,187,120,0.2); border: 1px solid #48bb78; color: #2d7a4f; }
        .alert-error { background: rgba(229,62,62,0.2); border: 1px solid #e53e3e; color: #c53030; }
        
        /* Список вопросов */
        .qa-list { display: flex; flex-direction: column; gap: 25px; }
        .qa-item { background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border-left: 4px solid #FEC50C; }
        .qa-question { font-weight: 600; color: #002E4B; margin-bottom: 10px; font-size: 16px; }
        .qa-meta { font-size: 12px; color: #888; margin-bottom: 15px; }
        .qa-answer { background: #f8fafc; padding: 15px; border-radius: 8px; border-left: 3px solid #002E4B; }
        .qa-answer-title { font-weight: 700; color: #002E4B; margin-bottom: 8px; display: flex; align-items: center; gap: 8px; }
        .qa-answer-text { color: #333; line-height: 1.5; }
        .qa-empty { text-align: center; padding: 40px; color: #888; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../include/header.php'; ?>
    
    <div class="qa-page">
        <h1 style="color:#002E4B;text-align:center;margin-bottom:30px;">📩 Директорға сұрақ қою</h1>
        
        <?php if ($msg): ?>
            <div class="alert alert-<?= $msg_type ?>"><?= $msg ?></div>
        <?php endif; ?>
        
        <!-- Форма -->
        <div class="qa-form">
            <h2>✍️ Сұрағыңызды жазыңыз</h2>
            <form method="post">
                <div class="form-group">
                    <label>Есіміңіз *</label>
                    <input type="text" name="name" class="form__control" required placeholder="Аты-жөніңіз">
                </div>
                <div class="form-group">
                    <label>Email (жауап алу үшін)</label>
                    <input type="email" name="email" class="form__control" placeholder="mail@example.com">
                </div>
                <div class="form-group">
                    <label>Сұрағыңыз *</label>
                    <textarea name="question" class="form__control" required placeholder="Сұрағыңызды толық жазыңыз..."></textarea>
                    <div style="font-size:12px;color:#888;margin-top:5px;">Кемінде 20 таңба</div>
                </div>
                <button type="submit" name="send_question" class="btn-submit">📤 Жіберу</button>
            </form>
        </div>
        
        <!-- Список вопросов -->
        <h2 style="color:#002E4B;margin:40px 0 20px;">✅ Жауап берілген сұрақтар</h2>
        <div class="qa-list">
            <?php if (!empty($questions)): ?>
                <?php foreach ($questions as $q): ?>
                <div class="qa-item">
                    <div class="qa-question"><?= htmlspecialchars($q['question']) ?></div>
                    <div class="qa-meta">
                        <?= htmlspecialchars($q['user_name']) ?> • <?= date('d.m.Y', strtotime($q['created_at'])) ?>
                    </div>
                    <?php if (!empty($q['answer'])): ?>
                    <div class="qa-answer">
                        <div class="qa-answer-title">🎓 Директордың жауабы:</div>
                        <div class="qa-answer-text"><?= nl2br(htmlspecialchars($q['answer'])) ?></div>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="qa-empty">Әзірше жауап берілген сұрақтар жоқ. Бірінші болып сұрақ қойыңыз! 👇</div>
            <?php endif; ?>
        </div>
    </div>
    
    <?php include __DIR__ . '/../include/footer.php'; ?>
</body>
</html>