<?php
require __DIR__ . '/../path.php';
require __DIR__ . '/../app/database/connect.php';
global $pdo;

// 🔍 Автоматически определяем slug из имени файла (например, schedule.php -> schedule)
$slug = basename(__FILE__, '.php');

// 📥 Загружаем данные из БД
$stmt = $pdo->prepare("SELECT * FROM static_pages WHERE slug = ? LIMIT 1");
$stmt->execute([$slug]);
$page = $stmt->fetch(PDO::FETCH_ASSOC);

// 🚫 Если страница не найдена в БД
if (!$page) {
    header("HTTP/1.0 404 Not Found");
    die('<div style="text-align:center;margin-top:100px;color:#fff;"><h1>📭 Бет табылмады</h1><p>Әкімші панелі арқылы мазмұнды қосыңыз.</p></div>');
}
?>
<!DOCTYPE html>
<html lang="kk">
<head>
    <?php include __DIR__ . '/../include/head.php'; ?>
    <title><?= htmlspecialchars($page['title_kz']) ?> | Кемел Ұрпақ</title>
    <style>
        .static-page-header {
            background: linear-gradient(135deg, #002E4B 0%, #0d1f2d 100%);
            padding: 40px 0;
            border-bottom: 3px solid #FEC50C;
            text-align: center;
            margin-bottom: 40px;
        }
        .static-page-header h1 {
            color: #fff;
            font-size: 32px;
            margin: 0;
            font-weight: 800;
        }
        .static-content-box {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            max-width: 900px;
            margin: 0 auto 50px;
        }
        .static-content-box img {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        .static-text {
            color: #333;
            line-height: 1.8;
            font-size: 16px;
        }
        .static-text p { margin-bottom: 15px; }
        .static-text img { max-width: 100%; height: auto; border-radius: 6px; margin: 15px 0; }
        .static-text table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .static-text th, .static-text td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .static-text th { background: #002E4B; color: #fff; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../include/header.php'; ?>
    <?php include __DIR__ . '/../include/sprite.php'; ?>
    
    <!-- Заголовок страницы -->
    <div class="static-page-header">
        <div class="container">
            <h1><?= htmlspecialchars($page['title_kz']) ?></h1>
        </div>
    </div>
    
    <!-- Основной контент -->
    <div class="container">
        <div class="static-content-box">
            
            <?php if (!empty($page['img'])): ?>
                <img src="<?= BASE_URL ?>assets/img/pages/<?= htmlspecialchars($page['img']) ?>" 
                     alt="<?= htmlspecialchars($page['title_kz']) ?>">
            <?php endif; ?>
            
            <div class="static-text">
                <?php if (!empty(trim(strip_tags($page['content_kz'])))): ?>
                    <?= $page['content_kz'] ?>
                <?php else: ?>
                    <p style="color:#888;text-align:center;padding:40px 0;">
                        📝 Әзірше мазмұн жоқ. Әкімші панелі арқылы мәтін қосыңыз.
                    </p>
                <?php endif; ?>
            </div>
            
        </div>
    </div>
    
    <?php include __DIR__ . '/../include/footer.php'; ?>
</body>
</html>