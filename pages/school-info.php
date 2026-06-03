<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require __DIR__ . '/../path.php';
include __DIR__ . '/../include/head.php';
?>
<style> body, .container, .container * { color: #111 !important; } </style>
<?php
include __DIR__ . '/../include/header.php';
?>

<!-- 2. Увеличен верхний отступ (padding-top: 80px), чтобы контент не наезжал на фиксированную шапку/полосу -->
<div class="container" style="min-height: 400px; padding: 80px 20px 40px; max-width: 960px; margin: 0 auto;">
    <h1 style="margin-top: 0;">Мектеп ақпараты</h1>
    <p>Мектеп туралы толық ақпарат, видео-презентациялар және оқу бөлімінің SWOT талдауы.</p>

    <!-- Видео-презентация сети -->
    <div style="margin-top: 40px; text-align: center;">
        <h2 style="font-size: 22px;">Білім беру желісі туралы</h2>
        <video width="800" height="450" controls style="max-width: 100%; height: auto;">
            <source src="<?php echo BASE_URL . 'assets/video/school-presentation2.mp4'; ?>" type="video/mp4">
            <source src="<?php echo BASE_URL . 'assets/video/school-presentation2.webm'; ?>" type="video/webm">
            Сіздің браузеріңіз видеоны қолдамайды. 
            <a href="<?php echo BASE_URL . 'assets/video/school-presentation2.mp4'; ?>">Видеоны жүктеп алу</a>
        </video>
    </div>

    <!-- Видео-презентация школы -->
    <div style="margin-top: 40px; text-align: center;">
        <h2 style="font-size: 22px;">Мектеп презентациясы</h2>
        <video width="800" height="450" controls style="max-width: 100%; height: auto;">
            <source src="<?php echo BASE_URL . 'assets/video/school-presentation.mp4'; ?>" type="video/mp4">
            <source src="<?php echo BASE_URL . 'assets/video/school-presentation.webm'; ?>" type="video/webm">
            Сіздің браузеріңіз видеоны қолдамайды. 
            <a href="<?php echo BASE_URL . 'assets/video/school-presentation.mp4'; ?>">Видеоны жүктеп алу</a>
        </video>
    </div>

    <!-- SWOT-анализ учебной работы -->
    <div style="margin-top: 60px;">
        <h2 style="font-size: 26px; border-bottom: 2px solid #0056a4; padding-bottom: 10px;">SWOT талдау – ОҚУ БӨЛІМІ</h2>
        <p style="font-size: 16px; color: #555;">Каримбекова Аксауле Еркиновна<br>
        «125 Education» орта мектебі директордың оқу ісі жөніндегі орынбасары</p>

        <!-- 1. Заголовки выделены смысловыми цветами. !important перебивает глобальный стиль #111. Добавлена нижняя линия для визуального оформления заголовков -->
        <h3 style="color: #2e7d32 !important; border-bottom: 2px solid #2e7d32; padding-bottom: 8px; margin-top: 30px; margin-bottom: 15px;">Мықты тұстар</h3>
        <ul>
            <li><strong>Педагогикалық құрам:</strong> 5 педагог-зерттеуші, 4 педагог-сарапшы, 7 педагог-модератор, 17 педагог.</li>
            <li>Пәндер бірлестіктері белсенді жұмыс істеді. Барлық жоспарланған онкүндіктер өтті.</li>
            <li><strong>Оқушылар жетістіктері:</strong> Республикалық пәндік олимпиадаларда 1 оқушы облыстық кезеңде І орын, 14 оқушы ІІІ орын; 7-8 сыныптардан 2 оқушы І орын, 4 ІІ орын. Халықаралық TeenEagle жарысында әлем бойынша командалық І орын (Нью-Йорк). World Scholar’s Cup-та 22 оқушы 61 медаль (22 алтын). «Ілияс оқулары», «Абай оқулары» жеңімпаздары – республикалық деңгейде.</li>
            <li><strong>Мұғалімдер жетістіктері:</strong> Жаңабаев Н.Б. – ҚР Оқу-ағарту министрлігінің Алғыс хаты, халықаралық IMPACT олимпиадасының күміс медалі. Бірнеше мұғалім «Педстарт» республикалық олимпиадасының жеңімпаздары. Мұғалімдер облыстық, республикалық басылымдарда мақалалар жариялауда.</li>
            <li>Мектепте қалалық және облыстық іс-шаралар өткізілді: «Мамандық таңдау» коучингі, «Үздік жас маман», «DIRECTOR'S CUP» дебат турнирі, облыстық математикалық олимпиада.</li>
            <li>Ерекше аттестат – 6 оқушы, Алтын белгі – 29, Үздік аттестат – 37. Kundelik.kz 100% көрсеткіші.</li>
        </ul>

        <h3 style="color: #c62828 !important; border-bottom: 2px solid #c62828; padding-bottom: 8px; margin-top: 30px; margin-bottom: 15px;">Әлсіз тұстар</h3>
        <ul>
            <li>АКТ құрал-жабдықтарын жаңарту қажеттілігі.</li>
            <li>Мектепте ағымдағы жөндеу жұмыстарын жүргізу қажет.</li>
        </ul>

        <h3 style="color: #1565c0 !important; border-bottom: 2px solid #1565c0; padding-bottom: 8px; margin-top: 30px; margin-bottom: 15px;">Мүмкіндіктер</h3>
        <ul>
            <li>Халықаралық білім жобаларына қатысуды кеңейту.</li>
            <li>Басқа қалалардағы үздік мектептермен тәжірибе алмасу.</li>
            <li>Мұғалімдердің кәсіби шеберлігін арттыру семинарларын жүйелі ұйымдастыру.</li>
        </ul>

        <h3 style="color: #ef6c00 !important; border-bottom: 2px solid #ef6c00; padding-bottom: 8px; margin-top: 30px; margin-bottom: 15px;">Қауіп-қатер</h3>
        <ul>
            <li>Педагогикалық кадрлардың кейбір пәндер бойынша тұрақсыздығы.</li>
            <li>Аттестацияға құжаттардың уақытылы дайындалмау қаупі.</li>
        </ul>

        <h3 style="color: #6a1b9a !important; border-bottom: 2px solid #6a1b9a; padding-bottom: 8px; margin-top: 30px; margin-bottom: 15px;">Ұсыныстар</h3>
        <ul>
            <li>Барлық мұғалімдердің біліктілік арттыру курстарынан өтуін қамтамасыз ету.</li>
            <li>Жас мамандарға ақылы тәлімгерлік институтын енгізу.</li>
            <li>Мектептің материалдық-техникалық базасын кезең-кезеңмен жаңарту.</li>
        </ul>

        <p style="margin-top: 30px; font-style: italic; color: #888;">Талдау 2023-2024 оқу жылына негізделген.</p>
    </div>
</div>

<?php
include __DIR__ . '/../include/footer.php';
?>