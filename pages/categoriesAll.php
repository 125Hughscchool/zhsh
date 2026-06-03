<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require __DIR__ . '/../path.php';
include __DIR__ . '/../app/database/db.php';
include __DIR__ . '/../app/controllers/s_posts.php';

// Получаем ID категории из URL
$category_id = $_GET['id'] ?? null;
if (!$category_id) {
    die('Категория не указана');
}

// Получаем категорию по ID
$category = selectOne('s_topics', ['id' => $category_id]);

// Пагинация
$page = $_GET['page'] ?? 1;
$limit = 10;
$offset = $limit * ($page - 1);

// Количество постов в категории
$total_posts = countRow('s_posts', ['category_id' => $category_id]);
$total_pages = ceil($total_posts / $limit);

// Получаем посты только этой категории
$posts = selectAllFromSPostsWithStopicsOnPageCategory('s_posts', 's_topics', $limit, $offset, $category_id);

// Подключаем шаблоны
include __DIR__ . '/../include/head.php';
include __DIR__ . '/../include/sprite.php';
include __DIR__ . '/../include/header.php';
?>

<div class="rules" id="rules">
    <div class="container">
        <div class="advice_inner">
            <h1 class="advice__title"><?php echo htmlspecialchars($category['name']); ?></h1>
        </div>
    </div>
</div>

<div class="container">
    <?php foreach ($posts as $post): ?>
    <div class="rules__inner rules__inner--post">
        <article class="news news--post">
            <div class="news__preview">
                <?php if ($post['img']): ?>
                    <a href="<?php echo BASE_URL . 'pages/category.php?category=' . $post['id']; ?>">
                        <img src="<?php echo BASE_URL . '/assets/img/posts/' . $post['img']; ?>" alt="<?php echo mb_substr($post['title'], 0, 52, 'UTF-8') . '...'; ?>">
                    </a>
                <?php else: ?>
                    <img src="<?php echo BASE_URL . '/assets/img/icon/nophoto.png'; ?>" alt="">
                <?php endif; ?>
            </div>
            <div class="news__content news__content--post">
                <h2 class="news__title">
                    <a class="news__title-link" href="<?php echo BASE_URL . 'pages/category.php?category=' . $post['id']; ?>">
                        <?php echo mb_strlen($post['title'], 'UTF-8') > 50 ? mb_substr($post['title'], 0, 50, 'UTF-8') . '...' : $post['title']; ?>
                    </a>
                </h2>
                <p class="news__description news__description--post">
                    <?php echo mb_strlen($post['content'], 'UTF-8') > 250 ? mb_substr($post['content'], 0, 250, 'UTF-8') . '...' : $post['content']; ?>
                </p>
                <div class="news__footer">
                    <ul class="news__data">
                        <li class="news__data-item">
                            <time datetime="<?php echo $post['created_data']; ?>">
                                <?php echo date('d.m.Y', strtotime($post['created_data'])); ?>
                            </time>
                        </li>
                    </ul>
                    <a href="<?php echo BASE_URL . 'pages/category.php?category=' . $post['id']; ?>" class="news_read btn btn--min">Толығырақ</a>
                </div>
            </div>
        </article>
    </div>
    <?php endforeach; ?>
</div>

<?php
include __DIR__ . '/../include/resource.php';
include __DIR__ . '/../include/footer.php';
?>