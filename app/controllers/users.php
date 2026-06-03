<?php
    include  __DIR__ . '/../../app/controllers/posts.php';

    // if(!$_SESSION){
    //     header ('location:' . BASE_URL . '/account/signin.php');
    // }

    // if(isset($_POST['name'])){
    //     $admin = 0;
    //     $name = $_POST['name'];
    //     $email = $_POST['email'];
    //     $pass = password_hash($_POST['pass_first'], PASSWORD_DEFAULT);

    //     $post = [
    //         'admin' => $admin,
    //         'username' => $name,
    //         'email' => $email,
    //         'password' => $pass
    //     ];
    //     insert('users', $post);
       
    // }


$errMsg = '';
$users = selectAll('users');



// форма логики добавления ползователя в админке

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])){

    $admin = 0;
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $passF = trim($_POST['pass_first']);
    $passS = trim($_POST['pass_second']);



    if ($name === '' || $email === '' || $passF === ''){
        $errMsg = "Не все поля заполнены!";

    } elseif (mb_strlen($name, 'UTF-8')<5){
        $errMsg = "Имя должен быть более 5-ти символов!";
    } elseif ($passF !== $passS){
        $errMsg = "Пороли в обеих полях должны соответствовать!";

    } else{

        $existence = selectOne('users', ['email' => $email]);
        if ($existence['email'] === $email){
            $errMsg = "Пользователь с такой почтой уже зарегистрирован!";
        }else{
            $pass = password_hash($passF, PASSWORD_DEFAULT);
            if (isset($_POST['admin'])) $admin = 1;
            $user = [
                'admin' => $admin,
                'username' => $name,
                'email' => $email,
                'password' => $pass
            ];

            $id = insert('users', $user);

            $user = selectOne('users', ['id' => $id]);
            $_SESSION['id'] = $user['id'];
            $_SESSION['name'] = $user['username'];
            $_SESSION['admin'] = $user['admin'];

            if ($_SESSION['admin']){
                header('location: ' . BASE_URL . 'admin/users/index.php');
            }else {
                header('location: ' . BASE_URL . 'admin/users/index.php') ;
            }




        }

    }

}else {

    $name = '';
    $email = '';
}

// редактивание пользователя

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['edit_id'])){


    $user = selectOne('users', ['id' => $_GET['edit_id']]);
    $id = $user['id'];
    $name = $user['username'];
    $email = $user['email'];
    $status = $user['admin'];



}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])){

    $id = $_POST['id'];
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $passF = trim($_POST['pass_first']);
    $passS = trim($_POST['pass_second']);

    $status = isset($_POST['admin'])? 1 : 0;

    if ($name === '' || $email === ''){
        $errMsg = "Не все поля заполнены!";

    } elseif (mb_strlen($name, 'UTF-8')<5){
        $errMsg = "Имя должно быть более 5-ти символов!";
    }elseif ($passF !== $passS){
        $errMsg = "Пороли в обеих полях должны соответствовать!";

    }else{
        $pass = password_hash($passF, PASSWORD_DEFAULT);
        if (isset($_POST['admin'])) $admin = 1;
        $user = [
            'admin' => $status,
            'username' => $name,
            'email' => $email,
            'password' => $pass
        ];


        $user_up = update('users', $id, $user);
        header('location: ' . BASE_URL . 'admin/users/index.php');

    }


}


// удаление пользователей

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete_id'])){
    $id = $_GET['delete_id'];
    delete('users', $id);
    header('location: ' . BASE_URL . 'admin/users/index.php');
}
