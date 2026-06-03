<?php
// ===== БЕЗОПАСНАЯ ЗАГРУЗКА ФУНКЦИЙ =====
if (!function_exists('selectAll')) {
    require_once __DIR__ . '/../app/database/db.php';
}
if (!function_exists('buildTree')) {
    $topicsFile = __DIR__ . '/../app/controllers/topics.php';
    if (file_exists($topicsFile)) {
        require_once $topicsFile;
    }
}

// ===== ПОЛУЧЕНИЕ МЕНЮ =====
$topicsAll = [];
$menuTree = [];
try {
    if (function_exists('selectAll') && function_exists('buildTree')) {
        $topicsAll = selectAll('topics');
        if (!empty($topicsAll)) {
            $menuTree = buildTree($topicsAll, null);
        }
    }
} catch (Exception $e) {
    $menuTree = [];
    error_log('Header menu error: ' . $e->getMessage());
}
?>

<!-- ===== ВЕРХНЯЯ ПАНЕЛЬ С ГОССИМВОЛИКОЙ ===== -->
<div class="top-bar">
    <div class="container">
        <div class="top-bar__inner">
            <div class="top-bar__flag">
                <img src="<?php echo BASE_URL; ?>assets/img/Flag_of_Kazakhstan.svg.png" alt="Флаг РК" width="140">
            </div>
            <div class="top-bar__text">
                <span class="top-bar__country-kz">Қазақстан Республикасы</span>
                <span class="top-bar__country">Республика Казахстан</span>
            </div>
            <div class="top-bar__emblem">
                <img src="<?php echo BASE_URL; ?>assets/img/gerb.png" alt="Герб РК" width="95">
            </div>
        </div>
    </div>
</div>

<!-- ===== ОСНОВНОЙ ХЕДЕР ===== -->
<header class="header header-pos" id="header">
    <div class="header__inner">
        
        <!-- Контакты (ТЕПЕРЬ ПОЛНОСТЬЮ КЛИКАБЕЛЬНЫЕ) -->
        <div class="adress">
            <a href="tel:+77764444125" class="adress__link">
                <svg class="adress__icon"><use xlink:href="#phone"></use></svg>
                <span class="adress__text">+7 (776) 444-41-25</span>
            </a>
            <a href="mailto:kemelurpaq.taraz@125highschool.kz" class="adress__link">
                <svg class="adress__icon"><use xlink:href="#email"></use></svg>
                <span class="adress__text">kemelurpaq.taraz@125highschool.kz</span>
            </a>
        </div>
        
        <nav class="nav">
            <a href="#" class="nav__link" data-scroll="#section"></a>
            <ul class="nav__list">
                <li class="nav__item">
                    <a href="<?php echo BASE_URL; ?>" class="nav__link">Басты бет</a>
                </li>
                <?php
                if (!empty($menuTree) && function_exists('renderNavMenu')) {
                    renderNavMenu($menuTree);
                }
                ?>
            </ul>
        </nav>
        
        <div class="introicon">
            <a href="<?php echo BASE_URL; ?>account/signin.php" class="introicon__item introicon__item--md">
                <svg class="introicon__icon"><use xlink:href="#signin"></use></svg>
            </a>
        </div>
        <button class="burger" type="button"><span class="burger__item">menu</span></button>
    </div>
</header>

<!-- ===== СТИЛИ ===== -->
<style>
.top-bar {
    background-color: #002E4B;
    padding: 20px 0;
    border-bottom: 2px solid #FEC50C;
    position: relative;
    z-index: 1001;
}
.top-bar__inner {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 30px;
    flex-wrap: wrap;
}
.top-bar__text { text-align: center; color: #fff; }
.top-bar__country-kz {
    display: block;
    color: #FEC50C;
    font-weight: 800;
    font-size: 28px;
    line-height: 1.2;
    text-transform: uppercase;
}
.top-bar__country {
    display: block;
    font-weight: 700;
    font-size: 22px;
    color: #fff;
    margin-top: 2px;
}
.top-bar__flag img, .top-bar__emblem img {
    height: auto;
    display: block;
    flex-shrink: 0;
}

.header, .header-pos {
    background-color: #002E4B !important;
    position: relative !important;
    z-index: 1000 !important;
    top: auto !important;
}

/* Контакты - ГАРАНТИРОВАННО КЛИКАБЕЛЬНЫЕ */
.adress {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.adress__link {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    color: #fff;
    cursor: pointer;
    pointer-events: auto !important;
    transition: color 0.2s;
}
.adress__link:hover {
    color: #FEC50C;
}
.adress__icon {
    width: 22px;
    height: 22px;
    fill: #fff;
    stroke: #fff;
    flex-shrink: 0;
}
.adress__text {
    font-size: 14px;
    font-weight: 500;
    white-space: nowrap;
}

.header .nav__link, .header .nav__list a {
    color: #fff !important;
}
.header .introicon__icon, .header .introicon__icon use {
    fill: #fff !important;
}

@media (max-width: 768px) {
    .top-bar { padding: 15px 0; }
    .top-bar__inner { flex-direction: column; gap: 10px; }
    .top-bar__country-kz { font-size: 20px; }
    .top-bar__country { font-size: 16px; }
    .top-bar__flag img { width: 100px; }
    .top-bar__emblem img { width: 70px; }
    .header__inner { flex-direction: column; gap: 15px; text-align: center; }
    .adress { flex-direction: row; justify-content: center; gap: 20px; }
}
</style>

<?php include __DIR__ . "/script.php"; ?>