<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/../path.php';
include __DIR__ . '/../app/database/db.php';
include __DIR__ . '/../app/controllers/posts.php';

$post_id = $_GET['post'] ?? null;
if (!$post_id) {
    header('Location: ' . BASE_URL);
    exit;
}

$post = selectOneFromPostsWithPost('posts', $post_id);

if (!$post) {
    header('Location: ' . BASE_URL . 'pages/postAll.php');
    exit;
}

$dateField = isset($post['created_data']) ? 'created_data' : (isset($post['created_at']) ? 'created_at' : null);
$createdDate = $dateField ? $post[$dateField] : null;

include __DIR__ . '/../include/head.php';
include __DIR__ . '/../include/sprite.php';
include __DIR__ . '/../include/header.php';
?>

<style>
.single-post {
    max-width: 900px;
    margin: 40px auto;
    padding: 0 20px;
}

.single-post__header {
    display: flex;
    gap: 30px;
    margin-bottom: 40px;
    align-items: flex-start;
}

.single-post__img {
    flex: 0 0 350px;
    max-width: 350px;
}

.single-post__img img {
    width: 100%;
    height: auto;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.single-post__info {
    flex: 1;
}

.single-post__title {
    font-size: 32px;
    font-weight: 700;
    color: #002E4B;
    margin: 0 0 20px;
    line-height: 1.3;
}

.single-post__anons {
    font-size: 18px;
    color: #64748b;
    margin: 0 0 20px;
    font-weight: 500;
    line-height: 1.5;
}

.single-post__meta {
    display: flex;
    gap: 20px;
    padding-top: 15px;
    border-top: 1px solid #e2e8f0;
    font-size: 14px;
    color: #94a3b8;
}

.single-post__content {
    background: #fff;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    font-size: 16px;
    line-height: 1.8;
    color: #334155;
}

.single-post__content img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 20px 0;
}

.single-post__content p {
    margin-bottom: 15px;
}

@media (max-width: 768px) {
    .single-post__header {
        flex-direction: column;
    }
    .single-post__img {
        flex: 0 0 100%;
        max-width: 100%;
    }
    .single-post__title {
        font-size: 24px;
    }
    .single-post__content {
        padding: 20px;
    }
}
</style>

<div class="single-post">
    <div class="single-post__header">
        <div class="single-post__img">
            <?php if (!empty($post['img'])): ?>
                <img src="<?php echo BASE_URL . '/assets/img/posts/' . htmlspecialchars($post['img']); ?>" 
                     alt="<?php echo htmlspecialchars(mb_substr($post['title'], 0, 50, 'UTF-8')); ?>">
            <?php else: ?>
                <img src="<?php echo BASE_URL . '/assets/img/icon/nophoto.png'; ?>" alt="">
            <?php endif; ?>
        </div>
        
        <div class="single-post__info">
            <h1 class="single-post__title"><?php echo htmlspecialchars($post['title']); ?></h1>
            
            <?php if (!empty($post['anons'])): ?>
                <h2 class="single-post__anons"><?php echo htmlspecialchars($post['anons']); ?></h2>
            <?php endif; ?>
            
            <div class="single-post__meta">
                <?php if ($createdDate): ?>
                    <span>📅 <?php echo date('d.m.Y', strtotime($createdDate)); ?></span>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="single-post__content">
        <?php echo $post['content']; ?>
    </div>
</div>

<?php
include __DIR__ . '/../include/resource.php';
include __DIR__ . '/../include/footer.php';
?>