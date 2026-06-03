<?php
require __DIR__ . '/../path.php';
include __DIR__ . '/../app/controllers/comment.php';

if(!$_SESSION){
    header ('location:' . BASE_URL . '/account/signin.php');
}
?>
<!DOCTYPE html>
<html>

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
                <div class="post" >
                
                    <div class="post__content">
                        <h1>Управление комментариями</h1>
                        <table class="content__table" >
                            <tr class="table__inner">
                                <th class="table__title">ID</th>
                                <th class="table__title">Текст коммент.</th>
                                <th class="table__title">Автор</th>
                                <th class="table__title">Категория</th>
                                <th colspan="2" class="table__title">управление</th>
                                <th class="table__title">Дата создание</th>
                                <th class="table__title">Статус</th>

                            </tr>
                            <?php foreach ($commentsForAdm as $key => $comment):?>
                            <tr class="table__inner">
                                <td class="table__item"><?= $comment['id']; ?></td>
                                <td class="table__item"><?=mb_substr($comment['comment'], 0, 50, 'UTF-8') . '...';?></td>
                                <td class="table__item"><?= $comment['email']; ?></td>

                                <td class="table__item">1</td>

                                <td class="table__item"><a href="edit.php?id=<?=$comment['id'];?>">edit</a></td>
                                <td class="table__item"><a href="edit.php?delete_id=<?=$comment['id'];?>">delete</a></td>
                                <td class="table__item"><a href="#"><?=$comment['created_date']; ?></a></td>
                                <?php if ($comment['status']): ?>
                                    <td class="table__item"><a href="edit.php?publish=0&pub_id=<?=$comment['id'];?>">Опубликаовано</a></td>
                                <?php else:?>
                                    <td class="table__item"><a href="edit.php?publish=1&pub_id=<?=$comment['id'];?>">Не Опубликовано</a></td>
                                <?php endif;?>
                            </tr>
                            <?php endforeach;?>

                        </table>
                    </div>
<!--                    <div class="post__footer">-->
<!--                        <ul class="post__data">-->
<!--                            <li class="post__data-item">-->
<!--                                <time datetime="2022-11-10 10:02">10.11.2022</time>-->
<!---->
<!--                            </li>-->
<!--                        </ul>-->
<!--                    </div>-->

                </div>
            </div>
        </main>


        <!-------------------------main end----------------------------->
        <!----modal------------------------------------------------------>
        <div class="modal" id="contact-modal">
            <div class="modal__content">
                <form class="form" action="/" method="post">
                    <div class="cabinet">
                        <div class="cabinet__form">

                            <div class="form__group">
                                <input type="text" placeholder="Ваша Имя"  class="form__control form__control--md">
                                <span class="form__line">   </span>
                            </div>

                            <div class="form__group">
                                <input type="email" placeholder="Ваш e-mail"  class="form__control form__control--md">
                                <span class="form__line">   </span>
                            </div>

                            <div class="form__group">
                                <textarea class="form__control form__control--md form__control--textarea" placeholder="Текст сообщения"></textarea>
                                <span class="form__line">   </span>
                            </div>


                        </div>

                    </div>
                    <button class="btn btn--blue btn--rounded " type="submit">Отправить</button>
                </form>

                <button class="modal__cancel" type="button" >
                    <img src="assets/img/cancel.svg" alt="закрыт">
                </button>

                <ul class="modal__footer">
                    <li>e-mail: <a href="kaz.comp@bk.ru">kaz.comp@bk.ru</a>
                    </li>
                    <li>тел.: <a href="+77054144456">8 705 414 44 56</a>
                    </li>
                </ul>
            </div>



        </div>
        <!----modal end--------------------------------------------------->

        <!----modal stories------------------------------------------------------>
        <div class="modal" id="story-modal">
            <div class="modal__content modal__content--story">
                <video class="modal__video" poster="assets/img/vid.png" src=""></video>
            </div>
        </div>
        <!----modal stories end----------------------------------------------------->


    </div>

</div>

<!-- <?php include '../../app/temp/script.php'?> -->

</body>

</html>
