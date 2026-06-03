<?php
// ⚙️ НАСТРОЙКА: Укажи ID категорий из таблицы s_topics (смотри в phpMyAdmin)
$cat_achievements = 2; // ID категории "Жетістіктер"
$cat_lessons      = 3; // ID категории "Сабақтар"

// 🔹 Загрузка Жетістіктер (только опубликованные, последние 3)
$stmt = $pdo->prepare("SELECT id, title, img, content FROM s_posts WHERE id_s_topic = ? AND status = 1 ORDER BY created_data DESC LIMIT 3");
$stmt->execute([$cat_achievements]);
$achievements = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 🔹 Загрузка Сабақтар
$stmt->execute([$cat_lessons]);
$lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- 🟢 БІЗДІҢ ЖЕТІСТІКТЕР -->
<div class="section">
    <div class="section__row">
        <div class="row__icon"><img src="assets/img/title-ic-1.png" alt=""></div>
        <div class="row__text">БІЗДІҢ ЖЕТІСТІКТЕР</div>
    </div>
    <div class="olimpics">
        <?php if (!empty($achievements)): ?>
            <?php foreach ($achievements as $post): ?>
            <div class="olimpics__item">
                <?php if (!empty($post['img'])): ?>
                    <img src="<?= BASE_URL ?>assets/img/posts/<?= htmlspecialchars($post['img']) ?>" 
                         alt="<?= htmlspecialchars($post['title']) ?>" class="olimpics__icon">
                <?php else: ?>
                    <div class="olimpics__icon" style="background:#f0f0f0;display:flex;align-items:center;justify-content:center;font-size:40px;">🏆</div>
                <?php endif; ?>
                <h4 class="olimpics__title"><?= htmlspecialchars(mb_substr($post['title'], 0, 35)) ?></h4>
                <a href="<?= BASE_URL ?>post.php?post=<?= $post['id'] ?>" class="btn btn--blue">Толығырақ</a>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align:center;color:#666;padding:20px;">Әзірше жетістіктер жоқ. Әкімші панелі арқылы қосыңыз.</p>
        <?php endif; ?>
    </div>
</div>

<!-- 🟢 САБАҚТАР -->
<div class="study-club" id="study-club">
    <div class="container">
        <div class="study-club__inner">
            <div class="row__icon"><img src="assets/img/title-ic-1.png" alt=""></div>
            <div class="row__text">САБАҚТАР</div>
        </div>
        <div class="olimpics">
            <?php if (!empty($lessons)): ?>
                <?php foreach ($lessons as $post): ?>
                <div class="olimpics__item">
                    <?php if (!empty($post['img'])): ?>
                        <img src="<?= BASE_URL ?>assets/img/posts/<?= htmlspecialchars($post['img']) ?>" 
                             alt="<?= htmlspecialchars($post['title']) ?>" class="olimpics__icon">
                    <?php else: ?>
                        <div class="olimpics__icon" style="background:#f0f0f0;display:flex;align-items:center;justify-content:center;font-size:40px;">📚</div>
                    <?php endif; ?>
                    <h4 class="olimpics__title"><?= htmlspecialchars(mb_substr($post['title'], 0, 35)) ?></h4>
                    <a href="<?= BASE_URL ?>post.php?post=<?= $post['id'] ?>" class="btn btn--blue">Толығырақ</a>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align:center;color:#666;padding:20px;">Әзірше сабақтар жоқ. Әкімші панелі арқылы қосыңыз.</p>
            <?php endif; ?>
        </div>
    </div>
</div>