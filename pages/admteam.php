<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$bp = __DIR__ . '/../';
require $bp . 'path.php';
require_once $bp . 'app/database/connect.php';
global $pdo;

// 1. Әкімшілікті алу (тек жарияланғандары)
$admAll = [];
try {
    $stmt = $pdo->prepare("SELECT name, profession, img FROM admteam WHERE status = 1 ORDER BY id ASC");
    $stmt->execute();
    $admAll = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {}

// 2. Ұстаздарды/Команданы алу
$teamAll = [];
try {
    $stmt = $pdo->query("SELECT name, position, photo, bio FROM team ORDER BY id ASC");
    $teamAll = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {}
?>
<!DOCTYPE html>
<html>
<head>
    <?php include __DIR__ . '/../include/head.php'; ?>
    <title>Біздің ұжым | Кемел Ұрпақ</title>
    <style>
        /* Жалпы стильдер */
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 30px; margin-top: 30px; }
        .card { background: #fff; padding: 30px 20px; border-radius: 12px; text-align: center; box-shadow: 0 4px 16px rgba(0,0,0,0.08); transition: transform 0.2s; }
        .card:hover { transform: translateY(-4px); }
        
        /* Фото */
        .card-img { width: 140px; height: 140px; border-radius: 50%; object-fit: cover; margin: 0 auto 20px; border: 4px solid #FEC50C; }
        
        /* Мәтіндер */
        .card-name { margin: 0 0 8px 0; color: #002E4B; font-size: 19px; font-weight: 700; }
        .card-role { color: #FEC50C; font-weight: 600; margin: 0 0 15px 0; font-size: 14px; text-transform: uppercase; letter-spacing: 0.3px; }
        .card-bio { color: #555; font-size: 14px; line-height: 1.6; text-align: left; background: #f8f9fa; padding: 15px; border-radius: 8px; }
        
        /* Көк сызық-бөлгіш */
        .section-divider {
            height: 3px;
            background: linear-gradient(90deg, transparent, #002E4B, transparent);
            margin: 60px auto;
            max-width: 300px;
            border-radius: 2px;
        }
        
        /* Секция тақырыптары */
        .section-title {
            text-align: center;
            color: #002E4B;
            font-size: 28px;
            font-weight: 800;
            margin: 0 0 10px 0;
            position: relative;
            padding-bottom: 15px;
        }
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: #FEC50C;
            border-radius: 2px;
        }
        .section-subtitle {
            text-align: center;
            color: #666;
            margin: 0 0 40px 0;
            font-size: 16px;
        }
        
        /* Адаптив */
        @media (max-width: 768px) {
            .grid { grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 25px; }
            .card-img { width: 120px; height: 120px; }
            .section-title { font-size: 24px; }
        }
    </style>
</head>
<body>
<?php include __DIR__ . '/../include/header.php'; ?>

<main style="padding: 50px 0; min-height: 60vh;">
    <div class="container" style="max-width: 1100px; margin: 0 auto; padding: 0 20px;">
        
        <h1 style="text-align:center; color:#002E4B; margin-bottom: 50px;">👥 Біздің ұжым</h1>
        
        <!-- ========================
             1-БӨЛІМ: ӘКІМШІЛІК
             ======================== -->
        <?php if (!empty($admAll)): ?>
            <h2 class="section-title">Мектеп әкімшілігі</h2>
            <p class="section-subtitle">Мектептің басшылық құрамы</p>
            
            <div class="grid">
                <?php foreach ($admAll as $adm): ?>
                    <div class="card">
                        <?php if (!empty($adm['img'])): ?>
                            <img class="card-img" 
                                 src="<?php echo BASE_URL; ?>assets/img/team/<?php echo htmlspecialchars($adm['img']); ?>" 
                                 alt="<?php echo htmlspecialchars($adm['name']); ?>">
                        <?php endif; ?>
                        <h3 class="card-name"><?php echo htmlspecialchars($adm['name']); ?></h3>
                        <p class="card-role"><?php echo htmlspecialchars($adm['profession'] ?? ''); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <!-- ========================
             КӨК БӨЛГІШ СЫЗЫҚ
             ======================== -->
        <?php if (!empty($admAll) && !empty($teamAll)): ?>
            <div class="section-divider"></div>
        <?php endif; ?>
        
        <!-- ========================
             2-БӨЛІМ: ҰСТАЗДАР
             ======================== -->
        <?php if (!empty($teamAll)): ?>
            <h2 class="section-title">Педагогикалық құрам</h2>
            <p class="section-subtitle">Біздің ұстаздар мен тәлімгерлер</p>
            
            <div class="grid">
                <?php foreach ($teamAll as $t): ?>
                    <div class="card">
                        <?php if (!empty($t['photo'])): ?>
                            <img class="card-img" 
                                 src="<?php echo BASE_URL; ?>assets/img/team/<?php echo htmlspecialchars($t['photo']); ?>" 
                                 alt="<?php echo htmlspecialchars($t['name']); ?>">
                        <?php endif; ?>
                        <h3 class="card-name"><?php echo htmlspecialchars($t['name']); ?></h3>
                        <p class="card-role"><?php echo htmlspecialchars($t['position'] ?? ''); ?></p>
                        <?php if (!empty($t['bio'])): ?>
                            <p class="card-bio"><?php echo nl2br(htmlspecialchars($t['bio'])); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <!-- Егер мүлдем бос болса -->
        <?php if (empty($admAll) && empty($teamAll)): ?>
            <p style="text-align:center; color:#666; padding:60px; font-size:18px;">
                Ұжым туралы ақпарат жаңартылуда...
            </p>
        <?php endif; ?>
        
    </div>
</main>

<?php include __DIR__ . '/../include/footer.php'; ?>
</body>
</html>