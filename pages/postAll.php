<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require __DIR__ . '/../path.php';
include __DIR__ . '/../app/database/connect.php';
global $pdo;

$page = $_GET['page'] ?? 1;
$limit = 10;
$offset = $limit * ($page - 1);

try {
    $total = $pdo->query("SELECT COUNT(*) FROM posts")->fetchColumn();
    $total_pages = ceil($total / $limit);
} catch (Exception $e) {
    $total_pages = 1;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $posts = [];
}

include __DIR__ . '/../include/head.php';
include __DIR__ . '/../include/sprite.php';
include __DIR__ . '/../include/header.php';
?>

<style>
.postall-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin: 30px 0;
}

.postall-item {
    display: flex;
    gap: 25px;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    padding: 20px;
    align-items: flex-start;
}

.postall-item__img {
    flex: 0 0 280px;
    max-width: 280px;
    height: auto;
}

.postall-item__img img {
    width: 100%;
    height: auto;
    max-height: 200px;
    object-fit: contain;
    border-radius: 8px;
}

.postall-item__content {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.postall-item__title {
    font-size: 20px;
    font-weight: 700;
    color: #002E4B;
    margin: 0 0 12px;
    line-height: 1.3;
}

.postall-item__title a {
    color: inherit;
    text-decoration: none;
}

.postall-item__title a:hover {
    color: #FEC50C;
}

.postall-item__excerpt {
    font-size: 15px;
    color: #64748b;
    line-height: 1.6;
    margin: 0 0 15px;
    flex: 1;
}

.postall-item__footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 15px;
    border-top: 1px solid #e2e8f0;
}

.postall-item__date {
    color: #94a3b8;
    font-size: 14px;
}

.postall-item__link {
    background: #002E4B;
    color: #fff;
    padding: 8px 20px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    transition: background 0.3s;
}

.postall-item__link:hover {
    background: #FEC50C;
    color: #002E4B;
}

@media (max-width: 768px) {
    .postall-item {
        flex-direction: column;
    }
    .postall-item__img {
        flex: 0 0 100%;
        max-width: 100%;
    }
}
</style>

<div class="rules" id="rules">
    <div class="container">
        <div class="advice_inner">
            <h1 class="advice__title">Барлық жаңалықтар</h1>
        </div>
    </div>
</div>

<div class="container">
    <?php if (!empty($posts)): ?>
        <div class="postall-list">
            <?php foreach ($posts as $post): ?>
                <article class="postall-item">
                    <div class="postall-item__img">
                        <?php if (!empty($post['img'])): ?>
                            <a href="<?php echo BASE_URL . 'pages/post.php?post=' . $post['id']; ?>">
                                <img src="<?php echo BASE_URL . '/assets/img/posts/' . htmlspecialchars($post['img']); ?>" 
                                     alt="<?php echo htmlspecialchars(mb_substr($post['title'], 0, 50, 'UTF-8')); ?>">
                            </a>
                        <?php else: ?>
                            <img src="<?php echo BASE_URL . '/assets/img/icon/nophoto.png'; ?>" alt="">
                        <?php endif; ?>
                    </div>
                    
                    <div class="postall-item__content">
                        <h2 class="postall-item__title">
                            <a href="<?php echo BASE_URL . 'pages/post.php?post=' . $post['id']; ?>">
                                <?php echo htmlspecialchars($post['title']); ?>
                            </a>
                        </h2>
                        
                        <?php if (!empty($post['anons'])): ?>
                            <p class="postall-item__excerpt">
                                <?php echo htmlspecialchars(mb_substr($post['anons'], 0, 200, 'UTF-8')) . (mb_strlen($post['anons'], 'UTF-8') > 200 ? '...' : ''); ?>
                            </p>
                        <?php endif; ?>
                        
                        <div class="postall-item__footer">
                            <?php
                            $dateField = isset($post['created_data']) ? 'created_data' : (isset($post['created_at']) ? 'created_at' : null);
                            $rawDate = $dateField ? $post[$dateField] : '';
                            $timestamp = strtotime($rawDate);
                            $displayDate = ($timestamp !== false && $timestamp > 0) ? date('d.m.Y', $timestamp) : '';
                            ?>
                            <?php if ($displayDate): ?>
                                <span class="postall-item__date">📅 <?php echo $displayDate; ?></span>
                            <?php endif; ?>
                            
                            <a href="<?php echo BASE_URL . 'pages/post.php?post=' . $post['id']; ?>" class="postall-item__link">
                                Толығырақ →
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p style="text-align:center;padding:40px;color:#666;">Жаңалықтар жоқ</p>
    <?php endif; ?>
</div>

<?php if ($total_pages > 1): ?>
<div class="container" style="margin:30px 0;">
    <div style="display:flex;justify-content:center;gap:10px;">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?= $i ?>" class="btn <?= $i == $page ? 'btn--blue' : 'btn--min' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>
</div>
<?php endif; ?>

<?php
include __DIR__ . '/../include/resource.php';
include __DIR__ . '/../include/footer.php';
?>