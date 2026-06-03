<?php
include_once  __DIR__ . '/../../app/database/db.php';




$errMsg = '';
$id = '';
$categories = '';
$topicsAll = selectAll('topics');
$topicOptions = selectAll('topics');
// форма логики категории
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['topic-create'])){
    
    // md($_POST);
    // exit();

    $categories = trim($_POST['name']);
    $parent_id = $_POST['parent_id'] !== '' ? (int)$_POST['parent_id'] : null;

    if ($categories === '' ){
        $errMsg = "Не все поля заполнены!";

    } elseif (mb_strlen($categories, 'UTF-8')<5){
        $errMsg = "Категория должнаЫ быть более 5-nb символов!";
    }else{
        $existence = selectOne('topics', ['name' => $categories]);
        if ($existence['name'] === $categories){
            $errMsg = "Такая категория уже есть в Базе!";
        }else{
            $topics = [
                'name' => $categories,
                'parent_id' => $parent_id
            ];

            $id = insert('topics', $topics);
            $topic = selectOne('topics', ['id' => $id]);
            header('location: ' . BASE_URL . 'admin/topics/index.php');

        }

    }

}else {
//    echo 'GET';
    $categories = '';
}


// редактивание категории
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])){
    $id = $_GET['id'];
    $topics = selectOne('topics', ['id' => $id]);
    $id = $topics['id'];
    $categories = $topics['name'];


}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['topic-edit'])){

    $categories = trim($_POST['name']);
    $parent_id = $_POST['parent_id'] !== '' ? (int)$_POST['parent_id'] : null;
    if ($categories === '' ){
        $errMsg = "Не все поля заполнены!";

    } elseif (mb_strlen($categories, 'UTF-8')<5){
        $errMsg = "Категория должнаЫ быть более 5-ти символов!";
    }else{
            $topics = [
                'name' => $categories,
                'parent_id' => $parent_id
            ];

            $id = $_POST['id'];
            $topics_id = update('topics', $id, $topics);
            header('location: ' . BASE_URL . 'admin/topics/index.php');

        }
}


// удаление категории
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['del_id'])) {
    $id = $_GET['del_id'];
    deleteCategoryRecursive($id);
    header('location: ' . BASE_URL . 'admin/topics/index.php');
}