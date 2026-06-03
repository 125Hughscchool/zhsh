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
                    <a class="btn--red btn--rounded btn" href="<?php echo BASE_URL . "admin/s_topics/created.php";?>">Добавить</a>
                    

                    <div class="post__content">
                        <h1>Обнавление категории События, Эл. дневник, Уроки исс.</h1>
                        <div class="err">
                            <p ><?="$errMsg"?></p>
                        </div>
                        <table class="content__table">
                            <div class="add-post">
                                <form class="add-post__form" action="edit.php" method="post">
                                    <input name="id" type="hidden" value="<?=$id?>">
                                    <div class="form__group">
                                        <input name="name" type="text" value="<?=$categories?>" placeholder="Название категории" class="form__control form__control--md">
                                        <span class="form__line-profile">   </span>
                                    </div>
                                    

                                    <button name="s_topic-edit" class="btn btn--blue btn--rounded " type="submit">Обновить</button>

                                </form>
                            </div>
                    </div>
                    

                </div>
            </div>
        </main>


        <!-------------------------main end----------------------------->
    </div>

</div>



</body>

</html>


