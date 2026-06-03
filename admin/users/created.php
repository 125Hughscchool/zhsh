<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$root = __DIR__ . '/../../';
require_once $root . 'path.php';
require_once $root . 'app/database/connect.php';
global $pdo;

// 🔐 ПРОВЕРКА АДМИНА (с защитой от пробелов и регистра)
$role = trim(strtolower($_SESSION['role'] ?? ''));
$isAdmin = ($role === 'admin');

if (!$isAdmin) {
    die('<html><head><meta charset="utf-8"><title>Рұқсат жоқ</title></head>
    <body style="background:#fff;color:#000;font-family:Arial,sans-serif;display:flex;justify-content:center;align-items:center;height:100vh;margin:0;">
        <div style="text-align:center;padding:30px;border:2px solid #dc3545;border-radius:10px;background:#fff5f5;max-width:450px;">
            <h2 style="color:#dc3545;margin:0 0 10px;">🚫 Рұқсат жоқ</h2>
            <p style="color:#333;margin-bottom:15px;">Бұл бет тек әкімшілерге арналған.</p>
            <div style="background:#eee;padding:10px;border-radius:6px;text-align:left;font-size:11px;margin-bottom:15px;word-break:break-all;">
                <strong>Сессия:</strong><br>
                ' . htmlspecialchars(print_r($_SESSION, true)) . '
            </div>
            <a href="' . BASE_URL . 'admin/users/index.php" style="display:inline-block;padding:10px 20px;background:#002E4B;color:#fff;text-decoration:none;border-radius:5px;">← Қайту</a>
        </div>
    </body></html>');
}

$msg = '';
$old = $_POST;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role     = $_POST['role'] ?? 'user';

    if (!$username || !$email || !$password) {
        $msg = '❌ Барлық өрістерді толтырыңыз!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = '❌ Email дұрыс емес!';
    } elseif (strlen($password) < 4) {
        $msg = '❌ Пароль кемінде 4 таңба!';
    } else {
        try {
            $check = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $check->execute([$username, $email]);
            
            if ($check->fetch()) {
                $msg = '❌ Бұл логин немесе email бос емес!';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
                $stmt->execute([$username, $email, $hash, $role]);
                
                header('Location: index.php?msg=' . urlencode('✅ Пайдаланушы жасалды!') . '&type=success');
                exit;
            }
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
    <title>Пайдаланушы қосу</title>
    <style>
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; color: #fff; margin-bottom: 8px; font-weight: 500; }
        .form__control { width: 100%; padding: 12px; background: #fff; border: 1px solid #ccc; border-radius: 6px; color: #000; font-size: 14px; box-sizing: border-box; }
        .form__control:focus { border-color: #FEC50C; outline: none; }
        select.form__control { cursor: pointer; }
        select.form__control option { background: #fff; color: #000; }
        .alert { padding: 12px; border-radius: 6px; margin-bottom: 20px; color: #fff; font-weight: 500; }
        .alert-error { background: #e53e3e; }
        .btn-group { display: flex; gap: 15px; margin-top: 20px; }
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
                    <a href="index.php" class="btn--red btn--rounded btn" style="margin-bottom:20px;">← Тізімге қайту</a>
                    <div class="post__content">
                        <h1 style="color:#fff;">➕ Пайдаланушы қосу</h1>
                        <?php if ($msg): ?>
                            <div class="alert alert-error"><?= htmlspecialchars($msg) ?></div>
                        <?php endif; ?>
                        <form method="post" style="max-width:500px;">
                            <div class="form-group">
                                <label>Логин (username) *</label>
                                <input type="text" name="username" class="form__control" value="<?= htmlspecialchars($old['username'] ?? '') ?>" required placeholder="ivan_teacher">
                            </div>
                            <div class="form-group">
                                <label>Email *</label>
                                <input type="email" name="email" class="form__control" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required placeholder="mail@school.kz">
                            </div>
                            <div class="form-group">
                                <label>Пароль *</label>
                                <input type="password" name="password" class="form__control" required minlength="4" placeholder="Кемінде 4 таңба">
                            </div>
                            <div class="form-group">
                                <label>Рөлі</label>
                                <select name="role" class="form__control">
                                    <option value="user" <?= ($old['role'] ?? 'user') == 'user' ? 'selected' : '' ?>>Пайдаланушы</option>
                                    <option value="admin" <?= ($old['role'] ?? '') == 'admin' ? 'selected' : '' ?>>Әкімші</option>
                                </select>
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
</body>
</html>