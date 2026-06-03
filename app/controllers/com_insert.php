<?php

include __DIR__ . '/../app/controllers/posts.php';

$commentsForAdm = selectAll('comments');


$page = $_GET['post'];
$id_user = '';
$email = '';
$comment = '';
$errMsg = '';
$status = 0;
$comments = [];


// Код для формы создания комментариев
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['goComment'])){


    $email = trim($_POST['email']);
    $comment = trim($_POST['comment']);

    if ($email === '' || $comment === ''){
        $errMsg = "Не все поля заполнены!";

    } elseif (mb_strlen($comment, 'UTF-8')<10){
        $errMsg = "Содержание комментарии должен быть длинее 10-ти символов!";
    }else{

        $user_id = selectOne('users', ['email' => $email]);
        if ($user_id['email'] == $email){
            $status = 1;
        }else{
            $errMsg = "Email $email с таким адресом не существует!";
        }

        $comment = [
            'id_user' => $_SESSION['id'],
            'status' => $status,
            'page' => $page,
            'email' => $email,
            'comment' => $comment,

        ];

        $comment= insert('comments', $comment);
        $comments = selectAll('comments', ['page' => $page, 'status' => 1]);

    }


}else {
//    echo 'GET';
    $email = '';
    $comment = '';
    $comments = selectAll('comments', ['page' => $page, 'status' => 1]);

}
