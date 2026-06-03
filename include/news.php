<div class="news__block">
    <div class="study-club__inner">
        <div class="row__icon"><img src="assets/img/title-ic-1.png" alt=""></div>
        <div class="row__text">Жаңалықтар</div>
    </div>
    <div class="container">
        <div class="news__inner"><?php if (!empty($newposts)): ?>
            <?php $post = reset($newposts); ?>
            <div class="news__left">
                <article class="left__inner">
                    <div class="left__preview">
                        <?php if($post['img']):?>
                            <a href="<?=BASE_URL . 'pages/post.php?post=' . $post['id'];?>">                         
                                <img class="left__img" src="<?= BASE_URL . '/assets/img/posts/' . $post['img']?>" alt="<?=mb_substr($post['title'], 0, 52, 'UTF-8') . '...'?>">
                            </a> 
                        <?php else:?>
                            <img class="left__img" src="<?= BASE_URL . '/assets/img/icon/nophoto.png' ?>" alt="">  
                        <?php endif;?> 
                    </div>
                    <div class="left__content">
                        <h2 class="left__title">
                            <?php if(mb_strlen($post['title'],'UTF-8')>35):?>
                                <a class="left__title-link" href="<?=BASE_URL . 'pages/post.php?post=' . $post['id'];?>"><?=mb_substr($post['title'], 0, 35, 'UTF-8') . '...'?></a>                         
                            <?php else:?>
                                <a class="left__title-link" href="<?=BASE_URL . 'pages/post.php?post=' . $post['id'];?>"><?=$post['title'];?></a>
                            <?php endif;?>
                        </h2>
                        
                        <?php if(mb_strlen($post['content'],'UTF-8')>200):?>											
                            <div class="left__description"><?=mb_substr($post['anons'], 0, 200, 'UTF-8') . '...'?></div>
                        <?php else:?>
                            <div class="left__description"><?=$post['anons'];?></div>
                        <?php endif;?>
                        
                        <div class="left__footer">
                            <ul class="left__data">
                                <?php
                                // ✅ ИСПРАВЛЕНО: было $$rawDate (два доллара)
                                $rawDate = $post['created_data'] ?? $post['created_at'] ?? '';
                                $timestamp = strtotime($rawDate);
                                $displayDate = ($timestamp !== false && $timestamp > 0) ? date('d.m.Y', $timestamp) : '';
                                ?>
                                <li class="news__data-item">
                                    <?php if ($displayDate): ?>
                                        <time datetime="<?= date('Y-m-d', $timestamp) ?>"><?= $displayDate ?></time>
                                    <?php else: ?>
                                        <time>—</time>
                                    <?php endif; ?>
                                </li>
                            </ul>
                            <a href="<?=BASE_URL . 'pages/post.php?post=' . $post['id'];?>" class="btn btn--click btn--pos">толығырақ</a>
                        </div>
                    </div>
                </article>
            </div><?php endif;?> 
            
            <div class="news__right"><?php foreach ($newposts as $key => $post): ?>
                <?php if ($key === 0) continue;?>
                <article class="news">
                    <div class="news__preview">
                        <?php if($post['img']):?>
                            <a href="<?=BASE_URL . 'pages/post.php?post=' . $post['id'];?>">                         
                                <img src="<?= BASE_URL . '/assets/img/posts/' . $post['img']?>" alt="<?=mb_substr($post['title'], 0, 52, 'UTF-8') . '...'?>">
                            </a> 
                        <?php else:?>
                            <img src="<?= BASE_URL . '/assets/img/icon/nophoto.png' ?>" alt="">  
                        <?php endif;?> 
                    </div>
                    <div class="news__content">
                        <h2 class="news__title">
                            <?php if(mb_strlen($post['title'],'UTF-8')>18):?>
                                <a class="news__title-link" href="<?=BASE_URL . 'pages/post.php?post=' . $post['id'];?>"><?=mb_substr($post['title'], 0, 18, 'UTF-8') . '...'?></a>                         
                            <?php else:?>
                                <a class="news__title-link" href="<?=BASE_URL . 'pages/post.php?post=' . $post['id'];?>"><?=$post['title'];?></a>
                            <?php endif;?>
                        </h2>
                        <?php if(mb_strlen($post['content'],'UTF-8')>200):?>											
                            <p class="news__description"><?=mb_substr($post['anons'], 0, 200, 'UTF-8') . '...'?></p>
                        <?php else:?>
                            <p class="news__description"><?=$post['anons'];?></p>
                        <?php endif;?>
                        <div class="news__footer">
                            <ul class="news__data">
                                <?php
                                // ✅ ИСПРАВЛЕНО: универсальная обработка даты
                                $rawDate = $post['created_data'] ?? $post['created_at'] ?? '';
                                $timestamp = strtotime($rawDate);
                                $displayDate = ($timestamp !== false && $timestamp > 0) ? date('d.m.Y', $timestamp) : '';
                                ?>
                                <li class="news__data-item">
                                    <?php if ($displayDate): ?>
                                        <time datetime="<?= date('Y-m-d', $timestamp) ?>"><?= $displayDate ?></time>
                                    <?php else: ?>
                                        <time>—</time>
                                    <?php endif; ?>
                                </li>
                            </ul>
                            <a href="<?=BASE_URL . 'pages/post.php?post=' . $post['id'];?>" class="news_read btn btn--min">толығырақ</a>
                        </div>
                    </div>
                </article><?php endforeach;?> 
            </div>
             
        </div>
        <div class="news__button">
            <a href="<?=BASE_URL . 'pages/postAll.php';?>" class="btn btn--click">Барлық жаңалықтар</a>
        </div>
    </div>
</div>