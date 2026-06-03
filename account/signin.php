<?php
session_start();
$root = __DIR__ . '/../';
require_once $root . 'path.php';
require_once $root . 'app/database/connect.php';
global $pdo;

// Если уже вошел — редирект
if (isset($_SESSION['id'])) {
    header('Location: ' . BASE_URL . 'admin/post/index.php');
    exit;
}

$error = '';
// 🔐 CSRF проверка
if (class_exists('Csrf') && !\Csrf::validate($_POST['csrf_token'] ?? '')) {
    $error = '❌ Қауіпсіздік тексеруі сәтсіз!';
} 
// ... дальше твоя проверка логина/пароля (в else)
else {
    // твой код проверки пароля...
}

// 🔐 Обработка входа (БЕЗ лишних проверок пока)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username_input = trim($_POST['username'] ?? '');
    $password_input = $_POST['password'] ?? '';

    if (!$username_input || !$password_input) {
        $error = '❌ Логин мен парольді енгізіңіз!';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username_input]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password_input, $user['password'])) {
                // ✅ Успешный вход
                $_SESSION['id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['name'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['last_activity'] = time();
				
                
                // 🔥 ВРЕМЕННО: редирект на главную сайта для теста
                header('Location: ' . BASE_URL . 'admin/post/index.php');
                exit;
            } else {
                $error = '❌ Логин немесе пароль дұрыс емес!';
            }
        } catch (Exception $e) {
            $error = '❌ Қате: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Кіру | Кемел Ұрпақ</title>
    <style>
        body { margin: 0; font-family: 'Montserrat', sans-serif; background-color: #002E4B; color: #fff; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .login-card { background: #0d1f2d; padding: 40px; border-radius: 12px; width: 100%; max-width: 400px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); border: 1px solid #FEC50C; }
        .login-title { text-align: center; margin-bottom: 30px; color: #FEC50C; font-size: 24px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; color: #a0aec0; margin-bottom: 8px; font-size: 14px; }
        .form__control { width: 100%; padding: 12px; background: #fff; border: none; border-radius: 6px; color: #000; font-size: 16px; box-sizing: border-box; }
        .form__control:focus { outline: 2px solid #FEC50C; }
        .btn-submit { width: 100%; padding: 14px; background: #FEC50C; color: #002E4B; border: none; border-radius: 6px; font-size: 16px; font-weight: 700; cursor: pointer; }
        .error-msg { background: rgba(229,62,62,0.2); border: 1px solid #e53e3e; color: #fc8181; padding: 10px; border-radius: 6px; margin-bottom: 20px; text-align: center; }
        .back-link { display: block; text-align: center; margin-top: 20px; color: #718096; text-decoration: none; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2 class="login-title">🔐 Кіру</h2>
        <?php if ($error): ?><div class="error-msg"><?= $error ?></div><?php endif; ?>
        <form method="post">
			<?php if (class_exists('Csrf')): ?>
    <?= \Csrf::field() ?>
<?php endif; ?>
            <div class="form-group">
                <label>Логин</label>
                <input type="text" name="username" class="form__control" required>
            </div>
            <div class="form-group">
                <label>Пароль</label>
                <input type="password" name="password" class="form__control" required>
            </div>
            <button type="submit" class="btn-submit">Кіру</button>
        </form>
        <a href="<?= BASE_URL ?>" class="back-link">← Басты бет</a>
    </div>
</body>
</html>