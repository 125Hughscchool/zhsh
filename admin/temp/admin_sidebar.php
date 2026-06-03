<aside class="sidebar">
    <div class="sidebar__header">
        <img src="../img/admin.jpg" alt="обложка">
    </div>
    <div class="sidebar__content">
        <div class="profile">
            <img class="profile__avatar" src="../img/logoWA.png" alt="">
            <div class="profile__name">Панель управления</div>
            <div class="profile__prof">Управление контентом сайта</div>
        </div>

        <div class="sidebar__row">
            <ul class="row__inner">
                <!-- Основные пункты -->
                <li class="row__item"><a class="row__link" href="<?php echo BASE_URL . "../../admin/post/index.php" ?>">📰 Жаңалықтар</a></a></li>
                <li class="row__item"><a class="row__link" href="<?php echo BASE_URL . "../../admin/admteam/index.php" ?>">Әкімшілік</a></li>
                <li class="row__item"><a class="row__link" href="<?php echo BASE_URL . "../../admin/team/index.php" ?>">Мұғалімдер</a></li>
                <li class="row__item"><a class="row__link" href="<?php echo BASE_URL . "../../admin/users/index.php" ?>">Пользователи</a></li>
                <!-- 📩 Директор блогы -->
<li class="row__item">
    <a class="row__link" href="<?= BASE_URL ?>admin/director-blog/index.php">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:8px;vertical-align:middle;">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
        </svg>
        Директор блогы
    </a>
</li>
                <!-- Достижения, Уроки -->
                <li class="row__item">
                    <a class="row__link has-subnav" href="#">Жетістіктер, сабақтар</a>
                    <ul class="subnav">
                        <li><a class="row__link-subnav" href="<?php echo BASE_URL . "../../admin/s_post/index.php" ?>">Барлық жазбалар</a></li>
                        <li><a class="row__link-subnav" href="<?php echo BASE_URL . "../../admin/s_topics/index.php" ?>">Санаттар</a></li>
                    </ul>
                </li>
                
                <!-- Документация -->
                <li class="row__item"><a class="row__link" href="<?php echo BASE_URL . "../../admin/cert/index.php" ?>">Құжаттама</a></li>
                
                <!-- 🆕 Категории для документации (ВЫПАДАЮЩЕЕ) -->
                <li class="row__item">
                    <a class="row__link has-subnav" href="#">📁 Құжаттамаға арналған санаттар</a>
                    <ul class="subnav">
                        <li><a class="row__link-subnav" href="<?php echo BASE_URL . "../../admin/topics/index.php" ?>">Санаттарды басқару</a></li>
                        <li><a class="row__link-subnav" href="<?php echo BASE_URL . "../../admin/topics/create.php" ?>">➕ Санат қосыңыз</a></li>
                    </ul>
                </li>

                <!-- 🆕 Оқушыларға -->
                <li class="row__item">
                    <a class="row__link has-subnav" href="<?php echo BASE_URL . "../../admin/static_pages/index.php?group=students" ?>">🎓 Оқушыларға</a>
                    <ul class="subnav">
                        <li><a class="row__link-subnav" href="<?php echo BASE_URL . "../../admin/static_pages/edit.php?slug=schedule" ?>">📅 Сабақ кестесі</a></li>
                        <li><a class="row__link-subnav" href="<?php echo BASE_URL . "../../admin/static_pages/edit.php?slug=curricula" ?>">📚 Оқу жоспарлары</a></li>
                        <li><a class="row__link-subnav" href="<?php echo BASE_URL . "../../admin/static_pages/edit.php?slug=programs" ?>">🎓 Бағдарламалар</a></li>
                        <li><a class="row__link-subnav" href="<?php echo BASE_URL . "../../admin/static_pages/edit.php?slug=attestation" ?>">📝 Аттестация</a></li>
                    </ul>
                </li>

                <!-- 🆕 Ата-аналарға -->
                <li class="row__item">
                    <a class="row__link has-subnav" href="<?php echo BASE_URL . "../../admin/static_pages/index.php?group=parents" ?>">👨‍👩‍‍👦 Ата-аналарға</a>
                    <ul class="subnav">
                        <li><a class="row__link-subnav" href="<?php echo BASE_URL . "../../admin/static_pages/edit.php?slug=admission" ?>">🏫 Қабылдау</a></li>
                        <li><a class="row__link-subnav" href="<?php echo BASE_URL . "../../admin/static_pages/edit.php?slug=nutrition" ?>">🍽️ Тамақтану</a></li>
                        <li><a class="row__link-subnav" href="<?php echo BASE_URL . "../../admin/static_pages/edit.php?slug=uniform" ?>">👔 Мектеп формасы</a></li>
                        <li><a class="row__link-subnav" href="<?php echo BASE_URL . "../../admin/static_pages/edit.php?slug=safety" ?>">🛡️ Қауіпсіздік</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    
    <div class="sidebar__footer">
         <a class="btn btn--red" href="<?php echo BASE_URL . "../../index.php" ?>">Главная</a>
        <button id="myBtn" class="btn btn--blue" type="button" data-modal="contact-modal">Разработчику</button>
    </div>
</aside>