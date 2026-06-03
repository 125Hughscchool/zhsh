<?php
include_once  __DIR__ . '/../../app/database/db.php';



$errMsg = '';
$id = '';
$categories = '';
$description = '';
$s_topicsAll = selectAll('s_topics');

// форма логики категории
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['s_topic-create'])){
    
    //md($_POST);
    // exit();

    $categories = trim($_POST['name']);
    $description = trim($_POST['description']);


    if ($categories === ''){
        $errMsg = "Не все поля заполнены!";

    } elseif (mb_strlen($categories, 'UTF-8')<5){
        $errMsg = "Категория должнаЫ быть более 5-nb символов!";
    }else{
        $existence = selectOne('s_topics', ['name' => $categories]);
        if ($existence['name'] === $categories){
            $errMsg = "Такая категория уже есть в Базе!";
        }else{
            $s_topics = [
                'name' => $categories,
                'description' => $description,

            ];

            $id = insert('s_topics', $s_topics);
            $s_topics = selectOne('s_topics', ['id' => $id]);
            header('location: ' . BASE_URL . 'admin/s_topics/index.php');
            md($id);
        }

    }

}else {
//    echo 'GET';
    $categories = '';
    $description = '';
}


// редактивание категории
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])){
    $id = $_GET['id'];
    $s_topics = selectOne('s_topics', ['id' => $id]);
    $id = $s_topics['id'];
    $categories = $s_topics['name'];
    $description = $s_topics['description'];


}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['s_topic-edit'])){

    $categories = trim($_POST['name']);
    $description = trim($_POST['description']);

    if ($categories === '' ){
        $errMsg = "Не все поля заполнены!";

    } elseif (mb_strlen($categories, 'UTF-8')<5){
        $errMsg = "Категория должнаЫ быть более 5-ти символов!";
    }else{
            $s_topics = [
                'name' => $categories,
                'description' => $description,

            ];

            $id = $_POST['id'];
            $s_topics_id = update('s_topics', $id, $s_topics);
            header('location: ' . BASE_URL . 'admin/s_topics/index.php');

        }
}


// удаление категории
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['del_id'])){
    $id = $_GET['del_id'];
    delete('s_topics', $id);
    header('location: ' . BASE_URL . 'admin/s_topics/index.php');
}