<?php

include_once __DIR__ . '/../app/database/db.php";

// include '../../path.php';
// include "../../app/database/db.php";




$errMsg = [];
$id = '';
$title = '';
$topic = '';
$userfile = '';
$link = '';
$topics = selectAll('topics');
//$topicsCategory = selectAllFromTopicsWithTopic('posts', 'topics');
$cert = selectAll('cert');
$certAdm = selectAllFromCertWithTopic('cert', 'topics');




// форма для создании записи
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_cert'])){

    if (!empty($_FILES['userfile']['name'])) {
        $fileName = time() . "_" . $_FILES['userfile']['name'];
        $fileTmpName = $_FILES['userfile']['tmp_name'];
        $fileType = $_FILES['userfile']['type'];
        $allowedTypes =['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        
        if(!in_array($fileType, $allowedTypes)) {
            array_push($errMsg, "Invalid file type! Please upload an image, PDF, Word, or Excel file.");
        }else{
            $destination = __DIR__ . '/../assets/files/' . $fileName;

            if (move_uploaded_file($fileTmpName, $destination)) {
                $_POST['userfile'] = $fileName;
            } else {
                array_push($errMsg, "ошибка загрузки файла");
            }
        }
    }else{
        array_push($errMsg, "Ошибка получения документа");
        }
        // md($fileName);
        //  exit();
    $title = trim($_POST['title']);
    $topic = trim($_POST['topic']);
    $link = trim($_POST['link']);
    
    $publish = isset($_POST['publish'])? 1 : 0;

    if ($title === '' || $topic === ''){
        $errMsg = "Не все поля заполнены!";

    } elseif (mb_strlen($title, 'UTF-8')<3){
        $errMsg = "Название стаьи должно быть более 3 символов!";
    }else{
            $cert = [
                'title' => $title,
                'status' => $publish,
                'userfile' => $fileName,
                'link' => $link,
                'id_topic' => $topic

            ];

            $cert= insert('cert', $cert);
            $cert = selectOne('cert', ['id' => $id]);
            header('location: ' . BASE_URL . 'admin/cert/index.php');

        }


}else {
//    echo 'GET';
    $id = '';
    $title = '';
    $publish = '';
    $topic = '';
    $link = '';

}


// редактивание статьи
// if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])){



//     $id = $_GET['id'];
//     $cert = selectOne('v_posts', ['id' => $id]);
//     $id = $cert['id'];
//     $title = $cert['title'];
//     $topic = $cert['id_topic'];
//     $publish = $cert['status'];
//     $link = $cert['link'];


// }

// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['v_post-edit'])){

//     $id = $_POST['id'];
//     $title = trim($_POST['title']);
//     $topic = trim($_POST['topic']);
//     $link = trim($_POST['link']);

//     $publish = isset($_POST['publish'])? 1 : 0;


//     if ($title === '' || $topic === ''){
//         $errMsg = "Не все поля заполнены!";

//     } elseif (mb_strlen($title, 'UTF-8')<5){
//         $errMsg = "Название стаьи должно быть более 5-ти символов!";
//     }else{
//         $cert = [
//             'id_user' => $_SESSION['id'],
//             'title' => $title,
//             'status' => $publish,
//             'link' => $link,
//             'id_topic' => $topic

//         ];

//         $cert_id = update('cert', $id, $cert);
//         header('location: ' . BASE_URL . 'admin/cert/index.php');

//     }


// }
// else {
//     $title = 'title';
//     $publish = isset($_POST['publish']) ? 1 : 0;
//     $topic = 'id_topic';
//     $link = '';

// }

// статус статьи
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['pub_id'])){
    $id = $_GET['pub_id'];
    $publish = $_GET['publish'];

    $cert_id = update('cert', $id, ['status' => $publish]);

    header('location: ' . BASE_URL . 'admin/cert/index.php');

}


// удаление статьи
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete_id'])){
    $id = $_GET['delete_id'];
    delete('cert', $id);
    header('location: ' . BASE_URL . 'admin/cert/index.php');
}