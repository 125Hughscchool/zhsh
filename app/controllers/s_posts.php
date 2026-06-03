<?php
session_start();

/ ✅ Правильный путь
$root = __DIR__ . '/../../';
require_once $root . 'path.php';
require_once $root . 'app/database/db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$errMsg = [];
$id = '';
$title = '';
$content = '';
$img = '';
$s_topic = '';

// ✅ Загружаем ТОЛЬКО категории (это нужно для created.php)
global $pdo;
$s_topics = $pdo->query("SELECT id, name FROM s_topics ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

// ❌ Закомментируй эти строки (функции не существуют):
// $s_posts = selectAll('s_posts');
// $s_postsAdm = selectAllFromSPostsWithStopics('s_posts', 's_topics');


// форма для создании записи
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_s_post'])){

    if (!empty($_FILES['img']['name'])) {
        $imgName = time() . "_" . $_FILES['img']['name'];
        $fileTmpName = $_FILES['img']['tmp_name'];
        $fileType = $_FILES['img']['type'];
        $destination =  __DIR__ . '/../../assets/img/posts/' . $imgName;

        if (strpos($fileType, 'image') === false) {
            array_push($errMsg, "Файл не является изображением!");
        } else {

            $result = move_uploaded_file($fileTmpName, $destination);

            if ($result) {
                $_POST['img'] = $imgName;
            } else {
                array_push($errMsg, "ошибка загрузки изображения");
            }

        }
    }else{
        array_push($errMsg, "Ошибка получения картинки");
        }

    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $s_topic = trim($_POST['id_s_topic']);
    $publish = isset($_POST['publish'])? 1 : 0;

    if ($title === '' || $content === ''){
        $errMsg = "Не все поля заполнены!";

    } elseif (mb_strlen($title, 'UTF-8')<7){
        $errMsg = "Название стаьи должно быть более 7-ми символов!";
    }else{
            $s_post = [
                'title' => $title,
                'content' => $content,
                'img' => $_POST['img'],
                'status' => $publish,
                'id_s_topic' => $s_topic

            ];
            // md($s_post);
            // exit();

            $insertedPost = insert('s_posts', $s_post);
        if ($insertedPost) {
            header('location: ' . BASE_URL . 'admin/s_post/index.php');
            exit();
        } else {
            array_push($errMsg, "Ошибка при добавлении поста в базу данных");
        }

        }


}else {
//    echo 'GET';
    $id = '';
    $title = '';
    $content = '';
    $publish = '';
    $s_topic = '';

}




// статус статьи
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['pub_id'])){
    $id = $_GET['pub_id'];
    $publish = $_GET['publish'];

    $s_post_id = update('s_posts', $id, ['status' => $publish]);

    header('location: ' . BASE_URL . 'admin/s_post/index.php');

}


// удаление статьи
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete_id'])){
    $id = $_GET['delete_id'];
    delete('s_posts', $id);
    header('location: ' . BASE_URL . 'admin/s_post/index.php');
}