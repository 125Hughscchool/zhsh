<?php
include "../../path.php"; 
include "../../app/controllers/users.php";

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
                    <a class="btn--red btn--rounded btn" href="<?php echo BASE_URL . "admin/users/created.php";?>">Создать</a>
                    

                    <div class="post__content">
                        <h1>Создать пользователя</h1>
                        <div class="add-post">
                            <form class="add-post__form" action="edit.php" method="post">

                                <div class="form__group">

                                    <div class="cabinet">
                                        <div class="form__left">
                                            <div class="cabinet__form">
                                                <input name="id" value="<?=$id;?>" type="hidden">
                                                <div class="form__group">
                                                    <input name="name" value="<?=$name;?>" type="text" placeholder="Имя ползователя" class="form__control form__control--md">
                                                    <span class="form__line-profile">   </span>
                                                </div>

                                                <div class="form__group">
                                                    <input name="email" value="<?=$email;?>"  type="email" placeholder="e-mail"  readonly class="form__control form__control--md">
                                                    <span class="form__line">   </span>
                                                </div>

                                                <div class="form__group">
                                                    <input name="pass_first" type="password" placeholder="Сбросить пароль"  class="form__control form__control--md">
                                                    <span class="form__line">   </span>
                                                </div>

                                                <div class="form__group">
                                                    <input name="pass_second" type="password" placeholder="Подтвердить пароль"  class="form__control form__control--md">
                                                    <span class="form__line">   </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form__right">



                                        </div>

                                    </div>
                                    <div class="footer__form-btn">
                                        <button name="update_user" class="btn btn--blue btn--rounded " type="submit">Обновить</button>
                                        <div class="checkbox">
                                            <label class="checkbox__check"> ADMIN?
                                                <input name="admin" type="checkbox" id="checked">
                                            </label>
                                        </div>
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



</body>

</html>


