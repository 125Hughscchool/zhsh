<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/../path.php';
require __DIR__ . '/../app/database/connect.php';
global $pdo;

// 1. Загружаем ВСЕ категории из s_topics
$categories = [];
try {
    $stmt = $pdo->query("SELECT id, name FROM s_topics ORDER BY id");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $categories = [];
}

// 2. Для каждой категории загружаем её достижения
$categories_with_posts = [];
foreach ($categories as $cat) {
    try {
        $stmt = $pdo->prepare("
            SELECT p.id, p.title, p.content, p.img, p.created_data
            FROM s_posts p
            WHERE p.id_s_topic = :cat_id AND p.status = 1
            ORDER BY p.created_data DESC
            LIMIT 10
        ");
        $stmt->execute([':cat_id' => $cat['id']]);
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($posts)) {
            $categories_with_posts[] = [
                'category' => $cat,
                'posts' => $posts
            ];
        }
    } catch (Exception $e) { /* пропускаем */ }
}
?>
<!DOCTYPE html>
<html lang="kk">
<head>
    <?php include __DIR__ . '/../include/head.php'; ?>
    <title>Үздік оқушылардың жетістіктері | Кемел Ұрпақ</title>
    <style>
        /*  ОСНОВНАЯ ЦВЕТОВАЯ СХЕМА: Светлый фон + Тёмный текст */
        :root {
            --bg-page: #f8fafc;
            --bg-card: #ffffff;
            --text-main: #0f172a;       /* Почти чёрный */
            --text-secondary: #334155;  /* Тёмно-серый */
            --text-muted: #64748b;      /* Серый для дат */
            --accent-blue: #002E4B;     /* Фирменный тёмно-синий */
            --accent-gold: #FEC50C;     /* Золотой */
            --border-light: #e2e8f0;
        }

        body { background: var(--bg-page); }

        .page-header {
            background: var(--bg-card);
            padding: 40px 0;
            border-bottom: 3px solid var(--accent-gold);
            margin-bottom: 40px;
            text-align: center;
        }
        .page-header h1 {
            color: var(--accent-blue);
            font-size: 32px;
            margin: 0 0 10px;
            font-weight: 800;
        }
        .page-header p {
            color: var(--text-secondary);
            margin: 0;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            font-size: 16px;
        }

        .category-section { margin-bottom: 50px; }

        /* Заголовок категории */
        .category-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 25px;
            padding: 16px 24px;
            background: var(--bg-card);
            border-radius: 12px;
            border: 2px solid var(--accent-blue);
            box-shadow: 0 4px 12px rgba(0,46,75,0.08);
        }
        .category-header__bracket {
            color: var(--accent-gold);
            font-size: 24px;
            font-weight: 800;
        }
        .category-header__title {
            color: var(--accent-blue); /* ✅ ТЁМНЫЙ ЧИТАЕМЫЙ */
            font-size: 22px;
            font-weight: 800;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .category-header__count {
            background: var(--accent-blue);
            color: var(--accent-gold);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
        }

        /* Сетка и карточки */
        .posts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(290px, 1fr));
            gap: 20px;
        }
        .post-card {
            background: var(--bg-card);
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid var(--border-light);
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
        }
        .post-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0,46,75,0.12);
            border-color: var(--accent-gold);
        }
        .post-card__img {
            width: 100%;
            height: 170px;
            object-fit: cover;
            background: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            font-size: 36px;
        }
        .post-card__content { padding: 20px; }
        
        .post-card__title {
            color: var(--accent-blue); /* ✅ ТЁМНЫЙ ЗАГОЛОВОК */
            font-size: 18px;
            margin: 0 0 12px;
            line-height: 1.4;
            font-weight: 700;
        }
        .post-card__title a {
            color: inherit;
            text-decoration: none;
        }
        .post-card__title a:hover { color: var(--accent-gold); }
        
        .post-card__excerpt {
            color: var(--text-secondary); /* ✅ ТЁМНЫЙ ТЕКСТ ОПИСАНИЯ */
            font-size: 15px;
            margin: 0 0 16px;
            line-height: 1.6;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .post-card__footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 14px;
            border-top: 1px solid var(--border-light);
        }
        .post-card__date {
            color: var(--text-muted); /* ✅ СЕРЫЙ, НО ЧИТАЕМЫЙ */
            font-size: 13px;
            font-weight: 500;
        }
        .post-card__link {
            color: var(--accent-blue);
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            border-bottom: 2px solid var(--accent-gold);
            padding-bottom: 2px;
        }
        .post-card__link:hover { color: var(--accent-gold); }

        .category-divider {
            height: 1px;
            background: var(--border-light);
            margin: 40px 0;
            border: none;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: var(--bg-card);
            border-radius: 12px;
            border: 1px dashed var(--border-light);
        }
        .empty-state h3 { color: var(--accent-blue); margin: 0 0 10px; }
        .empty-state p { color: var(--text-secondary); }

        @media (max-width: 768px) {
            .posts-grid { grid-template-columns: 1fr; }
            .category-header__title { font-size: 18px; }
            .page-header h1 { font-size: 26px; }
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../include/header.php'; ?>
    <?php include __DIR__ . '/../include/sprite.php'; ?>
    
    <div class="page-header">
        <div class="container">
            <h1>🏆 здік оқушылардың жетістіктері</h1>
            <p>Біздің оқушылардың олимпиадаларда, спортта және шығармашылықтағы жетістіктері</p>
        </div>
    </div>
    
    <div class="container">
        <?php if (!empty($categories_with_posts)): ?>
            <?php foreach ($categories_with_posts as $group): ?>
                <?php 
                $cat = $group['category'];
                $posts = $group['posts'];
                ?>
                
                <section class="category-section">
                    <div class="category-header">
                        <span class="category-header__bracket">⟦</span>
                        <h2 class="category-header__title"><?= htmlspecialchars($cat['name']) ?></h2>
                        <span class="category-header__count"><?= count($posts) ?></span>
                        <span class="category-header__bracket">⟧</span>
                    </div>
                    
                    <div class="posts-grid">
                        <?php foreach ($posts as $post): ?>
                            <article class="post-card">
                                <?php if (!empty($post['img'])): ?>
                                    <img class="post-card__img" 
                                         src="<?= BASE_URL ?>assets/img/posts/<?= htmlspecialchars($post['img']) ?>" 
                                         alt="<?= htmlspecialchars($post['title']) ?>">
                                <?php else: ?>
                                    <div class="post-card__img">📄</div>
                                <?php endif; ?>
                                
                                <div class="post-card__content">
                                    <h3 class="post-card__title">
                                        <a href="<?= BASE_URL ?>post.php?post=<?= $post['id'] ?>">
                                            <?= htmlspecialchars(mb_substr($post['title'], 0, 55)) ?>
                                            <?= mb_strlen($post['title']) > 55 ? '...' : '' ?>
                                        </a>
                                    </h3>
                                    
                                    <p class="post-card__excerpt">
                                        <?= htmlspecialchars(mb_substr(strip_tags($post['content']), 0, 110)) ?>
                                        <?= mb_strlen(strip_tags($post['content'])) > 110 ? '...' : '' ?>
                                    </p>
                                    
                                    <div class="post-card__footer">
                                        <span class="post-card__date">
                                            📅 <?= !empty($post['created_data']) ? date('d.m.Y', strtotime($post['created_data'])) : '' ?>
                                        </span>
                                        <a class="post-card__link" href="<?= BASE_URL ?>post.php?post=<?= $post['id'] ?>">
                                            Толығырақ →
                                        </a>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </section>
                
                <?php if ($group !== end($categories_with_posts)): ?>
                    <hr class="category-divider">
                <?php endif; ?>
                
            <?php endforeach; ?>
            
        <?php else: ?>
            <div class="empty-state">
                <h3>📭 Әзірше жетістіктер жоқ</h3>
                <p>Админ панелі арқылы жаңа жетістіктерді қосыңыз.</p>
            </div>
        <?php endif; ?>
    </div>
    
    <?php include __DIR__ . '/../include/footer.php'; ?>
</body>
</html>