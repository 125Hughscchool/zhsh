<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
  
}

require __DIR__ . '/../../path.php';
require __DIR__ . '/../../app/database/db.php';
include __DIR__ . '/../../app/controllers/posts.php';

if (!isset($_SESSION['id']) || $_SESSION['id'] <= 0) {
    header('Location: ' . BASE_URL . 'account/signin.php');
    exit();
}

$errMsg = []; // ВАЖНО: в posts.php это массив!
$successMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_post'])) {
    
    $title = trim($_POST['title'] ?? '');
    $anons = trim($_POST['anons'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $publish = isset($_POST['publish']) ? 1 : 0;
    
    // Валидация (как в posts.php)
    if ($title === '' || $anons === '' || $content === '') {
        $errMsg[] = "Не все поля заполнены!";
    } elseif (mb_strlen($title, 'UTF-8') < 10) {
        $errMsg[] = "Название статьи должно быть более 10-ти символов!";
    } elseif (mb_strlen($anons, 'UTF-8') < 10) {
        $errMsg[] = "Краткое описание должно быть более 10 символов!";
    } else {
        // Загрузка изображения (как в posts.php)
        $imgName = '';
        if (!empty($_FILES['img']['name'])) {
            $imgName = time() . "_" . $_FILES['img']['name'];
            $fileTmpName = $_FILES['img']['tmp_name'];
            $fileType = $_FILES['img']['type'];
            $destination = __DIR__ . '/../../assets/img/posts/' . $imgName;

            if (strpos($fileType, 'image') === false) {
                $errMsg[] = "Файл не является изображением!";
            } else {
                if (move_uploaded_file($fileTmpName, $destination)) {
                    // Успешно загружено
                } else {
                    $errMsg[] = "Ошибка загрузки изображения";
                }
            }
        }
        
        // Если нет ошибок — сохраняем
        if (empty($errMsg)) {
            $post = [
                'title' => $title,
                'anons' => $anons,
                'content' => $content,
                'img' => $imgName, // Сохраняем только имя файла, как в posts.php
                'status' => $publish, // ВАЖНО: в posts.php поле называется 'status', а не 'publish'
                'author_id' => $_SESSION['id'] ?? null,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            try {
                // Вызываем insert() как в posts.php
                $result = insert('posts', $post);
                
                if ($result) {
                    // Редирект как в оригинале (чтобы не дублировать код)
                    header('Location: ' . BASE_URL . 'admin/post/index.php?added=1');
                    exit();
                } else {
                    $errMsg[] = "Ошибка при сохранении записи";
                }
            } catch (Throwable $e) {
                $errMsg[] = "Ошибка: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php include __DIR__ . '/../temp/admin_head.php'; ?>
    <style>
        .create-post-wrapper {
            background: #ffffff; padding: 30px; border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin: 20px 0;
        }
        .form-field { margin-bottom: 25px; display: block; clear: both; }
        .form-field label {
            display: block; margin-bottom: 8px; font-weight: 600;
            color: #002E4B; font-size: 14px;
        }
        .form-field input[type="text"],
        .form-field textarea {
            width: 100%; padding: 12px 15px; border: 2px solid #ddd;
            border-radius: 6px; font-size: 14px; font-family: inherit;
            background: #fff; color: #333; box-sizing: border-box; display: block;
        }
        .form-field input[type="text"]:focus,
        .form-field textarea:focus {
            outline: none; border-color: #002E4B; background: #fff;
        }
        .form-field textarea { min-height: 350px; resize: vertical; }
        .form-actions-row {
            display: flex; gap: 20px; align-items: center;
            flex-wrap: wrap; margin-top: 30px; padding-top: 25px;
            border-top: 2px solid #eee;
        }
        .checkbox-wrapper { display: flex; align-items: center; gap: 8px; }
        .checkbox-wrapper input[type="checkbox"] { width: 18px; height: 18px; cursor: pointer; }
        .checkbox-wrapper label {
            cursor: pointer; font-weight: 600; color: #000000 !important; font-size: 15px;
        }
        .file-upload-box { display: inline-block; }
        .file-upload-box input[type="file"] { display: none; }
        .file-upload-label {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 10px 20px; background: #f8f9fa; border: 2px dashed #002E4B;
            border-radius: 6px; cursor: pointer; font-weight: 500;
            color: #002E4B; transition: all 0.2s;
        }
        .file-upload-label:hover { background: #e9ecef; border-color: #004066; }
        .btn-submit-post {
            background: #002E4B; color: #fff; border: none;
            padding: 12px 30px; border-radius: 6px; font-weight: 600;
            cursor: pointer; font-size: 14px; display: inline-flex;
            align-items: center; gap: 8px; transition: background 0.2s;
        }
        .btn-submit-post:hover { background: #004066; }
        .alert-box {
            padding: 15px 20px; border-radius: 6px;
            margin-bottom: 25px; font-weight: 500;
        }
        .alert-error {
            background: #ffe6e6; color: #dc3545; border: 2px solid #ffcccc;
        }
        .alert-success {
            background: #e6ffe6; color: #28a745; border: 2px solid #ccffcc;
        }
        .required { color: #dc3545; }
        .debug-panel {
            background: #fff3cd; border: 2px solid #ffc107;
            padding: 15px; margin: 20px 0; border-radius: 6px;
            font-family: monospace; font-size: 11px;
            max-height: 300px; overflow: auto;
        }
        .debug-panel pre { margin: 0; white-space: pre-wrap; }
        .debug-toggle {
            background: #ffc107; border: none; padding: 5px 15px;
            border-radius: 4px; cursor: pointer; font-weight: 600;
            margin-bottom: 10px;
        }
        .hidden { display: none; }
    </style>
</head>
<body>
<div class="page">
    <div class="container__block">
        <?php include __DIR__ . '/../temp/admin_header.php'; ?>
        <?php include __DIR__ . '/../temp/admin_sidebar.php'; ?>
        
        <main class="main">
            <div class="container">
                <h2 style="margin-bottom: 25px; color: #002E4B; font-size: 24px;">📝 Добавление записи</h2>
                
                <?php if (!empty($errMsg)): ?>
                    <div class="alert-box alert-error">
                        ⚠️ <?php echo implode('<br>', array_map('htmlspecialchars', $errMsg)); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($successMsg)): ?>
                    <div class="alert-box alert-success"><?php echo htmlspecialchars($successMsg); ?></div>
                <?php endif; ?>
                
                <!-- DEBUG PANEL -->
                <button class="debug-toggle" onclick="document.getElementById('debugContent').classList.toggle('hidden')">🔍 Показать отладку</button>
                <div id="debugContent" class="debug-panel hidden">
                    <pre><?php print_r(['POST'=>$_POST, 'FILES'=>$_FILES, 'errMsg'=>$errMsg]); ?></pre>
                </div>
                
                <div class="create-post-wrapper">
                    <form method="post" enctype="multipart/form-data" id="addPostForm">
                        
                        <div class="form-field">
                            <label for="title">Название статьи <span class="required">*</span></label>
                            <input type="text" name="title" id="title" 
                                   value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>" 
                                   placeholder="Минимум 10 символов" required minlength="10">
                        </div>
                        
                        <div class="form-field">
                            <label for="anons">Краткое описание <span class="required">*</span></label>
                            <input type="text" name="anons" id="anons" 
                                   value="<?php echo htmlspecialchars($_POST['anons'] ?? ''); ?>" 
                                   placeholder="Минимум 10 символов" required minlength="10">
                        </div>
                        
                        <div class="form-field">
                            <label for="content">Содержимое записи <span class="required">*</span></label>
                            <textarea name="content" id="content" 
                                      placeholder="Введите текст статьи..." required><?php echo htmlspecialchars($_POST['content'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="form-actions-row">
                            <div class="checkbox-wrapper">
                                <input type="checkbox" name="publish" id="publish" value="1" <?php echo !empty($_POST['publish']) ? 'checked' : ''; ?>>
                                <label for="publish">Опубликовать сразу</label>
                            </div>
                            
                            <div class="file-upload-box">
                                <input type="file" name="img" id="imgUpload" accept="image/*">
                                <label for="imgUpload" class="file-upload-label">📁 Выбрать изображение</label>
                            </div>
                            
                            <button type="submit" name="add_post" class="btn-submit-post">💾 Добавить запись</button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/ckeditor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.querySelector('#content');
    let editorInstance = null;
    
    if (textarea && typeof ClassicEditor !== 'undefined') {
        ClassicEditor
            .create(textarea, {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', '|', 'bulletedList', 'numberedList', '|', 'blockQuote', 'undo', 'redo'],
                language: 'ru'
            })
            .then(editor => {
                editorInstance = editor;
                const form = document.getElementById('addPostForm');
                if (form) {
                    form.addEventListener('submit', function() {
                        if (editorInstance) {
                            textarea.value = editorInstance.getData();
                        }
                    });
                }
            })
            .catch(error => {
                console.error('CKEditor error:', error);
                if (textarea) {
                    textarea.style.minHeight = '350px';
                    textarea.style.padding = '12px';
                    textarea.style.border = '2px solid #ddd';
                }
            });
    }
    
    const debugToggle = document.querySelector('.debug-toggle');
    const debugContent = document.getElementById('debugContent');
    if (debugToggle && debugContent) {
        debugContent.classList.add('hidden');
        debugToggle.addEventListener('click', function() {
            debugContent.classList.toggle('hidden');
        });
    }
});
</script>
</body>
</html>