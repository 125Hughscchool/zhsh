<?php   
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/../path.php';
require __DIR__ . '/../app/database/connect.php';
require __DIR__ . '/../app/controllers/topics.php';
global $pdo;

include __DIR__ . '/../include/head.php';
include __DIR__ . '/../include/sprite.php';
include __DIR__ . '/../include/header.php';

// Получаем ID категории из URL
$categoryId = isset($_GET['id']) ? (int)$_GET['id'] : null;
if (!$categoryId) {
    die('<div class="container"><h2 style="color:red; padding:40px; text-align:center;">❌ Категория не указана</h2></div>');
}

// Пагинация
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 100;
$offset = $limit * ($page - 1);

// Получаем общее количество документов
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM cert WHERE category_id = ?");
    $stmt->execute([$categoryId]);
    $total_doc = $stmt->fetch()['total'];
    $total_pages = ceil($total_doc / $limit);
} catch (Exception $e) {
    $total_doc = 0;
    $total_pages = 0;
}

// Получаем документы категории
try {
    $doc = selectAllFromCertificateWithCategoryOnIndex('cert', 'topics', $limit, $offset, $categoryId);
    $categoryInfo = selectOne('topics', ['id' => $categoryId]);
} catch (Exception $e) {
    $doc = [];
    $categoryInfo = null;
}
?>

<div class="container" style="padding: 40px 20px;">
    <?php if ($categoryInfo): ?>
        <h2 class="title-page" style="color:#002E4B; margin-bottom:30px; text-align:center; font-size:28px;">
            <?= htmlspecialchars($categoryInfo['name']); ?>
        </h2>
        <?php if (!empty($categoryInfo['description'])): ?>
            <p style="text-align:center; color:#666; margin-bottom:40px; font-size:16px;">
                <?= htmlspecialchars($categoryInfo['description']); ?>
            </p>
        <?php endif; ?>
    <?php else: ?>
        <h2 class="title-page" style="color:#002E4B; margin-bottom:30px; text-align:center;">📄 Документы</h2>
    <?php endif; ?>

    <?php if (!empty($doc)): ?>
        <div class="table" style="background:#fff; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,0.1); overflow:hidden;">
            <table class="content__table" style="width:100%; border-collapse:collapse;">
                <thead style="background:#002E4B; color:#fff;">
                    <tr class="table__inner">
                        <th class="table__title" style="padding:15px; text-align:left;">№</th>
                        <th class="table__title" style="padding:15px; text-align:left;">Құжат атауы</th>
                        <th class="table__title" style="padding:15px; text-align:center;">Ашу/Жүктеп алу</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($doc as $key => $d): ?>
                        <tr class="table__inner" style="border-bottom:1px solid #eee;">
                            <td class="table__item table__itemNum" style="padding:15px; font-weight:600;">
                                <?= $key + 1 + $offset ?>
                            </td>
                            <td class="table__item" style="padding:15px;">
                                <?php if(mb_strlen($d['title'],'UTF-8') > 200): ?>
                                    <span title="<?= htmlspecialchars($d['title']) ?>">
                                        <?= mb_substr($d['title'], 0, 200, 'UTF-8') . '...' ?>
                                    </span>
                                <?php else: ?>
                                    <span><?= htmlspecialchars($d['title']); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="table__item table__itemDown" style="padding:15px; text-align:center;">
                                <div class="down">
                                    <?php 
                                    // Кнопка для файла
                                    if (!empty($d['userfile'])) {
                                        $fileUrl = htmlspecialchars($d['userfile']); 
                                        $fileType = strtolower(pathinfo($fileUrl, PATHINFO_EXTENSION));   
                                        
                                        if ($fileType === 'pdf') {
                                            echo '<a class="btn__green" style="display:inline-block; padding:8px 20px; background:#28a745; color:#fff; text-decoration:none; border-radius:4px; font-weight:600;" target="_blank" href="' . BASE_URL . 'assets/files/' . $fileUrl . '">📄 Толығырақ</a>';
                                        } elseif (in_array($fileType, ['doc', 'docx', 'xls', 'xlsx'])) {   
                                            $viewerUrl = 'https://docs.google.com/viewer?url=' . urlencode(BASE_URL . 'assets/files/' . $fileUrl) . '&embedded=true';
                                            echo '<a class="btn__green" style="display:inline-block; padding:8px 20px; background:#17a2b8; color:#fff; text-decoration:none; border-radius:4px; font-weight:600;" target="_blank" href="' . $viewerUrl . '">📝 Подробнее</a>';
                                        } else {
                                            echo '<a class="btn__green" style="display:inline-block; padding:8px 20px; background:#007bff; color:#fff; text-decoration:none; border-radius:4px; font-weight:600;" target="_blank" href="' . BASE_URL . 'assets/files/' . $fileUrl . '" download>⬇️ Толығырақ</a>';
                                        }
                                    }
                                    
                                    // Кнопка для внешней ссылки
                                    if(!empty($d['link'])) {
                                        echo '<a class="btn__green" style="display:inline-block; padding:8px 20px; background:#FEC50C; color:#002E4B; text-decoration:none; border-radius:4px; font-weight:600; margin-left:10px;" target="_blank" href="' . htmlspecialchars($d['link']) . '">🔗 Толығырақ</a>';
                                    }
                                    
                                    if (empty($d['userfile']) && empty($d['link'])) {
                                        echo '<span style="color:#999;">Файл жоқ</span>';
                                    }
                                    ?> 
                                </div>
                            </td>
                        </tr> 
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Пагинация -->
        <?php if ($total_pages > 1): ?>
            <div style="margin-top:30px; text-align:center;">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <?php if ($i == $page): ?>
                        <span style="display:inline-block; padding:10px 20px; background:#002E4B; color:#fff; border-radius:4px; margin:0 5px; font-weight:600;"><?= $i ?></span>
                    <?php else: ?>
                        <a href="?id=<?= $categoryId ?>&page=<?= $i ?>" style="display:inline-block; padding:10px 20px; background:#f0f0f0; color:#002E4B; text-decoration:none; border-radius:4px; margin:0 5px; font-weight:600;"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <div style="text-align:center; padding:60px; background:#fff; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,0.1);">
            <p style="color:#666; font-size:18px; margin:0;">📭 В этой категории пока нет документов</p>
            <a href="javascript:history.back()" style="display:inline-block; margin-top:20px; padding:10px 25px; background:#002E4B; color:#fff; text-decoration:none; border-radius:4px;">← Назад</a>
        </div>
    <?php endif; ?>
</div>

<?php
include __DIR__ . '/../include/resource.php';
include __DIR__ . '/../include/footer.php';
?>