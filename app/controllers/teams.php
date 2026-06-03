<?php
include_once __DIR__ . '/../../app/database/db.php';
include '../../path.php';
include '../../app/database/db.php';

$errMsg = [];
$id = '';
$name = '';
$position = ''; // ✅ Было $profession
$photo = '';    // ✅ Было $img

$teams = selectAll('team');

// 📝 Создание записи
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_team'])) {
    
    $photoName = '';
    if (!empty($_FILES['photo']['name'])) { // ✅ Было 'img'
        $photoName = time() . "_" . $_FILES['photo']['name'];
        $fileTmpName = $_FILES['photo']['tmp_name'];
        $fileType = $_FILES['photo']['type'];
        $destination = __DIR__ . '/../../assets/img/team/' . $photoName;

        if (strpos($fileType, 'image') === false) {
            $errMsg[] = "Файл не является изображением!";
        } else {
            if (move_uploaded_file($fileTmpName, $destination)) {
                $photoName = $photoName;
            } else {
                $errMsg[] = "Ошибка загрузки изображения";
            }
        }
    }

    $name = trim($_POST['name']);
    $position = trim($_POST['position']); // ✅ Было 'profession'
    
    if ($name === '' || $position === '') {
        $errMsg[] = "Не все поля заполнены!";
    } elseif (mb_strlen($name, 'UTF-8') < 12) {
        $errMsg[] = "Имя учителя должно быть более 12 символов!";
    } else {
        $team = [
            'name' => $name,
            'position' => $position, // ✅
            'photo' => $photoName,   // ✅
            'bio' => trim($_POST['bio'] ?? '')
        ];

        insert('team', $team);
        header('Location: ' . BASE_URL . 'admin/team/index.php');
        exit;
    }
}

// ✏️ Редактирование
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $team = selectOne('team', ['id' => $id]);
    $name = $team['name'];
    $position = $team['position']; // ✅
    $photo = $team['photo'];       // ✅
    $bio = $team['bio'] ?? '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['team-edit'])) {
    $id = $_POST['id'];
    $name = trim($_POST['name']);
    $position = trim($_POST['position']); // ✅
    $bio = trim($_POST['bio'] ?? '');
    
    $photoName = $_POST['old_photo'] ?? '';
    
    if (!empty($_FILES['photo']['name'])) { // ✅
        $photoName = time() . "_" . $_FILES['photo']['name'];
        $fileTmpName = $_FILES['photo']['tmp_name'];
        $fileType = $_FILES['photo']['type'];
        $destination = __DIR__ . '/../../assets/img/team/' . $photoName;

        if (strpos($fileType, 'image') !== false) {
            if (move_uploaded_file($fileTmpName, $destination)) {
                // Удаляем старое фото
                $old = $_POST['old_photo'] ?? '';
                if ($old && file_exists(__DIR__ . '/../../assets/img/team/' . $old)) {
                    unlink(__DIR__ . '/../../assets/img/team/' . $old);
                }
            }
        }
    }

    if ($name === '' || $position === '') {
        $errMsg[] = "Не все поля заполнены!";
    } else {
        $team = [
            'name' => $name,
            'position' => $position,
            'photo' => $photoName,
            'bio' => $bio
        ];
        update('team', $id, $team);
        header('Location: ' . BASE_URL . 'admin/team/index.php');
        exit;
    }
}

// 🗑️ Удаление
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $team = selectOne('team', ['id' => $id]);
    
    // Удаляем фото файла
    if (!empty($team['photo'])) {
        $path = __DIR__ . '/../../assets/img/team/' . $team['photo'];
        if (file_exists($path)) unlink($path);
    }
    
    delete('team', $id);
    header('Location: ' . BASE_URL . 'admin/team/index.php');
    exit;
}
?>