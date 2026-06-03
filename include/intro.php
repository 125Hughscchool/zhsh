<!-- 🟢 НАВИГАЦИЯ (КНОПКИ ПЕРЕХОДОВ) -->
<div class="intro-nav">
    <div class="container">
        <div class="introIn">
            <!-- 1. Мектеп ақпараты -->
            <a class="introIn__item" href="<?= BASE_URL ?>pages/school-info.php">
                <div class="introIn__icon">
                    <div class="introicon">
                        <svg class="introicon__icon"><use xlink:href="#school"></use></svg>
                    </div>
                </div>
                <div class="introIn__title">Мектеп ақпараты</div>
            </a>
<!-- 🆕 МҰҒАЛІМДЕР -->
<a class="introIn__item" href="<?= BASE_URL ?>pages/admteam.php">
    <div class="introIn__icon">
        <div class="introicon">
            <img src="<?= BASE_URL ?>assets/img/icon/mugalim.svg" alt="Мұғалімдер" class="introicon__img">
        </div>
    </div>
    <div class="introIn__title">Мұғалімдер</div>
</a>
            <!-- 2. Үздік оқушылар -->
            <a class="introIn__item" href="<?= BASE_URL ?>pages/best-students.php">
                <div class="introIn__icon">
                    <div class="introicon">
                        <svg class="introicon__icon"><use xlink:href="#medal"></use></svg>
                    </div>
                </div>
                <div class="introIn__title">Үздік оқушылар</div>
            </a>

            <!-- 3. Оқушыларға -->
            <div class="introIn__item">
                <div class="introIn__icon">
                    <div class="introicon">
                        <img src="<?= BASE_URL ?>assets/img/icon/okushilar.svg" alt="Оқушыларға" class="introicon__img">
                    </div>
                </div>
                <div class="introIn__title">Оқушыларға</div>
                <ul class="introIn__dropdown">
                    <li><a href="<?= BASE_URL ?>pages/schedule.php">📅 Сабақ кестесі</a></li>
                    <li><a href="<?= BASE_URL ?>pages/curricula.php">📚 Оқу жоспарлары</a></li>
                    <li><a href="<?= BASE_URL ?>pages/programs.php">🎓 Бағдарламалар</a></li>
                    <li><a href="<?= BASE_URL ?>pages/attestation.php">📝 Аттестация</a></li>
                </ul>
            </div>

            <!-- 4. Ата-аналарға -->
            <div class="introIn__item">
                <div class="introIn__icon">
                    <div class="introicon">
                        <img src="<?= BASE_URL ?>assets/img/icon/parents.svg" alt="Ата-аналарға" class="introicon__img">
                    </div>
                </div>
                <div class="introIn__title">Ата-аналарға</div>
                <ul class="introIn__dropdown">
                    <li><a href="<?= BASE_URL ?>pages/admission.php">🏫 Қабылдау</a></li>
                    <li><a href="<?= BASE_URL ?>pages/nutrition.php">🍽️ Тамақтану</a></li>
                    <li><a href="<?= BASE_URL ?>pages/uniform.php">👔 Мектеп формасы</a></li>
                    <li><a href="<?= BASE_URL ?>pages/safety.php">🛡️ Қауіпсіздік</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- 🟢 ГЛАВНЫЙ ЭКРАН (ЗАГОЛОВОК И ТЕКСТ) -->
<div class="intro" id="intro">
    <div class="container">
        <div class="intro__inner">
            <h1 class="intro__title">«Кемел ұрпақ» жекеменшік мектебі» ЖШС <br> <br>125 High School Тараз</h1>
            <h2 class="intro__subtitle">Сапалы білім беруді мақсат еткен, заман талабына сай оқу орны. Мұнда оқушылардың интеллектуалды, шығармашылық және көшбасшылық қабілеттерін дамытуға ерекше назар аударылады. Мектеп ұжымы оқытудың озық әдістерін қолдана отырып, әрбір оқушының қабілетін ашуға жағдай жасайды.</h2>
        </div>
    </div>
</div>