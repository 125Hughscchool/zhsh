<meta charset="UTF-8">
<link rel="stylesheet" href="../../assets/css/admin.css">
<script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/super-build/ckeditor.js"></script>

<!-- 🛡️ ФИКС МЕНЮ + СКРОЛЛ САЙДБАРА -->
<style>
    /* 🔥 ДЕЛАЕМ САЙДБАР ПРОКРУЧИВАЕМЫМ */
    .sidebar {
        overflow-y: auto !important;
        overflow-x: hidden !important;
        max-height: 100vh !important;
    }
    
    /* Контент сайдбара тоже может скроллиться */
    .sidebar .sidebar__content,
    .sidebar .sidebar__row,
    .sidebar .row__inner {
        height: auto !important;
        max-height: none !important;
        overflow: visible !important;
        display: block !important;
    }

    /* Показываем пункты меню */
    .sidebar .row__item {
        display: list-item !important;
        visibility: visible !important;
        opacity: 1 !important;
    }

    /* Убираем сдвиги у ссылок */
    .sidebar .row__link,
    .sidebar .row__link-subnav {
        margin-left: 0 !important;
        margin-right: 0 !important;
        padding-right: 15px !important;
        width: auto !important;
    }

    /* Выпадающее меню */
    .sidebar .subnav {
        display: none;
        position: static !important;
        width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    .sidebar .row__item:hover > .subnav,
    .sidebar .row__item.open > .subnav {
        display: block !important;
    }

    /* Стили для ссылок внутри подменю */
    .sidebar .subnav a {
        padding-left: 35px !important;
        font-size: 13px !important;
    }

    /* Кастомный скроллбар (красивый) */
    .sidebar::-webkit-scrollbar {
        width: 6px;
    }
    .sidebar::-webkit-scrollbar-track {
        background: rgba(0,0,0,0.2);
    }
    .sidebar::-webkit-scrollbar-thumb {
        background: #FEC50C;
        border-radius: 3px;
    }
    .sidebar::-webkit-scrollbar-thumb:hover {
        background: #d19b06;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const triggers = document.querySelectorAll('.sidebar .has-subnav');
    triggers.forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const parent = this.parentElement;
            parent.classList.toggle('open');
        });
    });
});
</script>