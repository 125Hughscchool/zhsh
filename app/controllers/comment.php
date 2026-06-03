<?php

require __DIR__ . '/../path.php';
include __DIR__ . '/../app/controllers/posts.php';



$commentsForAdm = selectAll('comments');

//$page = $_GET['post'];

$email = '';
$comment = '';
$errMsg = '';
$status = 0;
$comments = [];


//// Код для формы создания комментариев
//if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['goComment'])){
//
//
//    $email = trim($_POST['email']);
//    $comment = trim($_POST['comment']);
//
//    if ($email === '' || $comment === ''){
//        $errMsg = "Не все поля заполнены!";
//
//    } elseif (mb_strlen($comment, 'UTF-8')<10){
//        $errMsg = "Содержание комментарии должен быть длинее 10-ти символов!";
//    }else{
//        $user = selectOne('users', ['email' => $email]);
//        if ($user['email'] == $email){
//            $status = 1;
//        }
//
//        $comment = [
//            'status' => $status,
//            'page' => $page,
//            'email' => $email,
//            'comment' => $comment,
//
//        ];
//
//        $comment= insert('comments', $comment);
//        $comments = selectAll('comments', ['page' => $page, 'status' => 1]);
//
//    }
//
//
//}else {
////    echo 'GET';
//    $email = '';
//    $comment = '';
//    $comments = selectAll('comments', ['page' => $page, 'status' => 1]);
//
//}


// удаление комментария
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete_id'])){
    $id = $_GET['delete_id'];
    delete('comments', $id);
    header('location: ' . BASE_URL . 'admin/commentsAdm/index.php');
}


// статус комментария
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['pub_id'])){
    $id = $_GET['pub_id'];
    $publish = $_GET['publish'];

    $post_id = update('comments', $id, ['status' => $publish]);

    header('location: ' . BASE_URL . 'admin/commentsAdm/index.php');

}

// редактивание комментария
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])){


    $id = $_GET['id'];
    $comment = selectOne('comments', ['id' => $id]);
    $id = $comment['id'];
    $email = $comment['email'];
    $comment = $comment['comment'];


}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment-edit'])){


    $id = $_POST['id'];
    $comment = trim($_POST['comment']);
    $publish = isset($_POST['publish'])? 1 : 0;


    if ($comment === ''){
        $errMsg = "Комментарий не имеет содержимого текста!";

    } elseif (mb_strlen($comment, 'UTF-8')<10){
        $errMsg = "Количество символов внутри комментария меньше 10-ти символов!!";
    }else{
        $com = [
            'comment' => $comment,
            'status' => $publish,

        ];

        $comment = update('comments', $id, $com);
        header('location: ' . BASE_URL . 'admin/commentsAdm/index.php');

    }


}
else {

    $publish = isset($_POST['publish'])? 1 : 0;

}