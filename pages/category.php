<?php 
    require __DIR__ . '/../path.php';
    include __DIR__ . '/../app/controllers/s_topics.php";

    $post = selectOneFromPostsWithPost('s_posts', $_GET['category']);

    include ("../include/head.php"); 

     //------sprite------>
    include ("../include/sprite.php"); 
    //------header------>
    include ("../include/pages_header.php"); 

    

?>
                     <div class="advice" id="advice">
        <div class="container">
            
                <div class="post__inner">
                    <div class="post__img">
                        <?php if($post['img']):?>
								<img src="<?= BASE_URL . '/assets/img/posts/' . $post['img']?>" alt="<?=mb_substr($post['title'], 0, 50, 'UTF-8') . '...'?>">
							<?php else:?>
								<img class="noimg" src="<?= BASE_URL . '/assets/img/icon/nophoto.png' ?>" alt="">  
							<?php endif;?> 
                    </div>
                    <div class="post__content">
                        <h1 class="post__title"><?=$post['title'];?></h1>
                        
                            <?php if(mb_strlen($post['content'],'UTF-8')>200):?>											
                                <h2 class="post__anons"><?=mb_substr($post['content'], 0, 200, 'UTF-8') . '...'?></h2>
                            <?php else:?>
                                <h2 class="post__anons"><?=$post['content'];?></h2>
                            <?php endif;?>
                        


                        <div class="post__info">
                            <div class="post__data">
                                <time datetime="2022-11-10 10:02"><?=$post['created_data'];?></time>
                            </div>
                            <div class="post_social">
                                <div class="social social--footer">
                                    <a href="" class="social__item" target="_blank">
                                        <svg class="social__icon">
                                            <use xlink:href="#facebook"> </use>
                                        </svg>
                                    </a>
                                    <a href="" class="social__item" target="_blank">
                                        <svg class="social__icon">
                                            <use xlink:href="#instagram"> </use>
                                        </svg>
                                    </a>    
                                </div>
                            </div>
                        </div>  
                    </div>
                      
                </div>
                
        </div>
    </div>

    <div class="container">
        <div class="posttext__border">
            <div class="posttext__description"><?=$post['content'];?></div>
            <!-- <a class="btn btn--doc" target="_blank" href="">файлді жүктеу</a> -->
        </div> 
        
       
    </div>

                        
        <?php
    //------resource------>
    include ("../include/resource.php");
    //------footer------>
    include ("../include/footer.php"); 
?>

