<?php
   session_start();
    include "../../path.php"; 
   
    include "../../app/controllers/s_topics.php";

    if(!$_SESSION){
        header ('location:' . BASE_URL . '/account/signin.php');
    }
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <?php include("../temp/admin_head.php");?>
</head>

<body>

<div class="page">
    <div class="container__block">

        <!-------------------------header------------------------>
        <?php include("../temp/admin_header.php");?>
        <!-------------------------header end------------------------>

        <!-------------------------sidebar------------------------>
        <?php include("../temp/admin_sidebar.php");?>
        <!-------------------------sidebar end------------------------>

        <!-------------------------main----------------------------->
        <main class="main">
            <div class="container">
                <div class="post">
                    <a class="btn--red btn--rounded btn" href="<?php echo BASE_URL . "admin/s_topics/created.php";?>">Добавить </a>
                    

                    <div class="post__content">
                        <h1>Управление категориями </h1>
                        <table class="content__table">
                            <tr class="table__inner">

                                <th class="table__title">ID</th>
                                <th class="table__title">Наименование</th>
                                <th colspan="2" class="table__title">управление</th>
                                <th class="table__title">Дата публикации</th>

                            </tr>
                            <?php foreach ($s_topicsAll as $key => $topics):?>
                            <tr class="table__inner">
                                <td class="table__item"><?= $key + 1; ?></td>
                                <td class="table__item"><?=$topics['name']; ?></td>
                                <td class="table__item"><a href="edit.php?id=<?=$topics['id'];?>">edit</a></td>
                                <td class="table__item"><a href="edit.php?del_id=<?=$topics['id'];?>">delete</a></td>
                                <td class="table__item"><a href="#"><?=$topics['datatime']; ?></a></td>
                            </tr>
                            <?php endforeach;?>

                        </table>
                    </div>
                    <!-- <div class="post__footer">
                        <ul class="post__data">
                            <li class="post__data-item">
                                <time datetime="2022-11-10 10:02">10.11.2022</time>

                            </li>
                        </ul>
                    </div> -->

                </div>
            </div>
        </main>


        <!-------------------------main end----------------------------->
    </div>

</div>



</body>

</html>

