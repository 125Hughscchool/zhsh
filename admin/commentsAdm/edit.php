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
                <div class="post">
                    <div class="post__content">
                        <h1>Редактирование комментария</h1>
                        <div class="err">

                        </div>
                        <table class="content__table">
                            <div class="add-post">
                                <form  class="add-post__form" action="edit.php" method="post" >
                                    <input name="id" value="<?=$id;?>" type="hidden" >
                                    <div class="form__group">

                                        <input name="email" readonly value="<?=$email?>" type="text" placeholder="Название статьи" class="form__control form__control--md">
                                        <span class="form__line-profile">   </span>
                                    </div>
                                    <div class="form__group">
                                        <textarea class="add-post__textarea" name="comment" placeholder="Содержимое записи"><?=$comment;?></textarea>
                                    </div>

                                    <div class="form__button">

                                        <div class="checkbox">
                                            <?php if (empty($publist) && $publish == 0): ?>
                                            <label class="checkbox__check" for="flexCheckChecked">Опубликовать
                                                <input name="publish" type="checkbox" id="checked" >
                                            </label>
                                            <?php else: ?>
                                                <label class="checkbox__check" for="flexCheckChecked">Опубликовать
                                                    <input name="publish" type="checkbox" id="checked">
                                                </label>
                                            <?php endif;?>
                                        </div>

                                        <button class="btn btn--blue btn--rounded btn--save" name="comment-edit" type="submit">Сохранить</button>
                                    </div>

                                </form>
                            </div>
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
    </div>

</div>

<script src="https://cdn.ckeditor.com/ckeditor5/36.0.0/classic/ckeditor.js"></script>
<script src="../../assets/js/admin-js.js"></script>

</body>

</html>

