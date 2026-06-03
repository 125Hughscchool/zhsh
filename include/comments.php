<?php
//include_once SITE_ROOT . "app/controllers/com_insert.php";


?>

<h3 class="postsub__title">Сіздің пікіріңіз</h3>
<div class="err">
    <p ><?="$errMsg"?></p>
</div>
<form class="formcomment" action="<?=BASE_URL . "post.php?post=$page";?>" method="post">
    <input name="page" value="<?=$page;?>" type="hidden">
    <div class="formcomment__group">
        <input class="formcomment__control-data" name="email" type="email" placeholder="E-mail адресіңіз">
    </div>
    <div class="formcomment__group">
        <textarea class="formcomment__control-data" name="comment" placeholder="Мәтін мазмұны"></textarea>
    </div>
    <button class="btn btn--doc" name="goComment" type="submit">Жіберу</button>
</form>

<?php if (count($comments) > 0): ?>
<h3>Барлық пілірлер жазбалары</h3>
<?php foreach ($comments as $comment):?>
        <ul class="comments">
            <li class="comments__item">
                <div class="comments__header">
                    <img src="assets/img/icon/ava.png" alt="" class="commenta__avatar">
                    <div class="comments__author">
                        <div class="comments__name"><?=$comment['email'];?></div>
                        <time class="comments__pubdate" datatime="2022-11-25 15:35"><?=$comment['created_date'];?></time>
                    </div>
                </div>
                <div class="comments__text"><?=$comment['comment'];?></div>
                <!-- <button class="comments__reply">Жауап беру</button> -->
            </li>
        </ul>
<?php endforeach;?>
<?php endif; ?>