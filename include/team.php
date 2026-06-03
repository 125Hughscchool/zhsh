<?php
require __DIR__ . '/../path.php';
include __DIR__ . '/../app/controllers/teams.php';
$teams = selectAll('team');
?>

<section class="team" id="team">
    <div class="study-club__inner">
        <div class="row__icon"><img src="assets/img/title-ic-1.png" alt=""></div>
        <div class="row__text">Біздің мұғалімдеріміз</div>
    </div>
    
    <div class="container">
        <!-- ✅ Правильная структура Swiper -->
        <div class="swiper teamSwiper">
            <div class="swiper-wrapper">
                <?php if (!empty($teams)): ?>
                    <?php foreach ($teams as $team): ?>
                        <div class="swiper-slide team__item">
                            <!-- Фото -->
                            <?php if (!empty($team['photo'])): ?>
                                <div class="team__img">
                                    <img src="<?= BASE_URL . 'assets/img/team/' . htmlspecialchars($team['photo']) ?>" 
                                         alt="<?= htmlspecialchars($team['name']) ?>">
                                </div>
                            <?php else: ?>
                                <div class="team__img" style="background:#eee;display:flex;align-items:center;justify-content:center;font-size:30px;">👤</div>
                            <?php endif; ?>
                            
                            <!-- ФИО и должность -->
                            <h3 class="team__name"><?= htmlspecialchars($team['name']) ?></h3>
                            <p class="team__position"><?= htmlspecialchars($team['position']) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="swiper-slide" style="text-align:center;padding:40px;color:#888;">Мұғалімдер тізімі бос</div>
                <?php endif; ?>
            </div>
            
            <!-- Кнопки навигации -->
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>
</section>

<!-- Подключаем Swiper (если ещё не подключён) -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (document.querySelector('.teamSwiper')) {
        new Swiper('.teamSwiper', {
            slidesPerView: 3,
            spaceBetween: 20,
            loop: false,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                320: { slidesPerView: 1, spaceBetween: 10 },
                768: { slidesPerView: 2, spaceBetween: 15 },
                1024: { slidesPerView: 3, spaceBetween: 20 }
            }
        });
    }
});
</script>